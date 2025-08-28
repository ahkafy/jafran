@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-users-cog text-primary"></i>
                        Team Overview
                    </h2>
                    <p class="text-muted mb-0">Monitor your entire MLM network performance</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('mlm.genealogy') }}" class="btn btn-info">
                        <i class="fas fa-sitemap"></i> Genealogy Tree
                    </a>
                    <a href="{{ route('mlm.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Statistics Overview -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-gradient-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1">Total Team Size</h6>
                            <h3 class="mb-0">{{ $stats['total_team'] ?? 0 }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-gradient-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1">Direct Referrals</h6>
                            <h3 class="mb-0">{{ $stats['direct_referrals'] ?? 0 }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-user-plus fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-gradient-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1">Team Investments</h6>
                            <h3 class="mb-0">${{ number_format(array_sum($stats['team_investments'] ?? []), 2) }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-chart-line fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-gradient-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1">Your Commissions</h6>
                            <h3 class="mb-0">${{ number_format($stats['total_commissions'] ?? 0, 2) }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-hand-holding-usd fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Levels Performance -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-layer-group text-primary"></i>
                        Team Performance by Levels
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @for($level = 1; $level <= 5; $level++)
                        @php
                            $levelMembers = $teamLevels[$level] ?? collect();
                            $levelInvestments = $stats['team_investments'][$level] ?? 0;
                            $commissionRate = [1 => 10, 2 => 4, 3 => 3, 4 => 2, 5 => 2][$level];
                        @endphp
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="card border-{{ ['', 'success', 'info', 'warning', 'danger', 'dark'][$level] }} h-100">
                                <div class="card-header bg-{{ ['', 'success', 'info', 'warning', 'danger', 'dark'][$level] }} text-white text-center">
                                    <h6 class="mb-0">Level {{ $level }}</h6>
                                    <small>{{ $commissionRate }}% Commission</small>
                                </div>
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                                                                <i class="fas fa-users fa-2x text-{{ ['', 'success', 'info', 'warning', 'danger', 'dark'][$level] }}"></i>
                                    </div>
                                    <div class="mb-3">
                                        <h4 class="text-{{ ['', 'success', 'info', 'warning', 'danger', 'dark'][$level] }}">{{ $levelMembers->count() }}</h4>
                                    <p class="text-muted mb-2">Members</p>

                                    <hr>

                                    <h5 class="text-primary">${{ number_format($levelInvestments, 2) }}</h5>
                                    <p class="text-muted mb-2">Total Investments</p>

                                    <hr>

                                    <h6 class="text-success">${{ number_format($levelInvestments * ($commissionRate / 100), 2) }}</h6>
                                    <p class="text-muted mb-0">Your Commission</p>
                                </div>
                                @if($levelMembers->count() > 0)
                                <div class="card-footer bg-light">
                                    <button class="btn btn-sm btn-outline-{{ ['', 'success', 'info', 'warning', 'danger'][$level] }} w-100" onclick="showLevelMembers({{ $level }})">
                                        <i class="fas fa-eye"></i> View Members
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Members by Level -->
    @for($level = 1; $level <= 5; $level++)
    @php
        $levelMembers = $teamLevels[$level] ?? collect();
    @endphp
    @if($levelMembers->count() > 0)
    <div class="row mb-4" id="level-{{ $level }}-members" style="display: none;">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-{{ ['', 'success', 'info', 'warning', 'danger'][$level] }} text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-users"></i>
                        Level {{ $level }} Team Members
                    </h5>
                    <button class="btn btn-sm btn-outline-light" onclick="hideLevelMembers({{ $level }})">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">Member</th>
                                    <th class="border-0">Rank</th>
                                    <th class="border-0">Join Date</th>
                                    <th class="border-0">Investments</th>
                                    <th class="border-0">Team Size</th>
                                    <th class="border-0">Your Commission</th>
                                    <th class="border-0">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($levelMembers as $member)
                                @php
                                    $memberInvestments = $member->investments->sum('amount');
                                    $commissionRate = [1 => 10, 2 => 4, 3 => 3, 4 => 2, 5 => 2][$level];
                                    $commission = $memberInvestments * ($commissionRate / 100);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $member->name }}</h6>
                                                <small class="text-muted">{{ $member->email }}</small><br>
                                                <small class="text-primary">{{ $member->referral_code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $member->getRankInfo()['color'] }}">
                                            <i class="fas fa-{{ $member->getRankInfo()['icon'] }} me-1"></i>
                                            {{ $member->rank ?? 'Guest' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            {{ $member->created_at->format('M d, Y') }}
                                            <br><small class="text-muted">{{ $member->created_at->diffForHumans() }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="mb-1 {{ $memberInvestments > 0 ? 'text-success' : 'text-muted' }}">
                                            ${{ number_format($memberInvestments, 2) }}
                                        </h6>
                                        <small class="text-muted">{{ $member->investments->count() }} investments</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $member->referrals->count() }} direct
                                        </span>
                                    </td>
                                    <td>
                                        <h6 class="mb-1 text-warning">
                                            ${{ number_format($commission, 2) }}
                                        </h6>
                                        <small class="text-muted">{{ $commissionRate }}%</small>
                                    </td>
                                    <td>
                                        @if($member->investments->count() > 0)
                                            <span class="badge bg-success">
                                                <i class="fas fa-chart-line"></i> Active
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock"></i> Inactive
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endfor

    <!-- Team Growth Tips -->
    <div class="row">
        <div class="col-12">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-rocket"></i>
                        Team Growth Strategies
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6><i class="fas fa-target text-success"></i> Focus on Level 1</h6>
                            <p class="small text-muted">Direct referrals give you the highest commission (10%). Focus on recruiting quality members who will invest.</p>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="fas fa-graduation-cap text-info"></i> Train Your Team</h6>
                            <p class="small text-muted">Help your direct referrals build their own teams. Their success equals more commissions for you.</p>
                        </div>
                        <div class="col-md-4">
                            <h6><i class="fas fa-chart-bar text-warning"></i> Monitor Performance</h6>
                            <p class="small text-muted">Regularly check your team's performance and provide support to inactive members.</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-light border-info mb-0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <strong>Need help growing your team?</strong>
                                        <br><small class="text-muted">Share your referral link and start earning commissions today!</small>
                                    </div>
                                    <a href="{{ route('mlm.referral-link') }}" class="btn btn-info">
                                        <i class="fas fa-share-alt"></i> Get Link
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #007bff, #0056b3);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
}

.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,0.05);
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.9rem;
    }

    .avatar-circle {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }

    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }

    .card-body .row .col-md-4 {
        margin-bottom: 1rem;
    }
}
</style>

<script>
function showLevelMembers(level) {
    // Hide all other level member sections
    for (let i = 1; i <= 4; i++) {
        if (i !== level) {
            const element = document.getElementById(`level-${i}-members`);
            if (element) {
                element.style.display = 'none';
            }
        }
    }

    // Show the selected level
    const element = document.getElementById(`level-${level}-members`);
    if (element) {
        element.style.display = 'block';
        element.scrollIntoView({ behavior: 'smooth' });
    }
}

function hideLevelMembers(level) {
    const element = document.getElementById(`level-${level}-members`);
    if (element) {
        element.style.display = 'none';
    }
}
</script>
@endsection
