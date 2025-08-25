@extends(                    <h1 class="mb-2 fw-bold">
                        <i class="fas fa-minus-circle text-primary"></i>
                        Withdraw Funds
                    </h1>outs.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2 fw-bold text-dark">
                        <i class="fas fa-minus-circle text-warning"></i>
                        Withdraw Funds
                    </h1>
                    <p class="text-muted mb-0">Request withdrawal to your US bank account via EFT</p>
                </div>
                <a href="{{ route('wallet') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span class="d-none d-sm-inline">Back to Wallet</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Withdrawal Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-university"></i>
                        EFT Withdrawal Request
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('wallet.submit-withdrawal') }}" method="POST">
                        @csrf

                        <!-- Amount Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Withdrawal Amount</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Amount (USD)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" class="form-control" name="amount"
                                                           min="2" max="10000" step="0.01" required>
                                                </div>
                                                <small class="text-muted">Min: $2, Max: $10,000</small>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="alert alert-info mb-0">
                                                    <strong>Available Balance:</strong><br>
                                                    Wallet: ${{ number_format(auth()->user()->wallet_balance, 2) }}<br>
                                                    Commission: ${{ number_format(auth()->user()->commission_balance, 2) }}<br>
                                                    <strong>Total: ${{ number_format(auth()->user()->wallet_balance + auth()->user()->commission_balance, 2) }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Information -->
                        <h6 class="mb-3">Bank Account Information (US Standard)</h6>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="bank_name"
                                       placeholder="e.g. Chase Bank, Bank of America" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="account_holder_name"
                                       placeholder="Full name as on bank account" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="account_number"
                                       placeholder="Bank account number" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Routing Number (ABA) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="routing_number"
                                       placeholder="9-digit routing number" pattern="[0-9]{9}" maxlength="9" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">SWIFT Code (International)</label>
                                <input type="text" class="form-control" name="swift_code"
                                       placeholder="For international transfers (optional)">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Account Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="account_type" required>
                                    <option value="">Select account type</option>
                                    <option value="checking">Checking</option>
                                    <option value="savings">Savings</option>
                                </select>
                            </div>
                        </div>

                        <!-- Bank Address -->
                        <h6 class="mb-3">Bank Address</h6>

                        <div class="mb-3">
                            <label class="form-label">Bank Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="bank_address"
                                   placeholder="Bank street address" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="bank_city"
                                       placeholder="Bank city" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">State <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="bank_state"
                                       placeholder="State (e.g. CA, NY)" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">ZIP Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="bank_zip_code"
                                       placeholder="ZIP Code" required>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label class="form-label">Additional Notes (Optional)</label>
                            <textarea class="form-control" name="notes" rows="3"
                                    placeholder="Any additional information or special instructions..."></textarea>
                        </div>

                        <!-- Terms and Submit -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                <label class="form-check-label" for="agreeTerms">
                                    I confirm that the bank account information provided is accurate and belongs to me.
                                    I understand that withdrawal processing may take 3-5 business days.
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning btn-lg">
                            <i class="fas fa-paper-plane"></i> Submit Withdrawal Request
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Information Sidebar -->
        <div class="col-lg-4">
            <!-- Processing Information -->
            <div class="card border-info mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle"></i>
                        Processing Information
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-clock text-primary"></i>
                            <strong>Processing Time:</strong> 3-5 business days
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-dollar-sign text-success"></i>
                            <strong>Minimum:</strong> $50
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-shield-alt text-info"></i>
                            <strong>Method:</strong> EFT (Electronic Funds Transfer)
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-university text-warning"></i>
                            <strong>Supported:</strong> US Bank Accounts Only
                        </li>
                        <li>
                            <i class="fas fa-check-circle text-success"></i>
                            <strong>Verification:</strong> Manual review for security
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        Security Notice
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2">• Bank account must be in your name</li>
                        <li class="mb-2">• All information will be verified</li>
                        <li class="mb-2">• Incorrect details may delay processing</li>
                        <li class="mb-2">• Funds are sent via secure EFT</li>
                        <li>• Contact support if you need assistance</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawal Requests History -->
    @if($withdrawalRequests->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-history text-primary"></i>
                        Your Withdrawal Requests
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">Request ID</th>
                                    <th class="border-0">Amount</th>
                                    <th class="border-0">Bank Account</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Submitted</th>
                                    <th class="border-0">Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($withdrawalRequests as $request)
                                <tr>
                                    <td>
                                        <strong>#{{ $request->id }}</strong>
                                    </td>
                                    <td>
                                        <strong class="text-warning">${{ $request->formatted_amount }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            {{ $request->bank_name }}
                                            <br><small class="text-muted">{{ $request->masked_account_number }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="{{ $request->status_badge }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            {{ $request->created_at->format('M d, Y') }}
                                            <br><small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($request->admin_notes)
                                            <small class="text-muted">{{ Str::limit($request->admin_notes, 50) }}</small>
                                        @else
                                            <small class="text-muted">No notes</small>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-light">
                        {{ $withdrawalRequests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
// Format routing number input
document.querySelector('input[name="routing_number"]').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 9) {
        value = value.substring(0, 9);
    }
    e.target.value = value;
});

// Format account number input (numbers only)
document.querySelector('input[name="account_number"]').addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/\D/g, '');
});

// Auto-calculate available withdrawal amount
document.querySelector('input[name="amount"]').addEventListener('change', function(e) {
    const walletBalance = {{ auth()->user()->wallet_balance }};
    const commissionBalance = {{ auth()->user()->commission_balance }};
    const totalBalance = walletBalance + commissionBalance;
    const requestedAmount = parseFloat(e.target.value);

    if (requestedAmount > totalBalance) {
        alert('Requested amount exceeds your available balance of $' + totalBalance.toFixed(2));
        e.target.value = totalBalance.toFixed(2);
    }
});
</script>

<style>
.table-hover tbody tr:hover {
    background-color: rgba(255, 193, 7, 0.05);
}

.form-control:focus,
.form-select:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
}

@media (max-width: 767.98px) {
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
@endsection
