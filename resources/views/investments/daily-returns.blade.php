@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                                        <h1 class="mb-2 fw-bold">
                        <i class="fas fa-calendar-day text-primary"></i>
                        Daily Returns
                    </h1>
                    <p class="text-muted mb-0">Track your daily earnings and investment performance</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('investments.history') }}" class="btn btn-primary">
                        <i class="fas fa-history"></i>
                        <span class="d-none d-sm-inline">Investment History</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i>
                        <span class="d-none d-sm-inline">Dashboard</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Returns Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1 fw-semibold">Today's Returns</h6>
                            <h3 class="mb-0 fw-bold">
                                ${{ number_format($dailyReturns->where('return_date', today())->sum('amount'), 2) }}
                            </h3>
                            <small class="text-success">
                                <i class="fas fa-calendar-day"></i>
                                {{ now()->format('M d, Y') }}
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-calendar-check fa-lg text-success"></i>
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
                            <h6 class="text-muted mb-1 fw-semibold">This Week</h6>
                            <h3 class="mb-0 fw-bold">
                                ${{ number_format($dailyReturns->where('return_date', '>=', now()->startOfWeek())->sum('amount'), 2) }}
                            </h3>
                            <small class="text-info">
                                <i class="fas fa-calendar-week"></i>
                                7 days total
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-chart-bar fa-lg text-info"></i>
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
                            <h6 class="text-muted mb-1 fw-semibold">This Month</h6>
                            <h3 class="mb-0 fw-bold text-dark">
                                ${{ number_format($dailyReturns->where('return_date', '>=', now()->startOfMonth())->sum('amount'), 2) }}
                            </h3>
                            <small class="text-primary">
                                <i class="fas fa-calendar-alt"></i>
                                {{ now()->format('F') }} earnings
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
                            <h6 class="text-muted mb-1 fw-semibold">Total Returns</h6>
                            <h3 class="mb-0 fw-bold text-dark">
                                ${{ number_format($dailyReturns->sum('amount'), 2) }}
                            </h3>
                            <small class="text-warning">
                                <i class="fas fa-coins"></i>
                                All time earnings
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-trophy fa-lg text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Chart Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-semibold text-dark">
                            <i class="fas fa-chart-area text-primary"></i>
                            Returns Trend
                        </h6>
                        <div class="d-flex align-items-center gap-2">
                            <select class="form-select form-select-sm" style="width: auto;">
                                <option>Last 7 days</option>
                                <option>Last 30 days</option>
                                <option>Last 3 months</option>
                                <option>All time</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chart visualization will be available soon</p>
                        <small class="text-muted">Track your daily returns growth over time</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Returns Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold text-dark">
                            <i class="fas fa-list text-primary"></i>
                            Daily Returns History
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">All Returns</a></li>
                                    <li><a class="dropdown-item" href="#">Today</a></li>
                                    <li><a class="dropdown-item" href="#">This Week</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">By Investment</a></li>
                                </ul>
                            </div>
                            <span class="badge bg-primary">{{ $dailyReturns->total() }} records</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($dailyReturns->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">Date</th>
                                        <th class="border-0 fw-semibold">Investment</th>
                                        <th class="border-0 fw-semibold">Package</th>
                                        <th class="border-0 fw-semibold">Return Amount</th>
                                        <th class="border-0 fw-semibold">Return %</th>
                                        <th class="border-0 fw-semibold">Investment Day</th>
                                        <th class="border-0 fw-semibold">Status</th>
                                        <th class="border-0 fw-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dailyReturns as $return)
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-1 fw-semibold">{{ $return->return_date->format('M d, Y') }}</h6>
                                                <small class="text-muted">{{ $return->return_date->diffForHumans() }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 p-2 rounded me-2">
                                                    <i class="fas fa-wallet text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1 fw-semibold">${{ number_format($return->investment->amount, 2) }}</h6>
                                                    <small class="text-muted">Investment #{{ $return->investment->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-1 fw-semibold">{{ $return->investment->investmentPackage->name }}</h6>
                                                <small class="text-muted">{{ $return->investment->investmentPackage->daily_return_percentage }}% daily</small>
                                            </div>
                                        </td>
                                        <td>
                                            <h5 class="mb-1 fw-bold text-success">${{ number_format($return->amount, 2) }}</h5>
                                            <small class="text-muted">Daily earning</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-20 text-info">
                                                {{ number_format(($return->amount / $return->investment->amount) * 100, 2) }}%
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <span class="badge bg-primary">
                                                    Day {{ $return->day_number }}
                                                </span>
                                                <br>
                                                <small class="text-muted">
                                                    of {{ $return->investment->investmentPackage->return_days }}
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $return->status == 'paid' ? 'success' : 'warning' }}">
                                                <i class="fas fa-{{ $return->status == 'paid' ? 'check' : 'clock' }}"></i>
                                                {{ ucfirst($return->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('investments.details', $return->investment) }}" class="btn btn-outline-primary btn-sm" title="View Investment">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($return->status == 'pending')
                                                <button type="button" class="btn btn-outline-success btn-sm" title="Mark as Paid" onclick="markAsPaid({{ $return->id }})">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Footer -->
                        <div class="card-footer bg-light border-0">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="text-muted">
                                        Showing {{ $dailyReturns->firstItem() ?? 0 }} to {{ $dailyReturns->lastItem() ?? 0 }} of {{ $dailyReturns->total() }} returns
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-md-end gap-3">
                                        <div class="text-center">
                                            <small class="text-muted d-block">This Page Total</small>
                                            <strong class="text-success">${{ number_format($dailyReturns->sum('amount'), 2) }}</strong>
                                        </div>
                                        <div class="text-center">
                                            <small class="text-muted d-block">Grand Total</small>
                                            <strong class="text-primary">${{ number_format($dailyReturns->sum('amount'), 2) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    {{ $dailyReturns->links() }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-coins fa-4x text-muted"></i>
                            </div>
                            <h4 class="text-muted mb-3">No Daily Returns Yet</h4>
                            <p class="text-muted mb-4">You haven't earned any daily returns yet. Make an investment to start earning daily returns.</p>
                            <a href="{{ route('investments.index') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus"></i>
                                Start Investing Now
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Returns Statistics -->
    @if($dailyReturns->count() > 0)
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-chart-pie"></i>
                        Returns Breakdown
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h4 class="text-success fw-bold">${{ number_format($dailyReturns->where('return_date', today())->sum('amount'), 2) }}</h4>
                            <small class="text-muted">Today's Returns</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-info fw-bold">{{ $dailyReturns->where('return_date', today())->count() }}</h4>
                            <small class="text-muted">Returns Today</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-warning fw-bold">{{ $dailyReturns->where('status', 'pending')->count() }}</h4>
                            <small class="text-muted">Pending Returns</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-primary fw-bold">{{ number_format(($dailyReturns->sum('amount') / $dailyReturns->sum('investment.amount')) * 100, 1) }}%</h4>
                            <small class="text-muted">ROI to Date</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-lightbulb"></i>
                        Earning Tips
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small>Returns are calculated daily based on your investment amount</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small>Reinvest your returns to compound your earnings</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small>Track your progress and plan future investments</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,0.05);
}

.badge {
    font-size: 0.75rem;
}

@media (max-width: 767.98px) {
    .table-responsive {
        font-size: 0.875rem;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .card-footer .row > div {
        margin-bottom: 1rem;
    }
}
</style>

<script>
function markAsPaid(returnId) {
    if (confirm('Mark this return as paid?')) {
        // Implement AJAX call to mark return as paid
        alert('Return marked as paid! (Feature to be implemented)');
    }
}
</script>
@endsection
