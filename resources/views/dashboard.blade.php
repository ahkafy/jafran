@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Summary Cards -->
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Wallet Balance</h6>
                            <h4>${{ number_format($stats['wallet_balance'], 2) }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-wallet fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Commission Balance</h6>
                            <h4>${{ number_format($stats['commission_balance'], 2) }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-hand-holding-usd fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Active Investments</h6>
                            <h4>{{ $stats['active_investments'] }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Team</h6>
                            <h4>{{ $stats['total_team'] }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Investments -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Investments</h5>
                </div>
                <div class="card-body">
                    @if($recentInvestments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Package</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentInvestments as $investment)
                                    <tr>
                                        <td>{{ $investment->investmentPackage->name }}</td>
                                        <td>${{ number_format($investment->amount, 2) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $investment->status == 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($investment->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $investment->created_at->format('M d') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No investments yet. <a href="{{ route('investments.index') }}">Start investing</a></p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Commissions -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Commissions</h5>
                </div>
                <div class="card-body">
                    @if($recentCommissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>From</th>
                                        <th>Amount</th>
                                        <th>Level</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentCommissions as $commission)
                                    <tr>
                                        <td>{{ $commission->fromUser->name }}</td>
                                        <td>${{ number_format($commission->amount, 2) }}</td>
                                        <td>Level {{ $commission->level }}</td>
                                        <td>{{ $commission->paid_at->format('M d') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No commissions yet. Start building your team!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Returns -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Daily Returns</h5>
                </div>
                <div class="card-body">
                    @if($recentReturns->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Investment</th>
                                        <th>Day</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentReturns as $return)
                                    <tr>
                                        <td>{{ $return->investment->investmentPackage->name }}</td>
                                        <td>Day {{ $return->day_number }}</td>
                                        <td>${{ number_format($return->amount, 2) }}</td>
                                        <td>{{ $return->processed_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No returns processed yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('investments.index') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-plus-circle"></i> New Investment
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('mlm.referral-link') }}" class="btn btn-success btn-block">
                                <i class="fas fa-link"></i> Share Referral Link
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('mlm.genealogy') }}" class="btn btn-info btn-block">
                                <i class="fas fa-sitemap"></i> View Genealogy
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('wallet') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-wallet"></i> Wallet Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
