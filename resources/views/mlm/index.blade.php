@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>MLM Dashboard</h2>
            <p class="text-muted">Manage your MLM business and track your earnings</p>
        </div>
    </div>

    <!-- MLM Stats Cards -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Direct Referrals</h6>
                            <h4>{{ $stats['direct_referrals'] }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-plus fa-2x"></i>
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

        <div class="col-md-3 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Commissions</h6>
                            <h4>${{ number_format($stats['total_commissions'], 2) }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-hand-holding-usd fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Commission Balance</h6>
                            <h4>${{ number_format($stats['commission_balance'], 2) }}</h4>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-wallet fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Investments by Level -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Team Investments by Generation</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-primary">${{ number_format($stats['team_investments'][1] ?? 0, 2) }}</h4>
                                <p class="mb-0">Level 1 (Direct)</p>
                                <small class="text-muted">10% Commission</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-info">${{ number_format($stats['team_investments'][2] ?? 0, 2) }}</h4>
                                <p class="mb-0">Level 2</p>
                                <small class="text-muted">4% Commission</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-success">${{ number_format($stats['team_investments'][3] ?? 0, 2) }}</h4>
                                <p class="mb-0">Level 3</p>
                                <small class="text-muted">3% Commission</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h4 class="text-warning">${{ number_format($stats['team_investments'][4] ?? 0, 2) }}</h4>
                                <p class="mb-0">Level 4</p>
                                <small class="text-muted">2% Commission</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Commissions -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Commissions</h5>
                </div>
                <div class="card-body">
                    @if($recentCommissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>From User</th>
                                        <th>Type</th>
                                        <th>Level</th>
                                        <th>Amount</th>
                                        <th>Percentage</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentCommissions as $commission)
                                    <tr>
                                        <td>
                                            {{ $commission->fromUser->name }}
                                            <br><small class="text-muted">{{ $commission->fromUser->email }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $commission->type == 'direct' ? 'primary' : 'info' }}">
                                                {{ ucfirst($commission->type) }}
                                            </span>
                                        </td>
                                        <td>Level {{ $commission->level }}</td>
                                        <td>${{ number_format($commission->amount, 2) }}</td>
                                        <td>{{ $commission->percentage }}%</td>
                                        <td>
                                            <span class="badge badge-{{ $commission->status == 'paid' ? 'success' : 'warning' }}">
                                                {{ ucfirst($commission->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $commission->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('mlm.commissions') }}" class="btn btn-primary">
                                View All Commissions
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-hand-holding-usd fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No commissions yet</h5>
                            <p class="text-muted">Start building your team to earn commissions!</p>
                            <a href="{{ route('mlm.referral-link') }}" class="btn btn-success">
                                Get Your Referral Link
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>MLM Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('mlm.referral-link') }}" class="btn btn-success btn-block">
                                <i class="fas fa-link"></i> Get Referral Link
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('mlm.genealogy') }}" class="btn btn-info btn-block">
                                <i class="fas fa-sitemap"></i> View Genealogy
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('mlm.referrals') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-users"></i> Manage Referrals
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('mlm.team') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-chart-bar"></i> Team Overview
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
