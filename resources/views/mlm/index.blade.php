@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="mb-2 fw-bold text-gradient">
                        <i class="fas fa-users-cog text-primary"></i>
                        MLM Dashboard
                    </h1>
                    <p class="text-muted mb-0">Manage your MLM business and track your earnings</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('mlm.referral-link') }}" class="btn btn-success">
                        <i class="fas fa-share-alt"></i>
                        <span class="d-none d-sm-inline">Get Referral Link</span>
                        <span class="d-sm-none">Share</span>
                    </a>
                    <a href="{{ route('mlm.genealogy') }}" class="btn btn-info">
                        <i class="fas fa-sitemap"></i>
                        <span class="d-none d-sm-inline">Genealogy Tree</span>
                        <span class="d-sm-none">Tree</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- MLM Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-gradient-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1 opacity-75">Direct Referrals</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['direct_referrals'] }}</h3>
                            <small class="opacity-75">Level 1 Members</small>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-user-plus fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white bg-opacity-25 border-0">
                    <small class="text-white">
                        <i class="fas fa-arrow-up"></i>
                        10% commission rate
                    </small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card bg-gradient-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1 opacity-75">Total Team</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['total_team'] }}</h3>
                            <small class="opacity-75">All Generations</small>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white bg-opacity-25 border-0">
                    <small class="text-white">
                        <i class="fas fa-layer-group"></i>
                        4 levels deep
                    </small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card bg-gradient-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1 opacity-75">Total Commissions</h6>
                            <h3 class="mb-0 fw-bold">${{ number_format($stats['total_commissions'], 2) }}</h3>
                            <small class="opacity-75">All Time Earnings</small>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-hand-holding-usd fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white bg-opacity-25 border-0">
                    <small class="text-white">
                        <i class="fas fa-chart-line"></i>
                        Total earned
                    </small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card bg-gradient-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1 opacity-75">Available Balance</h6>
                            <h3 class="mb-0 fw-bold">${{ number_format($stats['commission_balance'], 2) }}</h3>
                            <small class="opacity-75">Ready to withdraw</small>
                        </div>
                        <div class="text-end">
                            <i class="fas fa-wallet fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white bg-opacity-25 border-0">
                    <small class="text-white">
                        <i class="fas fa-credit-card"></i>
                        Commission balance
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Investments by Level -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-layer-group text-primary"></i>
                            Team Investments by Generation
                        </h5>
                        <span class="badge bg-primary">4 Levels</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @php
                            $levelColors = ['success', 'info', 'warning', 'danger'];
                            $levelNames = ['Direct Team', 'Level 2', 'Level 3', 'Level 4'];
                            $commissionRates = [10, 4, 3, 2];
                        @endphp

                        @for($level = 1; $level <= 4; $level++)
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 border border-{{ $levelColors[$level-1] }} rounded-3 h-100">
                                <div class="mb-3">
                                    <i class="fas fa-users fa-2x text-{{ $levelColors[$level-1] }}"></i>
                                </div>
                                <h4 class="text-{{ $levelColors[$level-1] }} fw-bold mb-1">
                                    ${{ number_format($stats['team_investments'][$level] ?? 0, 2) }}
                                </h4>
                                <h6 class="mb-2">{{ $levelNames[$level-1] }}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Commission Rate</small>
                                    <span class="badge bg-{{ $levelColors[$level-1] }}">{{ $commissionRates[$level-1] }}%</span>
                                </div>
                                <div class="mt-2">
                                    <small class="text-success fw-semibold">
                                        Your Earnings: ${{ number_format(($stats['team_investments'][$level] ?? 0) * ($commissionRates[$level-1] / 100), 2) }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Commissions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-history text-primary"></i>
                            Recent Commissions
                        </h5>
                        <a href="{{ route('mlm.commissions') }}" class="btn btn-outline-primary btn-sm">
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
                                        <th class="border-0 fw-semibold">From Member</th>
                                        <th class="border-0 fw-semibold">Type</th>
                                        <th class="border-0 fw-semibold">Level</th>
                                        <th class="border-0 fw-semibold">Amount</th>
                                        <th class="border-0 fw-semibold">Rate</th>
                                        <th class="border-0 fw-semibold">Status</th>
                                        <th class="border-0 fw-semibold">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentCommissions as $commission)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-3">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-semibold">{{ $commission->fromUser->name }}</h6>
                                                    <small class="text-muted">{{ $commission->fromUser->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $commission->type == 'direct' ? 'success' : 'info' }}">
                                                <i class="fas fa-{{ $commission->type == 'direct' ? 'user-check' : 'users' }}"></i>
                                                {{ ucfirst($commission->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">Level {{ $commission->level }}</span>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 text-success fw-bold">${{ number_format($commission->amount, 2) }}</h6>
                                        </td>
                                        <td>
                                            <span class="text-primary fw-semibold">{{ $commission->percentage }}%</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $commission->status == 'paid' ? 'success' : ($commission->status == 'pending' ? 'warning' : 'danger') }}">
                                                <i class="fas fa-{{ $commission->status == 'paid' ? 'check' : ($commission->status == 'pending' ? 'clock' : 'times') }}"></i>
                                                {{ ucfirst($commission->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                {{ $commission->created_at->format('M d, Y') }}
                                                <br><small class="text-muted">{{ $commission->created_at->format('h:i A') }}</small>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-hand-holding-usd fa-4x text-muted"></i>
                            </div>
                            <h4 class="text-muted mb-3">No Commissions Yet</h4>
                            <p class="text-muted mb-4">Start building your team to earn commissions from their investments!</p>
                            <a href="{{ route('mlm.referral-link') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-share-alt"></i>
                                Get Your Referral Link
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-bolt text-primary"></i>
                        MLM Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('mlm.referral-link') }}" class="btn btn-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                <i class="fas fa-link fa-2x mb-2"></i>
                                <strong>Get Referral Link</strong>
                                <small class="opacity-75">Share and earn commissions</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('mlm.genealogy') }}" class="btn btn-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                <i class="fas fa-sitemap fa-2x mb-2"></i>
                                <strong>View Genealogy</strong>
                                <small class="opacity-75">See your network tree</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('mlm.referrals') }}" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                <i class="fas fa-user-friends fa-2x mb-2"></i>
                                <strong>Manage Referrals</strong>
                                <small class="opacity-75">Track direct team</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('mlm.team') }}" class="btn btn-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                <strong>Team Overview</strong>
                                <small class="opacity-75">Monitor performance</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.text-gradient {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

@media (max-width: 767.98px) {
    .user-avatar {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }

    .table-responsive {
        font-size: 0.875rem;
    }

    .card-body .row .col-lg-3 .btn {
        padding: 2rem 1rem;
    }
}
</style>
@endsection
