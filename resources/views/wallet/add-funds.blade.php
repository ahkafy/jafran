@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2 fw-bold text-dark">
                        <i class="fas fa-plus-circle text-primary"></i>
                        Add Funds
                    </h1>
                    <p class="text-muted mb-0">Choose your preferred payment method to add funds to your wallet</p>
                </div>
                <a href="{{ route('wallet') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span class="d-none d-sm-inline">Back to Wallet</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Payment Methods -->
            <div class="row g-4">
                <!-- Stripe Payment -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-primary text-white text-center">
                            <i class="fab fa-cc-stripe fa-2x mb-2"></i>
                            <h5 class="mb-0">Credit/Debit Card</h5>
                            <small>Powered by Stripe - Instant</small>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('wallet.stripe-payment') }}" method="POST" id="stripe-form">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Amount (USD)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" name="amount"
                                               min="10" max="10000" step="0.01" required>
                                    </div>
                                    <small class="text-muted">Min: $10, Max: $10,000</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Card Information</label>
                                    <div id="stripe-card-element" class="form-control" style="height: 40px; padding: 10px;">
                                        <!-- Stripe Elements will create form elements here -->
                                    </div>
                                    <div id="stripe-card-errors" role="alert" class="text-danger mt-2"></div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100" id="stripe-submit">
                                    <i class="fas fa-lock"></i> Pay with Card
                                </button>
                            </form>
                        </div>
                        <div class="card-footer bg-light">
                            <ul class="list-unstyled mb-0 small">
                                <li><i class="fas fa-check text-success"></i> Instant processing</li>
                                <li><i class="fas fa-check text-success"></i> Secure SSL encryption</li>
                                <li><i class="fas fa-check text-success"></i> 3D Secure supported</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- PayPal Payment -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header text-white text-center" style="background: #0070ba;">
                            <i class="fab fa-paypal fa-2x mb-2"></i>
                            <h5 class="mb-0">PayPal</h5>
                            <small>Pay with PayPal - Instant</small>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('wallet.paypal-payment') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Amount (USD)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" name="amount"
                                               min="10" max="10000" step="0.01" required>
                                    </div>
                                    <small class="text-muted">Min: $10, Max: $10,000</small>
                                </div>

                                <div class="text-center mb-3">
                                    <p class="text-muted">You will be redirected to PayPal to complete the payment</p>
                                </div>

                                <button type="submit" class="btn w-100" style="background: #0070ba; color: white;">
                                    <i class="fab fa-paypal"></i> Pay with PayPal
                                </button>
                            </form>
                        </div>
                        <div class="card-footer bg-light">
                            <ul class="list-unstyled mb-0 small">
                                <li><i class="fas fa-check text-success"></i> Instant processing</li>
                                <li><i class="fas fa-check text-success"></i> Buyer protection</li>
                                <li><i class="fas fa-check text-success"></i> Link bank account or card</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Bank Transfer -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-success text-white text-center">
                            <i class="fas fa-university fa-2x mb-2"></i>
                            <h5 class="mb-0">Bank Transfer</h5>
                            <small>Manual Verification Required</small>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('wallet.bank-transfer') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Amount (USD)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" name="amount"
                                               min="10" max="10000" step="0.01" required>
                                    </div>
                                    <small class="text-muted">Min: $10, Max: $10,000</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Transfer Reference</label>
                                    <input type="text" class="form-control" name="transfer_reference"
                                           placeholder="Bank transaction ID or reference" required>
                                    <small class="text-muted">Provide your bank transaction reference</small>
                                </div>

                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-university"></i> Submit Transfer Details
                                </button>
                            </form>
                        </div>
                        <div class="card-footer bg-light">
                            <ul class="list-unstyled mb-0 small">
                                <li><i class="fas fa-info text-info"></i> Manual verification (1-3 days)</li>
                                <li><i class="fas fa-info text-info"></i> Lower fees</li>
                                <li><i class="fas fa-info text-info"></i> Suitable for large amounts</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bank Transfer Instructions -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-info">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle"></i>
                                Bank Transfer Instructions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="fw-semibold">Bank Details:</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Bank Name:</strong></td>
                                            <td>Chase Bank</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Account Name:</strong></td>
                                            <td>Jafran Investment LLC</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Account Number:</strong></td>
                                            <td>1234567890</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Routing Number:</strong></td>
                                            <td>021000021</td>
                                        </tr>
                                        <tr>
                                            <td><strong>SWIFT Code:</strong></td>
                                            <td>CHASUS33</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-semibold">Instructions:</h6>
                                    <ol>
                                        <li>Transfer the desired amount to our bank account</li>
                                        <li>Use your email address as the transfer memo/reference</li>
                                        <li>Submit the transfer details using the form above</li>
                                        <li>Wait for verification (1-3 business days)</li>
                                        <li>Funds will be added to your wallet after verification</li>
                                    </ol>
                                    <div class="alert alert-warning mt-3">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Important:</strong> Please ensure the transfer reference matches exactly for faster processing.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stripe JS -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Initialize Stripe (you'll need to add your publishable key)
    const stripe = Stripe('pk_test_your_stripe_publishable_key_here');
    const elements = stripe.elements();

    // Create card element
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#495057',
                '::placeholder': {
                    color: '#6c757d',
                },
            },
        },
    });

    cardElement.mount('#stripe-card-element');

    // Handle real-time validation errors from the card Element
    cardElement.on('change', ({error}) => {
        const displayError = document.getElementById('stripe-card-errors');
        if (error) {
            displayError.textContent = error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Handle form submission
    const form = document.getElementById('stripe-form');
    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const {token, error} = await stripe.createToken(cardElement);

        if (error) {
            // Show error to customer
            const errorElement = document.getElementById('stripe-card-errors');
            errorElement.textContent = error.message;
        } else {
            // Add token to form and submit
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            form.submit();
        }
    });
</script>

<style>
.card:hover {
    transform: translateY(-5px);
    transition: transform 0.3s ease;
}

#stripe-card-element {
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
}

#stripe-card-element.StripeElement--focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
</style>
@endsection
