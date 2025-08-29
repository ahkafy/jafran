@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2 fw-bold">
                        <i class="fas fa-tachometer-alt text-primary"></i>
                        Dashboard
                    </h1>
                    <p class="text-muted mb-0">Welcome back! Here's an overview of your account</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('investments.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        <span class="d-none d-sm-inline">New Investment</span>
                    </a>
                    <a href="{{ route('mlm.referral-link') }}" class="btn btn-success">
                        <i class="fas fa-share-alt"></i>
                        <span class="d-none d-sm-inline">Share Link</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1 fw-semibold">Wallet Balance</h6>
                            <h3 class="mb-0 fw-bold">${{ number_format($stats['wallet_balance'], 2) }}</h3>
                            <small class="text-success">
                                <i class="fas fa-arrow-up"></i>
                                Available for investment
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-wallet fa-lg text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1 fw-semibold">Commission Balance</h6>
                            <h3 class="mb-0 fw-bold">${{ number_format($stats['commission_balance'], 2) }}</h3>
                            <small class="text-success">
                                <i class="fas fa-chart-line"></i>
                                Network earnings
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-hand-holding-usd fa-lg text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1 fw-semibold">Active Investments</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['active_investments'] }}</h3>
                            <small class="text-info">
                                <i class="fas fa-clock"></i>
                                Earning daily returns
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-chart-line fa-lg text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1 fw-semibold">Total Team</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['total_team'] }}</h3>
                            <small class="text-warning">
                                <i class="fas fa-users"></i>
                                Team network
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-users fa-lg text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rank Information -->
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-trophy text-warning"></i>
                        Your Rank Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <div class="bg-{{ auth()->user()->getRankInfo()['color'] }} bg-opacity-10 p-3 rounded-circle">
                                        <i class="fas fa-{{ auth()->user()->getRankInfo()['icon'] }} fa-2x text-{{ auth()->user()->getRankInfo()['color'] }}"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="mb-1 fw-bold">{{ auth()->user()->rank ?? 'Guest' }}</h4>
                                    <p class="text-muted mb-0">{{ auth()->user()->getRankInfo()['requirements'] }}</p>
                                    @if(auth()->user()->rank_achieved_at)
                                        <small class="text-success">
                                            <i class="fas fa-calendar"></i>
                                            Achieved on {{ auth()->user()->rank_achieved_at->format('M d, Y') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @php
                                $progress = auth()->user()->getRankProgress();
                            @endphp
                            @if($progress['next_rank'])
                                <h6 class="fw-semibold mb-3">Next Rank: {{ $progress['next_rank'] }}</h6>
                                @foreach($progress['progress'] as $key => $req)
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <small class="fw-semibold">{{ ucwords(str_replace('_', ' ', $key)) }}</small>
                                            <small class="text-muted">{{ $req['current'] }}/{{ $req['required'] }}</small>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-{{ $req['met'] ? 'success' : 'warning' }}"
                                                 style="width: {{ min(100, ($req['current'] / $req['required']) * 100) }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center">
                                    <i class="fas fa-crown fa-3x text-warning mb-3"></i>
                                    <h6 class="fw-semibold">Maximum Rank Achieved!</h6>
                                    <p class="text-muted mb-0">You've reached the highest rank in our system.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <!-- Recent Investments -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-chart-line text-primary"></i>
                            Recent Investments
                        </h5>
                        <a href="{{ route('investments.history') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i> View All
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentInvestments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">Package</th>
                                        <th class="border-0 fw-semibold">Amount</th>
                                        <th class="border-0 fw-semibold">Status</th>
                                        <th class="border-0 fw-semibold">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentInvestments as $investment)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $investment->investmentPackage->name }}</div>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">${{ number_format($investment->amount, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $investment->status == 'active' ? 'success' : 'secondary' }}">
                                                {{ ucfirst($investment->status) }}
                                            </span>
                                        </td>
                                        <td class="text-muted">{{ $investment->created_at->format('M d') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No investments yet</h6>
                            <p class="text-muted mb-3">Start investing to earn daily returns</p>
                            <a href="{{ route('investments.index') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Start Investing
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Commissions -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-light border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-hand-holding-usd text-success"></i>
                            Recent Commissions
                        </h5>
                        <a href="{{ route('mlm.commissions') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-eye"></i> View All
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentCommissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">From</th>
                                        <th class="border-0 fw-semibold">Amount</th>
                                        <th class="border-0 fw-semibold">Level</th>
                                        <th class="border-0 fw-semibold">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentCommissions as $commission)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $commission->fromUser->name }}</div>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">${{ number_format($commission->amount, 2) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">Level {{ $commission->level }}</span>
                                        </td>
                                        <td class="text-muted">{{ $commission->paid_at->format('M d') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-hand-holding-usd fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No commissions yet</h6>
                            <p class="text-muted mb-3">Build your team to earn commissions</p>
                            <a href="{{ route('mlm.referral-link') }}" class="btn btn-success">
                                <i class="fas fa-share-alt"></i> Get Referral Link
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Returns -->
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-calendar-day text-info"></i>
                            Recent Daily Returns
                        </h5>
                        <a href="{{ route('investments.daily-returns') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-eye"></i> View All
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentReturns->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">Investment Package</th>
                                        <th class="border-0 fw-semibold">Day</th>
                                        <th class="border-0 fw-semibold">Return Amount</th>
                                        <th class="border-0 fw-semibold">Date Processed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentReturns as $return)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $return->investment->investmentPackage->name }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">Day {{ $return->day_number }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">${{ number_format($return->amount, 2) }}</span>
                                        </td>
                                        <td class="text-muted">{{ $return->processed_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-day fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No returns processed yet</h6>
                            <p class="text-muted mb-3">Daily returns will appear here once your investments start earning</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-bolt text-warning"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('investments.index') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                <strong>New Investment</strong>
                                <small class="text-muted">Start earning daily returns</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('mlm.referral-link') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                <i class="fas fa-share-alt fa-2x mb-2"></i>
                                <strong>Share Referral Link</strong>
                                <small class="text-muted">Build your team</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('mlm.genealogy') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                <i class="fas fa-sitemap fa-2x mb-2"></i>
                                <strong>View Genealogy</strong>
                                <small class="text-muted">See your network</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('wallet') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                <i class="fas fa-wallet fa-2x mb-2"></i>
                                <strong>Wallet Details</strong>
                                <small class="text-muted">Manage your funds</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
