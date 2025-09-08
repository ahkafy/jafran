@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2 fw-bold text-dark">
                        <i class="fas fa-history text-info"></i>
                        Transaction History
                    </h1>
                    <p class="text-muted mb-0">Complete history of all your wallet transactions</p>
                </div>
                <a href="{{ route('wallet') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span class="d-none d-sm-inline">Back to Wallet</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Transaction Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1 fw-semibold">Total Credits</h6>
                            <h4 class="mb-0 fw-bold text-success">
                                ${{ number_format($transactions->where('type', 'credit')->sum('amount'), 2) }}
                            </h4>
                            <small class="text-success">
                                {{ $transactions->where('type', 'credit')->count() }} transactions
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-arrow-up fa-lg text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1 fw-semibold">Total Debits</h6>
                            <h4 class="mb-0 fw-bold text-danger">
                                ${{ number_format($transactions->where('type', 'debit')->sum('amount'), 2) }}
                            </h4>
                            <small class="text-danger">
                                {{ $transactions->where('type', 'debit')->count() }} transactions
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="bg-danger bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-arrow-down fa-lg text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1 fw-semibold">Pending</h6>
                            <h4 class="mb-0 fw-bold text-warning">
                                {{ $transactions->where('status', 'pending')->count() }}
                            </h4>
                            <small class="text-warning">
                                ${{ number_format($transactions->where('status', 'pending')->sum('amount'), 2) }}
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-clock fa-lg text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1 fw-semibold">Net Balance</h6>
                            <h4 class="mb-0 fw-bold text-primary">
                                ${{ number_format($transactions->where('type', 'credit')->sum('amount') - $transactions->where('type', 'debit')->sum('amount'), 2) }}
                            </h4>
                            <small class="text-primary">
                                Credits - Debits
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-balance-scale fa-lg text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-list text-info"></i>
                            All Transactions
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('wallet.transactions') }}">All Transactions</a></li>
                                    <li><a class="dropdown-item" href="{{ route('wallet.transactions', ['type' => 'credit']) }}">Credits Only</a></li>
                                    <li><a class="dropdown-item" href="{{ route('wallet.transactions', ['type' => 'debit']) }}">Debits Only</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('wallet.transactions', ['status' => 'pending']) }}">Pending</a></li>
                                    <li><a class="dropdown-item" href="{{ route('wallet.transactions', ['status' => 'completed']) }}">Completed</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('wallet.transactions', ['category' => 'deposit']) }}">Deposits</a></li>
                                    <li><a class="dropdown-item" href="{{ route('wallet.transactions', ['category' => 'withdrawal']) }}">Withdrawals</a></li>
                                    <li><a class="dropdown-item" href="{{ route('wallet.transactions', ['category' => 'gift_card']) }}">Gift Cards</a></li>
                                </ul>
                            </div>
                            <span class="badge bg-info">{{ $transactions->total() }} records</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-semibold">Date & Time</th>
                                        <th class="border-0 fw-semibold">Type</th>
                                        <th class="border-0 fw-semibold">Category</th>
                                        <th class="border-0 fw-semibold">Description</th>
                                        <th class="border-0 fw-semibold">Amount</th>
                                        <th class="border-0 fw-semibold">Payment Method</th>
                                        <th class="border-0 fw-semibold">Status</th>
                                        <th class="border-0 fw-semibold">Reference</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $transaction->created_at->format('M d, Y') }}</strong>
                                                <br><small class="text-muted">{{ $transaction->created_at->format('H:i:s') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="{{ $transaction->type_icon }} me-2"></i>
                                                <span class="badge bg-{{ $transaction->type === 'credit' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($transaction->type) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="{{ $transaction->category_icon }} text-muted me-2"></i>
                                                <span class="small">{{ ucfirst(str_replace('_', ' ', $transaction->category)) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                {{ $transaction->description }}
                                                @if($transaction->processed_at)
                                                <br><small class="text-muted">Processed: {{ $transaction->processed_at->format('M d, Y H:i') }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <strong class="text-{{ $transaction->type === 'credit' ? 'success' : 'danger' }}">
                                                {{ $transaction->type === 'credit' ? '+' : '-' }}${{ $transaction->formatted_amount }}
                                            </strong>
                                        </td>
                                        <td>
                                            @if($transaction->payment_method)
                                                <span class="badge bg-secondary">
                                                    {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : ($transaction->status === 'failed' ? 'danger' : 'secondary')) }}">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($transaction->payment_reference)
                                                <code class="small">{{ Str::limit($transaction->payment_reference, 15) }}</code>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
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
                                    Showing {{ $transactions->firstItem() ?? 0 }} to {{ $transactions->lastItem() ?? 0 }} of {{ $transactions->total() }} transactions
                                </div>
                                {{ $transactions->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-history fa-4x text-muted"></i>
                            </div>
                            <h4 class="text-muted mb-3">No Transactions Found</h4>
                            <p class="text-muted mb-4">You haven't made any transactions yet. Start by adding funds or making an investment.</p>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('wallet.add-funds') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i>
                                    Add Funds
                                </a>
                                <a href="{{ route('investments.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-chart-line"></i>
                                    Start Investing
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    @if($transactions->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-download"></i>
                        Export Options
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-3">Download your transaction history for record keeping:</p>
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-outline-primary btn-sm" onclick="exportTransactions('pdf')">
                            <i class="fas fa-file-pdf"></i> Export as PDF
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="exportTransactions('excel')">
                            <i class="fas fa-file-excel"></i> Export as Excel
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="exportTransactions('csv')">
                            <i class="fas fa-file-csv"></i> Export as CSV
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function exportTransactions(format) {
    // This would typically make an AJAX request to an export endpoint
    alert(`Export to ${format.toUpperCase()} functionality will be implemented soon!`);
}

// Auto-refresh for pending transactions
@if($transactions->where('status', 'pending')->count() > 0)
    setTimeout(function() {
        var badges = Array.from(document.querySelectorAll('.badge'));
        var hasPending = badges.some(function(el) {
            return el.textContent && el.textContent.trim().toLowerCase().indexOf('pending') !== -1;
        });
        if (hasPending) {
            location.reload();
        }
    }, 30000); // Refresh every 30 seconds if there are pending transactions
@endif
</script>

<style>
.table-hover tbody tr:hover {
    background-color: rgba(23, 162, 184, 0.05);
}

code {
    background-color: #f8f9fa;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 0.875em;
}

@media (max-width: 767.98px) {
    .table-responsive {
        font-size: 0.875rem;
    }

    .d-flex.gap-2.flex-wrap .btn {
        margin-bottom: 0.5rem;
    }
}
</style>
@endsection
