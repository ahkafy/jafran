@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-wallet"></i> My Wallet</h4>
                </div>
                <div class="card-body">
                    <!-- Wallet Balance Cards -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card text-white bg-primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Wallet Balance</h6>
                                            <h3>${{ number_format($user->wallet_balance, 2) }}</h3>
                                            <small>Available for investments</small>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-wallet fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card text-white bg-success">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Commission Balance</h6>
                                            <h3>${{ number_format($user->commission_balance, 2) }}</h3>
                                            <small>Earned from MLM</small>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-hand-holding-usd fa-3x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Balance -->
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="card text-white bg-warning">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total Balance</h5>
                                    <h2>${{ number_format($user->wallet_balance + $user->commission_balance, 2) }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <a href="{{ route('investments.index') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-chart-line"></i> Invest Now
                            </a>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-success btn-block" disabled>
                                <i class="fas fa-download"></i> Withdraw (Coming Soon)
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-info btn-block" disabled>
                                <i class="fas fa-plus"></i> Add Funds (Coming Soon)
                            </button>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Recent Transactions</h5>
                        </div>
                        <div class="card-body">
                            @if($transactions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($transactions as $transaction)
                                            <tr>
                                                <td>
                                                    @if($transaction['type'] == 'return')
                                                        <span class="badge badge-primary">Return</span>
                                                    @elseif($transaction['type'] == 'commission')
                                                        <span class="badge badge-success">Commission</span>
                                                    @endif
                                                </td>
                                                <td>{{ $transaction['description'] }}</td>
                                                <td class="text-success">+${{ number_format($transaction['amount'], 2) }}</td>
                                                <td>
                                                    <span class="badge badge-success">Credit</span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($transaction['date'])->format('M d, Y H:i') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No transactions yet</h5>
                                    <p class="text-muted">Your transaction history will appear here</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
