<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvestmentPackage;
use App\Models\Investment;
use App\Services\MLMService;
use Illuminate\Support\Facades\Auth;

class InvestmentController extends Controller
{
    protected $mlmService;

    public function __construct(MLMService $mlmService)
    {
        $this->middleware('auth');
        $this->mlmService = $mlmService;
    }

    /**
     * Display investment packages
     */
    public function index()
    {
        $packages = InvestmentPackage::active()->get();
        $userInvestments = Auth::user()->investments()->latest()->with('investmentPackage')->get();

        return view('investments.index', compact('packages', 'userInvestments'));
    }

    /**
     * Show investment package details
     */
    public function show(InvestmentPackage $package)
    {
        return view('investments.show', compact('package'));
    }

    /**
     * Process new investment
     */
    public function invest(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:investment_packages,id',
            'amount' => 'required|numeric|min:5'
        ]);

        try {
            $user = Auth::user();
            $package = InvestmentPackage::findOrFail($request->package_id);

            // Validate investment amount matches package amount
            if ($request->amount != $package->amount) {
                return back()->withErrors(['amount' => 'Investment amount must match package amount']);
            }

            $investment = $this->mlmService->createInvestment($user, $request->package_id, $request->amount);

            return redirect()->route('investments.index')
                ->with('success', 'Investment created successfully! Daily returns will start tomorrow.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show investment details
     */
    public function investmentDetails(Investment $investment)
    {
        // Ensure user can only view their own investments
        if ($investment->user_id !== Auth::id()) {
            abort(403);
        }

        $investment->load(['investmentPackage', 'dailyReturns' => function ($query) {
            $query->latest('return_date');
        }]);

        return view('investments.details', compact('investment'));
    }

    /**
     * Get investment history
     */
    public function history()
    {
        $investments = Auth::user()->investments()
            ->with('investmentPackage')
            ->latest()
            ->paginate(10);

        return view('investments.history', compact('investments'));
    }

    /**
     * Show daily returns
     */
    public function dailyReturns()
    {
        $dailyReturns = Auth::user()->dailyReturns()
            ->with(['investment', 'investment.investmentPackage'])
            ->latest('return_date')
            ->paginate(15);

        return view('investments.daily-returns', compact('dailyReturns'));
    }
}
