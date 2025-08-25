@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2 fw-bold">
                        <i class="fas fa-history text-primary"></i>
                        Investment History
                    </h1>
                    <p class="text-muted mb-0">Track all your investment activities and performance</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('investments.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        <span class="d-none d-sm-inline">New Investment</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i>
                        <span class="d-none d-sm-inline">Dashboard</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Investment Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1 fw-semibold">Total Investments</h6>
                            <h3 class="mb-0 fw-bold">{{ $investments->total() }}</h3>
                            <small class="text-primary">
                                <i class="fas fa-chart-bar"></i>
                                All time
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-chart-line fa-lg text-primary"></i>
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
                            <h6 class="text-muted mb-1 fw-semibold">Total Invested</h6>
                            <h3 class="mb-0 fw-bold">
                                ${{ number_format($investments->sum('amount'), 2) }}
                            </h3>
                            <small class="text-success">
                                <i class="fas fa-dollar-sign"></i>
                                Capital deployed
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-money-bill-wave fa-lg text-success"></i>
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
                            <h3 class="mb-0 fw-bold">
                                {{ $investments->where('status', 'active')->count() }}
                            </h3>
                            <small class="text-info">
                                <i class="fas fa-clock"></i>
                                Currently earning
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-play-circle fa-lg text-info"></i>
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
                            <h6 class="text-muted mb-1 fw-semibold">Completed</h6>
                            <h3 class="mb-0 fw-bold">
                                {{ $investments->where('status', 'completed')->count() }}
                            </h3>
                            <small class="text-warning">
                                <i class="fas fa-check-circle"></i>
                                Finished earning
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-flag-checkered fa-lg text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Investment History Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-list text-primary"></i>
                            Investment History
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">All Investments</a></li>
                                    <li><a class="dropdown-item" href="#">Active Only</a></li>
                                    <li><a class="dropdown-item" href="#">Completed Only</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" href="#">Last Month</a></li>
                                </ul>
                            </div>
                            <span class="badge bg-primary">{{ $investments->total() }} records</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($investments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">Package</th>
                                        <th class="border-0 fw-semibold">Amount</th>
                                        <th class="border-0 fw-semibold">Daily Return</th>
                                        <th class="border-0 fw-semibold">Progress</th>
                                        <th class="border-0 fw-semibold">Total Returned</th>
                                        <th class="border-0 fw-semibold">Status</th>
                                        <th class="border-0 fw-semibold">Start Date</th>
                                        <th class="border-0 fw-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($investments as $investment)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                                    <i class="fas fa-box text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-semibold">{{ $investment->investmentPackage->name }}</h6>
                                                    <small class="text-muted">{{ $investment->investmentPackage->description }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="mb-1 fw-bold text-success">${{ number_format($investment->amount, 2) }}</h6>
                                            <small class="text-muted">Invested</small>
                                        </td>
                                        <td>
                                            <h6 class="mb-1 fw-semibold text-info">${{ number_format($investment->daily_return, 2) }}</h6>
                                            <small class="text-muted">{{ $investment->investmentPackage->daily_return_percentage }}% daily</small>
                                        </td>
                                        <td>
                                            @php
                                                $progress = ($investment->days_completed / $investment->investmentPackage->return_days) * 100;
                                            @endphp
                                            <div class="mb-1">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-{{ $investment->status == 'completed' ? 'success' : 'primary' }}"
                                                         style="width: {{ $progress }}%"></div>
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                {{ $investment->days_completed }}/{{ $investment->investmentPackage->return_days }} days
                                            </small>
                                        </td>
                                        <td>
                                            <h6 class="mb-1 fw-bold text-primary">${{ number_format($investment->total_returned, 2) }}</h6>
                                            <small class="text-muted">
                                                Profit: ${{ number_format($investment->total_returned - $investment->amount, 2) }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $investment->status == 'active' ? 'success' : ($investment->status == 'completed' ? 'primary' : 'secondary') }}">
                                                <i class="fas fa-{{ $investment->status == 'active' ? 'play' : ($investment->status == 'completed' ? 'check' : 'pause') }}"></i>
                                                {{ ucfirst($investment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>
                                                {{ $investment->start_date->format('M d, Y') }}
                                                <br><small class="text-muted">{{ $investment->start_date->diffForHumans() }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('investments.details', $investment) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="showInvestmentSummary({{ $investment->id }})">
                                                    <i class="fas fa-chart-pie"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer bg-light border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    Showing {{ $investments->firstItem() ?? 0 }} to {{ $investments->lastItem() ?? 0 }} of {{ $investments->total() }} results
                                </div>
                                {{ $investments->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-chart-line fa-4x text-muted"></i>
                            </div>
                            <h4 class="text-muted mb-3">No Investment History</h4>
                            <p class="text-muted mb-4">You haven't made any investments yet. Start investing to see your history here.</p>
                            <a href="{{ route('investments.index') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus"></i>
                                Start Your First Investment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Investment Tips -->
    @if($investments->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-lightbulb"></i>
                        Investment Tips
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="fw-semibold">Diversify Your Portfolio:</h6>
                            <p class="small text-muted">Consider investing in different packages to spread risk and maximize returns.</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-semibold">Reinvest Returns:</h6>
                            <p class="small text-muted">Use your daily returns to make new investments and compound your earnings.</p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-semibold">Monitor Performance:</h6>
                            <p class="small text-muted">Regularly check your investment progress and plan your next moves accordingly.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.progress {
    border-radius: 0.5rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,0.05);
}

@media (max-width: 767.98px) {
    .table-responsive {
        font-size: 0.875rem;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}
</style>

<script>
function showInvestmentSummary(investmentId) {
    // Implement investment summary modal
    alert('Investment summary feature coming soon!');
}
</script>
@endsection
