<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\WalletTransaction;
use App\Models\GiftCard;
use App\Models\WithdrawalRequest;
use App\Models\User;
use App\Services\WalletService;
use Exception;

class WalletController extends Controller
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->middleware('auth');
        $this->walletService = $walletService;
    }

    /**
     * Display wallet dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Get wallet summary with isolated balances
        $walletSummary = $this->walletService->getWalletSummary($user);

        // Get recent transactions
        $transactions = $user->walletTransactions()
            ->with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Get pending withdrawal requests
        $pendingWithdrawals = $user->withdrawalRequests()
            ->pending()
            ->latest()
            ->limit(5)
            ->get();

        // Get gift cards
        $createdGiftCards = $user->createdGiftCards()
            ->latest()
            ->limit(5)
            ->get();

        return view('wallet.index', compact(
            'user',
            'walletSummary',
            'transactions',
            'pendingWithdrawals',
            'createdGiftCards'
        ));
    }

    /**
     * Show add funds page
     */
    public function addFunds()
    {
        return view('wallet.add-funds');
    }

    /**
     * Process Stripe payment
     */
    public function processStripePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:5|max:10000',
            'stripeToken' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Here you would integrate with Stripe API
            // For now, we'll simulate a successful payment

            $user = Auth::user();
            $amount = $request->amount;

            // Create wallet transaction
            $transaction = WalletTransaction::create([
                'user_id' => $user->id,
                'type' => WalletTransaction::TYPE_CREDIT,
                'category' => WalletTransaction::CATEGORY_DEPOSIT,
                'amount' => $amount,
                'payment_method' => WalletTransaction::PAYMENT_METHOD_STRIPE,
                'payment_reference' => 'stripe_' . uniqid(),
                'description' => 'Investment deposit via Stripe - $' . number_format($amount, 2),
                'status' => WalletTransaction::STATUS_COMPLETED,
                'processed_at' => now()
            ]);

            // Add to investment balance (isolated from withdrawable funds)
            $this->walletService->addInvestmentFunds($user, $amount, 'Deposit via Stripe - $' . number_format($amount, 2));

            DB::commit();

            return redirect()->route('wallet')
                ->with('success', 'Funds added successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Payment failed. Please try again.');
        }
    }

    /**
     * Process PayPal payment
     */
    public function processPayPalPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:5|max:10000'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Here you would integrate with PayPal API
            // For now, we'll simulate a successful payment

            $user = Auth::user();
            $amount = $request->amount;

            // Create wallet transaction
            $transaction = WalletTransaction::create([
                'user_id' => $user->id,
                'type' => WalletTransaction::TYPE_CREDIT,
                'category' => WalletTransaction::CATEGORY_DEPOSIT,
                'amount' => $amount,
                'payment_method' => WalletTransaction::PAYMENT_METHOD_PAYPAL,
                'payment_reference' => 'paypal_' . uniqid(),
                'description' => 'Investment deposit via PayPal - $' . number_format($amount, 2),
                'status' => WalletTransaction::STATUS_COMPLETED,
                'processed_at' => now()
            ]);

            // Add to investment balance (isolated from withdrawable funds)
            $this->walletService->addInvestmentFunds($user, $amount, 'Deposit via PayPal - $' . number_format($amount, 2));

            DB::commit();

            return redirect()->route('wallet')
                ->with('success', 'Funds added successfully via PayPal!');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Payment failed. Please try again.');
        }
    }

    /**
     * Submit bank transfer request
     */
    public function submitBankTransfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:20|max:10000',
            'transfer_reference' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = Auth::user();
            $amount = $request->amount;

            // Create pending wallet transaction
            $transaction = WalletTransaction::create([
                'user_id' => $user->id,
                'type' => WalletTransaction::TYPE_CREDIT,
                'category' => WalletTransaction::CATEGORY_DEPOSIT,
                'amount' => $amount,
                'payment_method' => WalletTransaction::PAYMENT_METHOD_BANK_TRANSFER,
                'payment_reference' => $request->transfer_reference,
                'description' => 'Bank Transfer Deposit - $' . number_format($amount, 2),
                'status' => WalletTransaction::STATUS_PENDING,
                'metadata' => [
                    'transfer_reference' => $request->transfer_reference,
                    'submitted_at' => now()->toISOString()
                ]
            ]);

            return redirect()->route('wallet')
                ->with('success', 'Bank transfer request submitted. Funds will be added after verification.');

        } catch (Exception $e) {
            return back()->with('error', 'Failed to submit bank transfer request.');
        }
    }

    /**
     * Show gift card management
     */
    public function giftCards()
    {
        $user = Auth::user();

        $createdGiftCards = $user->createdGiftCards()
            ->latest()
            ->paginate(10, ['*'], 'created');

        $redeemedGiftCards = $user->redeemedGiftCards()
            ->latest()
            ->paginate(10, ['*'], 'redeemed');

        return view('wallet.gift-cards', compact('createdGiftCards', 'redeemedGiftCards'));
    }

    /**
     * Create gift card
     */
    public function createGiftCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:5|max:5',
            'message' => 'nullable|string|max:200'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $amount = $request->amount;

            // Check if user has sufficient balance
            if ($user->wallet_balance < $amount) {
                return back()->with('error', 'Insufficient wallet balance.');
            }

            // Create gift card
            $giftCard = GiftCard::create([
                'created_by' => $user->id,
                'amount' => $amount,
                'balance' => $amount,
                'message' => $request->message,
                'expires_at' => now()->addYear(), // Gift cards expire in 1 year
                'status' => GiftCard::STATUS_ACTIVE
            ]);

            // Create debit transaction
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => WalletTransaction::TYPE_DEBIT,
                'category' => WalletTransaction::CATEGORY_GIFT_CARD,
                'amount' => $amount,
                'payment_method' => WalletTransaction::PAYMENT_METHOD_GIFT_CARD,
                'payment_reference' => $giftCard->code,
                'description' => 'Gift Card Created - Code: ' . $giftCard->code,
                'status' => WalletTransaction::STATUS_COMPLETED,
                'processed_at' => now()
            ]);

            // Update user balance
            $user->decrement('wallet_balance', $amount);

            DB::commit();

            return redirect()->route('wallet.gift-cards')
                ->with('success', 'Gift card created successfully! Code: ' . $giftCard->code);

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create gift card.');
        }
    }

    /**
     * Redeem gift card
     */
    public function redeemGiftCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gift_card_code' => 'required|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $code = strtoupper(trim($request->gift_card_code));

            // Find gift card
            $giftCard = GiftCard::where('code', $code)->first();

            if (!$giftCard) {
                return back()->with('error', 'Invalid gift card code.');
            }

            if (!$giftCard->isRedeemable()) {
                return back()->with('error', 'Gift card is not redeemable (expired, used, or inactive).');
            }

            if ($giftCard->created_by === $user->id) {
                return back()->with('error', 'You cannot redeem your own gift card.');
            }

            $redeemedAmount = $giftCard->redeem($user);

            if (!$redeemedAmount) {
                return back()->with('error', 'Failed to redeem gift card.');
            }

            // Create credit transaction
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => WalletTransaction::TYPE_CREDIT,
                'category' => WalletTransaction::CATEGORY_GIFT_CARD,
                'amount' => $redeemedAmount,
                'payment_method' => WalletTransaction::PAYMENT_METHOD_GIFT_CARD,
                'payment_reference' => $giftCard->code,
                'description' => 'Gift Card Redeemed - Code: ' . $giftCard->code,
                'status' => WalletTransaction::STATUS_COMPLETED,
                'processed_at' => now()
            ]);

            // Update user balance
            $user->increment('wallet_balance', $redeemedAmount);

            DB::commit();

            return redirect()->route('wallet')
                ->with('success', 'Gift card redeemed successfully! $' . number_format($redeemedAmount, 2) . ' added to your wallet.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to redeem gift card.');
        }
    }

    /**
     * Show withdrawal form
     */
    public function withdrawal()
    {
        $user = Auth::user();

        $withdrawalRequests = $user->withdrawalRequests()
            ->latest()
            ->paginate(10);

        return view('wallet.withdrawal', compact('withdrawalRequests'));
    }

    /**
     * Submit withdrawal request
     */
    public function submitWithdrawal(Request $request)
    {
        // Base validation rules
        $rules = [
            'amount' => 'required|numeric|min:2|max:10000',
            'balance_type' => 'required|in:commission,returns,both',
            'method' => 'required|in:bank,mbook',
            'notes' => 'nullable|string|max:500'
        ];

        // Add method-specific validation
        if ($request->method === 'bank') {
            $rules += [
                'bank_name' => 'required|string|max:100',
                'account_holder_name' => 'required|string|max:100',
                'account_number' => 'required|string|max:20',
                'routing_number' => 'required|string|size:9',
                'swift_code' => 'nullable|string|max:11',
                'bank_address' => 'nullable|string|max:200',
                'bank_city' => 'nullable|string|max:50',
                'bank_state' => 'nullable|string|max:50',
                'bank_zip_code' => 'nullable|string|max:10',
                'bank_country' => 'required|string|max:2',
                'account_type' => 'required|in:checking,savings'
            ];
        } elseif ($request->method === 'mbook') {
            $rules += [
                'mbook_name' => 'required|string|max:100',
                'mbook_country' => 'required|string|max:100',
                'mbook_currency' => 'required|string|max:10',
                'mbook_wallet_id' => 'required|string|max:50'
            ];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = Auth::user();
            $amount = $request->amount;

            // Check if user has sufficient withdrawable balance
            if ($user->withdrawable_balance < $amount) {
                return back()->with('error', 'Insufficient withdrawable balance. Only commission and investment returns can be withdrawn.');
            }

            // Calculate processing fee to show user
            $bankCountry = $request->bank_country ?? 'US';
            $feeInfo = WithdrawalRequest::calculateProcessingFee($amount, $request->method, $bankCountry);

            // Create withdrawal request using WalletService
            $withdrawalRequest = $this->walletService->createWithdrawalRequest($user, $request->all());

            $nextProcessing = $user->getNextWithdrawalDate();

            $successMessage = 'Withdrawal request submitted successfully! ' .
                            'Request ID: #' . $withdrawalRequest->id .
                            '. Gross Amount: $' . number_format($amount, 2) .
                            '. Processing Fee: $' . number_format($feeInfo['fee_amount'], 2) . ' (' . $feeInfo['fee_rate'] . '%)' .
                            '. Net Amount: $' . number_format($feeInfo['net_amount'], 2) .
                            '. Processing date: ' . $nextProcessing['date']->format('M j, Y') .
                            ' (' . $nextProcessing['days_until'] . ' days from now)';

            return redirect()->route('wallet.withdrawal')
                ->with('success', $successMessage);

        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show transaction history
     */
    public function transactions()
    {
        $user = Auth::user();

        $transactions = $user->walletTransactions()
            ->latest()
            ->paginate(20);

        return view('wallet.transactions', compact('transactions'));
    }
}
