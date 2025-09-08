@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2 fw-bold">
                        <i class="fas fa-wallet text-primary"></i>
                        My Wallet
                    </h1>
                    <p class="text-muted mb-0">Manage your funds, transactions, and payment methods</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('wallet.add-funds') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        <span class="d-none d-sm-inline">Add Funds</span>
                    </a>
                    <a href="{{ route('wallet.withdrawal') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-minus"></i>
                        <span class="d-none d-sm-inline">Withdraw</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Balance Cards -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">Investment Balance</h6>
                            <h2 class="mb-0 fw-bold">${{ number_format($walletSummary['investment_balance'], 2) }}</h2>
                            <small class="opacity-75">For investments only</small>
                        </div>
                        <div class="text-end">
                            <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                                <i class="fas fa-chart-line fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">Commission Balance</h6>
                            <h2 class="mb-0 fw-bold">${{ number_format($walletSummary['commission_balance'], 2) }}</h2>
                            <small class="opacity-75">Network earnings</small>
                        </div>
                        <div class="text-end">
                            <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                                <i class="fas fa-percentage fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">Return Balance</h6>
                            <h2 class="mb-0 fw-bold">${{ number_format($walletSummary['return_balance'], 2) }}</h2>
                            <small class="opacity-75">Investment returns</small>
                        </div>
                        <div class="text-end">
                            <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                                <i class="fas fa-piggy-bank fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 opacity-75">Withdrawable Balance</h6>
                            <h2 class="mb-0 fw-bold">${{ number_format($walletSummary['withdrawable_balance'], 2) }}</h2>
                            <small class="opacity-75">Available for withdrawal</small>
                        </div>
                        <div class="text-end">
                            <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                                <i class="fas fa-hand-holding-usd fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wallet Information Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="card-title">
                                <i class="fas fa-info-circle text-primary"></i>
                                Wallet Isolation System
                            </h5>
                            <div class="alert alert-info mb-3">
                                <strong>Important:</strong> Your wallet now uses an isolated balance system for better security and compliance.
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-chart-line text-warning me-2"></i>
                                    <strong>Investment Balance:</strong> Added funds that must be used for investments
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-percentage text-success me-2"></i>
                                    <strong>Commission Balance:</strong> Earnings from your network referrals
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-piggy-bank text-info me-2"></i>
                                    <strong>Return Balance:</strong> Daily returns from your investments
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-hand-holding-usd text-purple me-2"></i>
                                    <strong>Withdrawable Balance:</strong> Combined commission + returns (can be withdrawn)
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded">
                                <h6 class="mb-3">Next Withdrawal Processing</h6>
                                <div class="text-center">
                                    <div class="h4 text-primary mb-1">{{ $walletSummary['next_processing_date']['date']->format('M j, Y') }}</div>
                                    <small class="text-muted">{{ $walletSummary['next_processing_date']['days_until'] }} days from now</small>
                                </div>
                                <hr>
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Withdrawals processed twice monthly: 1st & 16th
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="container">
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('wallet.add-funds') }}" class="card border-0 shadow-sm text-decoration-none">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                        <i class="fas fa-plus fa-lg text-primary"></i>
                    </div>
                    <h6 class="mb-1 fw-semibold">Add Funds</h6>
                    <small class="text-muted">Stripe, PayPal, Bank Transfer</small>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6">
            <a href="{{ route('wallet.withdrawal') }}" class="card border-0 shadow-sm text-decoration-none">
                <div class="card-body text-center">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                        <i class="fas fa-minus fa-lg text-warning"></i>
                    </div>
                    <h6 class="mb-1 fw-semibold">Withdraw Funds</h6>
                    <small class="text-muted">EFT to Bank Account</small>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6">
            <a href="{{ route('wallet.gift-cards') }}" class="card border-0 shadow-sm text-decoration-none">
                <div class="card-body text-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                        <i class="fas fa-gift fa-lg text-success"></i>
                    </div>
                    <h6 class="mb-1 fw-semibold">Gift Cards</h6>
                    <small class="text-muted">Create & Redeem</small>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6">
            <a href="{{ route('wallet.transactions') }}" class="card border-0 shadow-sm text-decoration-none">
                <div class="card-body text-center">
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                        <i class="fas fa-history fa-lg text-info"></i>
                    </div>
                    <h6 class="mb-1 fw-semibold">Transaction History</h6>
                    <small class="text-muted">View all transactions</small>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Recent Transactions -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-list text-primary"></i>
                            Recent Transactions
                        </h5>
                        <a href="{{ route('wallet.transactions') }}" class="btn btn-sm btn-outline-primary">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <tbody>
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-{{ $transaction->type === 'credit' ? 'success' : 'danger' }} bg-opacity-10 p-2 rounded me-3">
                                                    <i class="{{ $transaction->category_icon }} text-{{ $transaction->type === 'credit' ? 'success' : 'danger' }}"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-semibold">{{ $transaction->description }}</h6>
                                                    <small class="text-muted">{{ $transaction->created_at->format('M d, Y H:i') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <h6 class="mb-1 fw-bold text-{{ $transaction->type === 'credit' ? 'success' : 'danger' }}">
                                                {{ $transaction->type === 'credit' ? '+' : '-' }}${{ $transaction->formatted_amount }}
                                            </h6>
                                            <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted mb-3">No Transactions Yet</h5>
                            <p class="text-muted">Your transaction history will appear here</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Status -->
        <div class="col-lg-4">
            <!-- Gift Card Redeem -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-gift text-success"></i>
                        Redeem Gift Card
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('wallet.redeem-gift-card') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input type="text" class="form-control" name="gift_card_code"
                                   placeholder="Enter gift card code" required>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm w-100">
                            <i class="fas fa-gift"></i> Redeem Now
                        </button>
                    </form>
                </div>
            </div>

            <!-- Pending Withdrawals -->
            @if($pendingWithdrawals->count() > 0)
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-clock"></i>
                        Pending Withdrawals
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($pendingWithdrawals as $withdrawal)
                    <div class="d-flex justify-content-between align-items-center {{ !$loop->last ? 'mb-2' : '' }}">
                        <div>
                            <small class="text-muted">Request #{{ $withdrawal->id }}</small>
                            <br><strong>${{ $withdrawal->formatted_amount }}</strong>
                        </div>
                        <span class="badge bg-warning">{{ ucfirst($withdrawal->status) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Gift Cards -->
            @if($createdGiftCards->count() > 0)
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-gift text-primary"></i>
                        Recent Gift Cards
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($createdGiftCards as $giftCard)
                    <div class="d-flex justify-content-between align-items-center {{ !$loop->last ? 'mb-2' : '' }}">
                        <div>
                            <small class="text-muted">{{ $giftCard->code }}</small>
                            <br><strong>${{ $giftCard->formatted_balance }}</strong>
                        </div>
                        <span class="badge bg-{{ $giftCard->status === 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($giftCard->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
</div>

<style>
.card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
}

@media (max-width: 767.98px) {
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
@endsection
