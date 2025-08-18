@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-hand-holding-usd text-primary"></i>
                        Commission History
                    </h2>
                    <p class="text-muted mb-0">Track all your MLM commission earnings</p>
                </div>
                <a href="{{ route('mlm.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Back to MLM Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Commission Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-gradient-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1">Total Commissions</h6>
                            <h3 class="mb-0">${{ number_format($commissions->sum('amount'), 2) }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-gradient-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1">This Month</h6>
                            <h3 class="mb-0">
                                ${{ number_format($commissions->where('created_at', '>=', now()->startOfMonth())->sum('amount'), 2) }}
                            </h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-calendar-month fa-2x opacity-75"></i>
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
                            <h6 class="card-title mb-1">Direct Commissions</h6>
                            <h3 class="mb-0">
                                ${{ number_format($commissions->where('type', 'direct')->sum('amount'), 2) }}
                            </h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-user-check fa-2x opacity-75"></i>
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
                            <h6 class="card-title mb-1">Indirect Commissions</h6>
                            <h3 class="mb-0">
                                ${{ number_format($commissions->where('type', 'indirect')->sum('amount'), 2) }}
                            </h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Commission History Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-history text-primary"></i>
                        Commission History
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">All Commissions</a></li>
                                <li><a class="dropdown-item" href="#">Direct Only</a></li>
                                <li><a class="dropdown-item" href="#">Indirect Only</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">This Month</a></li>
                                <li><a class="dropdown-item" href="#">Last Month</a></li>
                            </ul>
                        </div>
                        <span class="badge bg-primary">{{ $commissions->total() }} records</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($commissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">From Member</th>
                                        <th class="border-0">Investment</th>
                                        <th class="border-0">Commission Type</th>
                                        <th class="border-0">Level</th>
                                        <th class="border-0">Percentage</th>
                                        <th class="border-0">Amount</th>
                                        <th class="border-0">Status</th>
                                        <th class="border-0">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($commissions as $commission)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">{{ $commission->fromUser->name }}</h6>
                                                    <small class="text-muted">{{ $commission->fromUser->email }}</small><br>
                                                    <small class="text-primary">{{ $commission->fromUser->referral_code }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($commission->investment)
                                                <div>
                                                    <h6 class="mb-1">${{ number_format($commission->investment->amount, 2) }}</h6>
                                                    <small class="text-muted">
                                                        {{ $commission->investment->investmentPackage->name ?? 'Investment Package' }}
                                                    </small>
                                                </div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $commission->type == 'direct' ? 'bg-success' : 'bg-info' }}">
                                                <i class="fas fa-{{ $commission->type == 'direct' ? 'user-check' : 'users' }}"></i>
                                                {{ ucfirst($commission->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                Level {{ $commission->level }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-primary font-weight-bold">
                                                {{ $commission->percentage }}%
                                            </span>
                                        </td>
                                        <td>
                                            <h6 class="mb-0 text-success">
                                                ${{ number_format($commission->amount, 2) }}
                                            </h6>
                                        </td>
                                        <td>
                                            <span class="badge {{ $commission->status == 'paid' ? 'bg-success' : ($commission->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
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

                        <!-- Pagination -->
                        <div class="card-footer bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    Showing {{ $commissions->firstItem() ?? 0 }} to {{ $commissions->lastItem() ?? 0 }} of {{ $commissions->total() }} results
                                </div>
                                {{ $commissions->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-hand-holding-usd fa-4x text-muted"></i>
                            </div>
                            <h4 class="text-muted mb-3">No Commissions Yet</h4>
                            <p class="text-muted mb-4">Start earning commissions by building your team and encouraging investments</p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('mlm.referral-link') }}" class="btn btn-primary">
                                    <i class="fas fa-share-alt"></i>
                                    Get Referral Link
                                </a>
                                <a href="{{ route('mlm.team') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-users"></i>
                                    View Team
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Commission Structure Info -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle"></i>
                        Commission Structure
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Direct Commissions (Level 1):</h6>
                            <ul class="small">
                                <li><strong>10%</strong> from direct referral investments</li>
                                <li>Paid immediately when investment is made</li>
                                <li>No minimum investment required</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Indirect Commissions (Levels 2-4):</h6>
                            <ul class="small">
                                <li><strong>Level 2:</strong> 4% commission</li>
                                <li><strong>Level 3:</strong> 3% commission</li>
                                <li><strong>Level 4:</strong> 2% commission</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-lightbulb"></i>
                                <strong>Tip:</strong> The more your team invests, the higher your commission earnings. Focus on helping your referrals succeed!
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

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
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

.font-weight-bold {
    font-weight: 600 !important;
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

    .btn-group {
        flex-direction: column;
    }
}

.badge {
    font-size: 0.75rem;
    padding: 0.375em 0.75em;
}
</style>
@endsection
