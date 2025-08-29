@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2 fw-bold">
                        <i class="fas fa-minus-circle text-primary"></i>
                        Withdraw Funds
                    </h1>
                    <p class="text-muted mb-0">Request withdrawal to your bank account or MBook wallet</p>
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
                        Withdrawal Request
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('wallet.submit-withdrawal') }}" method="POST" id="withdrawalForm">
                        @csrf

                        <!-- Withdrawal Method Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Withdrawal Method</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" name="method" id="method_bank" value="bank" checked>
                                                    <label class="form-check-label" for="method_bank">
                                                        <i class="fas fa-university text-primary"></i>
                                                        <strong>Bank Transfer</strong>
                                                        <br><small class="text-muted">Processing Fee: 2% (US Banks) / 10% (Other Banks)</small>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="radio" name="method" id="method_mbook" value="mbook">
                                                    <label class="form-check-label" for="method_mbook">
                                                        <i class="fas fa-mobile-alt text-success"></i>
                                                        <strong>MBook Wallet</strong>
                                                        <br><small class="text-muted">Processing Fee: 5%</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Amount Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Withdrawal Details</h6>

                                        <!-- Balance Type Selection -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Balance Type to Withdraw</label>
                                                <select class="form-select" name="balance_type" required>
                                                    <option value="both">Both Commission & Returns</option>
                                                    <option value="commission">Commission Only</option>
                                                    <option value="returns">Returns Only</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Amount (USD)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" class="form-control" name="amount" id="withdrawalAmount"
                                                           min="2" max="{{ auth()->user()->withdrawable_balance }}" step="0.01" required>
                                                </div>
                                                <small class="text-muted">Min: $2, Max: ${{ number_format(auth()->user()->withdrawable_balance, 2) }}</small>
                                            </div>
                                        </div>

                                        <!-- Fee Calculation Display -->
                                        <div class="row mb-3" id="feeCalculation" style="display: none;">
                                            <div class="col-12">
                                                <div class="alert alert-info">
                                                    <h6><i class="fas fa-calculator"></i> Fee Calculation</h6>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <strong>Gross Amount:</strong><br>
                                                            <span id="grossAmount">$0.00</span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <strong>Processing Fee:</strong><br>
                                                            <span id="processingFee">$0.00</span> (<span id="feeRate">0%</span>)
                                                        </div>
                                                        <div class="col-md-4">
                                                            <strong>Net Amount:</strong><br>
                                                            <span id="netAmount" class="text-success">$0.00</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="alert alert-info mb-0">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <strong>Available for Withdrawal:</strong><br>
                                                            Commission: ${{ number_format(auth()->user()->commission_balance, 2) }}<br>
                                                            Returns: ${{ number_format(auth()->user()->return_balance, 2) }}<br>
                                                            <strong class="text-success">Total Withdrawable: ${{ number_format(auth()->user()->withdrawable_balance, 2) }}</strong>
                                                            <hr class="my-2">
                                                            <strong class="text-primary">Minimum Withdrawal: $2.00 USD</strong>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Investment Balance:</strong><br>
                                                            <span class="text-warning">${{ number_format(auth()->user()->investment_balance, 2) }}</span><br>
                                                            <small class="text-muted">(Cannot be withdrawn - for investments only)</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Information -->
                        <div id="bankFields" class="withdrawal-method-fields">
                            <h6 class="mb-3">Bank Account Information</h6>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="bank_name"
                                           placeholder="e.g. Chase Bank, Bank of America">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Bank Country <span class="text-danger">*</span></label>
                                    <select class="form-select" name="bank_country" id="bankCountry">
                                        <option value="US">United States (2% fee)</option>
                                        <option value="CA">Canada (10% fee)</option>
                                        <option value="GB">United Kingdom (10% fee)</option>
                                        <option value="AU">Australia (10% fee)</option>
                                        <option value="other">Other Country (10% fee)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="account_holder_name"
                                           placeholder="Full name as on bank account">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Account Type <span class="text-danger">*</span></label>
                                    <select class="form-select" name="account_type">
                                        <option value="">Select account type</option>
                                        <option value="checking">Checking</option>
                                        <option value="savings">Savings</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="account_number"
                                           placeholder="Bank account number">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Routing Number (ABA) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="routing_number"
                                           placeholder="9-digit routing number" pattern="[0-9]{9}" maxlength="9">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">SWIFT Code (International)</label>
                                    <input type="text" class="form-control" name="swift_code"
                                           placeholder="For international transfers (optional)">
                                </div>
                                <div class="col-md-6">
                                    <!-- Empty column for spacing -->
                                </div>
                            </div>

                            <!-- Bank Address (Optional) -->
                            <h6 class="mb-3">Bank Address (Optional)</h6>

                            <div class="mb-3">
                                <label class="form-label">Bank Address</label>
                                <input type="text" class="form-control" name="bank_address"
                                       placeholder="Bank street address">
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="bank_city"
                                           placeholder="Bank city">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">State</label>
                                    <input type="text" class="form-control" name="bank_state"
                                           placeholder="State (e.g. CA, NY)">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">ZIP Code</label>
                                    <input type="text" class="form-control" name="bank_zip_code"
                                           placeholder="ZIP Code">
                                </div>
                            </div>
                        </div>

                        <!-- MBook Information -->
                        <div id="mbookFields" class="withdrawal-method-fields" style="display: none;">
                            <h6 class="mb-3">MBook Wallet Information</h6>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="mbook_name"
                                           placeholder="Full name on MBook account">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Country <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="mbook_country"
                                           placeholder="Your country">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">MBook Currency <span class="text-danger">*</span></label>
                                    <select class="form-select" name="mbook_currency">
                                        <option value="">Select currency</option>
                                        <option value="USD">USD - US Dollar</option>
                                        <option value="EUR">EUR - Euro</option>
                                        <option value="GBP">GBP - British Pound</option>
                                        <option value="CAD">CAD - Canadian Dollar</option>
                                        <option value="AUD">AUD - Australian Dollar</option>
                                        <option value="BDT">BDT - Bangladeshi Taka</option>
                                        <option value="INR">INR - Indian Rupee</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">MBook Wallet ID <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="mbook_wallet_id"
                                           placeholder="Your MBook wallet ID">
                                </div>
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
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0"><i class="fas fa-info-circle text-primary"></i> Processing Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2"><i class="fas fa-clock"></i> Processing Time</h6>
                        <p class="mb-0 fw-bold">5-7 Working Days</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2"><i class="fas fa-dollar-sign"></i> Minimum Amount</h6>
                        <p class="mb-0 fw-bold text-success">$2.00 USD</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2"><i class="fas fa-globe"></i> Supported Methods</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="fas fa-university text-primary me-2"></i>Global Banks</li>
                            <li class="mb-0"><i class="fas fa-mobile-alt text-success me-2"></i>MBook Wallet</li>
                        </ul>
                    </div>

                    <div class="mb-0">
                        <h6 class="text-muted mb-2"><i class="fas fa-percentage"></i> Processing Fees</h6>
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-1">US Banks: <span class="text-success">2%</span></li>
                            <li class="mb-1">Global Banks: <span class="text-warning">10%</span></li>
                            <li class="mb-0">MBook: <span class="text-info">5%</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Processing Schedule -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="card-title mb-0"><i class="fas fa-calendar-alt text-warning"></i> Processing Schedule</h5>
                </div>
                <div class="card-body">
                    <p class="mb-3 text-muted">Withdrawals are processed <strong>twice monthly</strong>:</p>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-calendar-day text-primary me-2"></i>
                            <strong>1st of month</strong>
                        </div>
                        <p class="mb-0 small text-muted ms-3">For requests before 1st</p>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-calendar-day text-primary me-2"></i>
                            <strong>16th of month</strong>
                        </div>
                        <p class="mb-0 small text-muted ms-3">For requests before 16th</p>
                    </div>
                    @php
                        $nextProcessing = auth()->user()->getNextWithdrawalDate();
                    @endphp
                    <div class="alert alert-warning py-2 px-3 mb-0">
                        <small><strong>Next processing:</strong><br>
                        {{ $nextProcessing['date']->format('M j, Y') }}<br>
                        <span class="text-muted">({{ $nextProcessing['days_until'] }} days)</span></small>
                    </div>
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
                                    <th class="border-0">Method</th>
                                    <th class="border-0">Amount</th>
                                    <th class="border-0">Fees</th>
                                    <th class="border-0">Net Amount</th>
                                    <th class="border-0">Account</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Submitted</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($withdrawalRequests as $request)
                                <tr>
                                    <td>
                                        <strong>#{{ $request->id }}</strong>
                                    </td>
                                    <td>
                                        <small class="badge bg-info">{{ $request->display_method }}</small>
                                    </td>
                                    <td>
                                        <strong class="text-warning">${{ $request->formatted_amount }}</strong>
                                    </td>
                                    <td>
                                        <span class="text-muted">${{ $request->formatted_processing_fee }}</span>
                                        <br><small class="text-muted">({{ $request->processing_fee_percentage }}%)</small>
                                    </td>
                                    <td>
                                        <strong class="text-success">${{ $request->formatted_net_amount }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            {{ $request->method_details }}
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
document.addEventListener('DOMContentLoaded', function() {
    const methodRadios = document.querySelectorAll('input[name="method"]');
    const bankFields = document.getElementById('bankFields');
    const mbookFields = document.getElementById('mbookFields');
    const withdrawalAmount = document.getElementById('withdrawalAmount');
    const bankCountry = document.getElementById('bankCountry');
    const feeCalculation = document.getElementById('feeCalculation');

    // Handle method switching
    methodRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'bank') {
                bankFields.style.display = 'block';
                mbookFields.style.display = 'none';
                updateRequiredFields('bank');
            } else if (this.value === 'mbook') {
                bankFields.style.display = 'none';
                mbookFields.style.display = 'block';
                updateRequiredFields('mbook');
            }
            calculateFees();
        });
    });

    // Update required fields based on selected method
    function updateRequiredFields(method) {
        // Clear all required attributes first
        document.querySelectorAll('#bankFields input, #bankFields select').forEach(field => {
            field.removeAttribute('required');
        });
        document.querySelectorAll('#mbookFields input, #mbookFields select').forEach(field => {
            field.removeAttribute('required');
        });

        // Add required attributes for the active method
        if (method === 'bank') {
            ['bank_name', 'account_holder_name', 'account_number', 'routing_number', 'bank_country', 'account_type'].forEach(name => {
                const field = document.querySelector(`[name="${name}"]`);
                if (field) field.setAttribute('required', 'required');
            });
        } else if (method === 'mbook') {
            ['mbook_name', 'mbook_country', 'mbook_currency', 'mbook_wallet_id'].forEach(name => {
                const field = document.querySelector(`[name="${name}"]`);
                if (field) field.setAttribute('required', 'required');
            });
        }
    }

    // Calculate and display processing fees
    function calculateFees() {
        const amount = parseFloat(withdrawalAmount.value) || 0;
        const method = document.querySelector('input[name="method"]:checked').value;
        const country = bankCountry ? bankCountry.value : 'US';

        if (amount > 0) {
            let feeRate = 0;
            if (method === 'bank') {
                feeRate = country === 'US' ? 2 : 10;
            } else if (method === 'mbook') {
                feeRate = 5;
            }

            const feeAmount = (amount * feeRate) / 100;
            const netAmount = amount - feeAmount;

            document.getElementById('grossAmount').textContent = '$' + amount.toFixed(2);
            document.getElementById('processingFee').textContent = '$' + feeAmount.toFixed(2);
            document.getElementById('feeRate').textContent = feeRate + '%';
            document.getElementById('netAmount').textContent = '$' + netAmount.toFixed(2);

            feeCalculation.style.display = 'block';
        } else {
            feeCalculation.style.display = 'none';
        }
    }

    // Event listeners for fee calculation
    withdrawalAmount.addEventListener('input', calculateFees);
    if (bankCountry) {
        bankCountry.addEventListener('change', calculateFees);
    }

    // Format routing number input
    const routingNumberField = document.querySelector('input[name="routing_number"]');
    if (routingNumberField) {
        routingNumberField.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 9) {
                value = value.substring(0, 9);
            }
            e.target.value = value;
        });
    }

    // Format account number input (numbers only)
    const accountNumberField = document.querySelector('input[name="account_number"]');
    if (accountNumberField) {
        accountNumberField.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    }

    // Validate withdrawal amount
    withdrawalAmount.addEventListener('change', function(e) {
        const availableBalance = {{ auth()->user()->withdrawable_balance }};
        const requestedAmount = parseFloat(e.target.value);

        if (requestedAmount > availableBalance) {
            alert('Requested amount exceeds your available withdrawable balance of $' + availableBalance.toFixed(2));
            e.target.value = availableBalance.toFixed(2);
            calculateFees();
        }
    });

    // Initialize with default method
    updateRequiredFields('bank');
    calculateFees();
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

.alert-info {
    background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%);
    border-left: 4px solid #2196f3;
}

.alert-info h6 {
    color: #1976d2;
    font-weight: 600;
}

.alert-info .fas {
    margin-right: 8px;
}

@media (max-width: 767.98px) {
    .table-responsive {
        font-size: 0.875rem;
    }

    .alert-info .row .col-md-6 {
        margin-bottom: 1rem;
    }
}
</style>
@endsection
