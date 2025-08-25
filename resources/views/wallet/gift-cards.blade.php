@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2 fw-bold text-dark">
                        <i class="fas fa-gift text-success"></i>
                        Gift Cards
                    </h1>
                    <p class="text-muted mb-0">Create gift cards from your wallet balance or redeem gift cards from others</p>
                </div>
                <a href="{{ route('wallet') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span class="d-none d-sm-inline">Back to Wallet</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Tabs for Redeem/Create Gift Card -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 pb-0">
                    <ul class="nav nav-tabs nav-justified" id="giftCardTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="redeem-tab" data-bs-toggle="tab" data-bs-target="#redeem" type="button" role="tab" aria-controls="redeem" aria-selected="true">
                                <i class="fas fa-magic"></i> Redeem Gift Card
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="create-tab" data-bs-toggle="tab" data-bs-target="#create" type="button" role="tab" aria-controls="create" aria-selected="false">
                                <i class="fas fa-plus"></i> Create Gift Card
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="giftCardTabContent">
                        <!-- Redeem Tab -->
                        <div class="tab-pane fade show active" id="redeem" role="tabpanel" aria-labelledby="redeem-tab">
                            <form action="{{ route('wallet.redeem-gift-card') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Gift Card Code</label>
                                    <input type="text" class="form-control" name="gift_card_code"
                                           placeholder="GC-XXXXXXXXXXXX" required style="text-transform: uppercase;">
                                    <small class="text-muted">Enter the complete gift card code</small>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-magic"></i> Redeem Gift Card
                                </button>
                            </form>
                        </div>
                        <!-- Create Tab -->
                        <div class="tab-pane fade" id="create" role="tabpanel" aria-labelledby="create-tab">
                            <form action="{{ route('wallet.create-gift-card') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Amount (USD)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" name="amount"
                                               min="10" max="1000" step="0.01" required>
                                    </div>
                                    <small class="text-muted">Min: $10, Max: $1,000</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Message (Optional)</label>
                                    <textarea class="form-control" name="message" rows="3"
                                            placeholder="Add a personal message..."></textarea>
                                    <small class="text-muted">Max 200 characters</small>
                                </div>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Available Balance:</strong> ${{ number_format(auth()->user()->wallet_balance, 2) }}
                                </div>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-gift"></i> Create Gift Card
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gift Cards Lists -->
        <div class="col-lg-6">
            <div class="row">
                <div class="col-12 col-md-6 mb-4">
                    <!-- Created Gift Cards -->
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-semibold text-dark">
                                    <i class="fas fa-list text-success"></i>
                                    Gift Cards You Created
                                </h5>
                                <span class="badge bg-success">{{ $createdGiftCards->total() }} cards</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($createdGiftCards->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-0">Code</th>
                                                <th class="border-0">Amount</th>
                                                <th class="border-0">Balance</th>
                                                <th class="border-0">Status</th>
                                                <th class="border-0">Created</th>
                                                <th class="border-0">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($createdGiftCards as $giftCard)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <code class="bg-light p-1 rounded">{{ $giftCard->code }}</code>
                                                        @if($giftCard->message)
                                                        <br><small class="text-muted">{{ Str::limit($giftCard->message, 30) }}</small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <strong class="text-success">${{ $giftCard->formatted_amount }}</strong>
                                                </td>
                                                <td>
                                                    <strong class="text-primary">${{ $giftCard->formatted_balance }}</strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $giftCard->status === 'active' ? 'success' : ($giftCard->status === 'redeemed' ? 'primary' : 'secondary') }}">
                                                        {{ ucfirst($giftCard->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div>
                                                        {{ $giftCard->created_at->format('M d, Y') }}
                                                        <br><small class="text-muted">{{ $giftCard->created_at->diffForHumans() }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-primary"
                                                                onclick="copyToClipboard('{{ $giftCard->code }}')" title="Copy Code">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                        @if($giftCard->status === 'active')
                                                        <button type="button" class="btn btn-outline-secondary"
                                                                onclick="shareGiftCard('{{ $giftCard->code }}')" title="Share">
                                                            <i class="fas fa-share"></i>
                                                        </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer bg-light">
                                    {{ $createdGiftCards->appends(request()->query())->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-gift fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted mb-3">No Gift Cards Created</h5>
                                    <p class="text-muted">Create your first gift card using the form on the left</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 mb-4">
                    <!-- Redeemed Gift Cards -->
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-light border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-semibold text-dark">
                                    <i class="fas fa-history text-primary"></i>
                                    Gift Cards You Redeemed
                                </h5>
                                <span class="badge bg-primary">{{ $redeemedGiftCards->total() }} cards</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($redeemedGiftCards->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-0">Code</th>
                                                <th class="border-0">Amount</th>
                                                <th class="border-0">From</th>
                                                <th class="border-0">Redeemed</th>
                                                <th class="border-0">Message</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($redeemedGiftCards as $giftCard)
                                            <tr>
                                                <td>
                                                    <code class="bg-light p-1 rounded">{{ $giftCard->code }}</code>
                                                </td>
                                                <td>
                                                    <strong class="text-success">${{ $giftCard->formatted_amount }}</strong>
                                                </td>
                                                <td>
                                                    <div>
                                                        {{ $giftCard->creator->name }}
                                                        <br><small class="text-muted">{{ $giftCard->creator->email }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        {{ $giftCard->redeemed_at->format('M d, Y') }}
                                                        <br><small class="text-muted">{{ $giftCard->redeemed_at->diffForHumans() }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($giftCard->message)
                                                        <small class="text-muted">{{ $giftCard->message }}</small>
                                                    @else
                                                        <small class="text-muted">No message</small>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer bg-light">
                                    {{ $redeemedGiftCards->appends(request()->query())->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted mb-3">No Gift Cards Redeemed</h5>
                                    <p class="text-muted">Gift cards you redeem will appear here</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'toast position-fixed top-0 end-0 m-3';
        toast.innerHTML = `
            <div class="toast-header">
                <i class="fas fa-check-circle text-success me-2"></i>
                <strong class="me-auto">Copied!</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                Gift card code copied to clipboard
            </div>
        `;
        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        // Remove toast after it's hidden
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    });
}

function shareGiftCard(code) {
    if (navigator.share) {
        navigator.share({
            title: 'Gift Card from Jafran Investment',
            text: `I've sent you a gift card! Use code: ${code}`,
            url: window.location.origin
        });
    } else {
        // Fallback - copy to clipboard
        copyToClipboard(`Gift Card Code: ${code}\nRedeem at: ${window.location.origin}`);
    }
}

// Auto-uppercase gift card code input
document.querySelector('input[name="gift_card_code"]').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase();
});
</script>

<style>
.table-hover tbody tr:hover {
    background-color: rgba(255, 107, 53, 0.05);
}

code {
    font-family: 'Courier New', monospace;
    font-weight: bold;
}

@media (max-width: 767.98px) {
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
@endsection
