@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-users text-primary"></i>
                        Direct Referrals
                    </h2>
                    <p class="text-muted mb-0">Manage your direct team members and track their performance</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('mlm.referral-link') }}" class="btn btn-success">
                        <i class="fas fa-share-alt"></i> Get Referral Link
                    </a>
                    <a href="{{ route('mlm.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-gradient-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1">Total Referrals</h6>
                            <h3 class="mb-0">{{ $directReferrals->count() }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-user-plus fa-2x opacity-75"></i>
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
                            <h6 class="card-title mb-1">Active Investors</h6>
                            <h3 class="mb-0">
                                {{ $directReferrals->filter(function($referral) {
                                    return $referral->investments->count() > 0;
                                })->count() }}
                            </h3>
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
                            <h6 class="card-title mb-1">Total Investments</h6>
                            <h3 class="mb-0">
                                ${{ number_format($directReferrals->sum(function($referral) {
                                    return $referral->investments->sum('amount');
                                }), 2) }}
                            </h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
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
                            <h6 class="card-title mb-1">Your Commissions</h6>
                            <h3 class="mb-0">
                                ${{ number_format($directReferrals->sum(function($referral) {
                                    return $referral->investments->sum('amount') * 0.10;
                                }), 2) }}
                            </h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-hand-holding-usd fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Referrals List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-users text-primary"></i>
                        Your Direct Referrals
                    </h5>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary">{{ $directReferrals->count() }} members</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($directReferrals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Member</th>
                                        <th class="border-0">Join Date</th>
                                        <th class="border-0">Total Investments</th>
                                        <th class="border-0">Investment Count</th>
                                        <th class="border-0">Your Commission</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($directReferrals as $referral)
                                    @php
                                        $totalInvestment = $referral->investments->sum('amount');
                                        $investmentCount = $referral->investments->count();
                                        $commission = $totalInvestment * 0.10;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $referral->name }}</h6>
                                                    <small class="text-muted">{{ $referral->email }}</small><br>
                                                    <small class="text-primary">Code: {{ $referral->referral_code }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                {{ $referral->created_at->format('M d, Y') }}
                                                <br><small class="text-muted">{{ $referral->created_at->diffForHumans() }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="mb-1 {{ $totalInvestment > 0 ? 'text-success' : 'text-muted' }}">
                                                ${{ number_format($totalInvestment, 2) }}
                                            </h6>
                                            <small class="text-muted">
                                                @if($totalInvestment > 0)
                                                    Active Investor
                                                @else
                                                    No Investments
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge {{ $investmentCount > 0 ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $investmentCount }} investment{{ $investmentCount != 1 ? 's' : '' }}
                                            </span>
                                        </td>
                                        <td>
                                            <h6 class="mb-1 text-warning">
                                                ${{ number_format($commission, 2) }}
                                            </h6>
                                            <small class="text-muted">10% commission</small>
                                        </td>
                                        <td>
                                            @if($referral->investments->count() > 0)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-chart-line"></i> Active
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock"></i> Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="viewMemberDetails({{ $referral->id }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-success btn-sm" onclick="sendMessage({{ $referral->id }})">
                                                    <i class="fas fa-envelope"></i>
                                                </button>
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
                                <i class="fas fa-user-plus fa-4x text-muted"></i>
                            </div>
                            <h4 class="text-muted mb-3">No Direct Referrals Yet</h4>
                            <p class="text-muted mb-4">Start building your team by sharing your referral link with others</p>
                            <a href="{{ route('mlm.referral-link') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-share-alt"></i>
                                Get Your Referral Link
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($directReferrals->count() > 0)
    <!-- Tips Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb"></i>
                        Tips to Grow Your Team
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Encourage Investments:</h6>
                            <ul class="small">
                                <li>Share investment benefits with your referrals</li>
                                <li>Help them understand the daily return system</li>
                                <li>Show them successful case studies</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Support Your Team:</h6>
                            <ul class="small">
                                <li>Stay in touch with your referrals regularly</li>
                                <li>Help them grow their own teams</li>
                                <li>Share MLM strategies and tips</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Member Details Modal -->
<div class="modal fade" id="memberDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Member Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="memberDetailsContent">
                <!-- Content will be loaded dynamically -->
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

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.9rem;
    }

    .avatar-circle {
        width: 35px;
        height: 35px;
        font-size: 14px;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}
</style>

<script>
function viewMemberDetails(memberId) {
    // Implement member details view
    alert('Member details feature coming soon!');
}

function sendMessage(memberId) {
    // Implement messaging feature
    alert('Messaging feature coming soon!');
}
</script>
@endsection
