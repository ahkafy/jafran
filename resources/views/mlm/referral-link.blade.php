@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <!-- Page Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h1 class="mb-2 fw-bold text-gradient">
                                <i class="fas fa-share-alt text-primary"></i>
                                Your Referral Link
                            </h1>
                            <p class="text-muted mb-0">Share this link with others to earn commissions when they invest</p>
                        </div>
                        <a href="{{ route('mlm.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left"></i>
                            <span class="d-none d-sm-inline">Back to MLM Dashboard</span>
                            <span class="d-sm-none">Back</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Referral Card -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-gradient-primary text-white">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fas fa-link"></i>
                                Your Unique Referral Link
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info border-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <div class="flex-grow-1">
                                        <strong>How it works:</strong> When someone registers using your link and makes investments, you'll earn commissions based on our MLM structure!
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="referralLink" class="form-label fw-semibold">
                                    <i class="fas fa-link text-primary"></i>
                                    Your Unique Referral Link:
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="referralLink" value="{{ $referralLink }}" readonly>
                                    <button class="btn btn-success" type="button" onclick="copyToClipboard()">
                                        <i class="fas fa-copy"></i>
                                        <span class="d-none d-sm-inline">Copy Link</span>
                                        <span class="d-sm-none">Copy</span>
                                    </button>
                                </div>
                                <div class="form-text">
                                    <i class="fas fa-shield-alt text-success"></i>
                                    This link is unique to you and tracks all your referrals automatically.
                                </div>
                            </div>

                            <!-- Quick Stats -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <div class="card bg-light border-0 h-100">
                                        <div class="card-body text-center">
                                            <div class="mb-2">
                                                <i class="fas fa-user-tag fa-2x text-primary"></i>
                                            </div>
                                            <h5 class="fw-bold text-primary mb-1">{{ Auth::user()->referral_code }}</h5>
                                            <p class="text-muted mb-0">Your Referral Code</p>
                                            <small class="text-muted">Share this code directly</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light border-0 h-100">
                                        <div class="card-body text-center">
                                            <div class="mb-2">
                                                <i class="fas fa-users fa-2x text-success"></i>
                                            </div>
                                            <h5 class="fw-bold text-success mb-1">{{ Auth::user()->referrals->count() }}</h5>
                                            <p class="text-muted mb-0">Current Referrals</p>
                                            <small class="text-muted">People you've referred</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commission Structure -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fas fa-percentage text-primary"></i>
                                Commission Structure
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @php
                                    $levels = [
                                        ['level' => 1, 'name' => 'Direct Referrals', 'rate' => 10, 'color' => 'success', 'icon' => 'user-check'],
                                        ['level' => 2, 'name' => 'Level 2', 'rate' => 4, 'color' => 'info', 'icon' => 'users'],
                                        ['level' => 3, 'name' => 'Level 3', 'rate' => 3, 'color' => 'warning', 'icon' => 'sitemap'],
                                        ['level' => 4, 'name' => 'Level 4', 'rate' => 2, 'color' => 'danger', 'icon' => 'network-wired']
                                    ];
                                @endphp

                                @foreach($levels as $level)
                                <div class="col-lg-3 col-md-6">
                                    <div class="text-center p-3 border border-{{ $level['color'] }} rounded-3 h-100">
                                        <div class="mb-3">
                                            <i class="fas fa-{{ $level['icon'] }} fa-2x text-{{ $level['color'] }}"></i>
                                        </div>
                                        <h5 class="fw-bold text-{{ $level['color'] }} mb-1">{{ $level['rate'] }}%</h5>
                                        <h6 class="mb-2">{{ $level['name'] }}</h6>
                                        <small class="text-muted">Commission Rate</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-4 p-3 bg-light rounded-3">
                                <h6 class="fw-semibold mb-3">
                                    <i class="fas fa-lightbulb text-warning"></i>
                                    How You Earn:
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                <strong>Direct referrals invest:</strong> You earn 10%
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                <strong>Their referrals invest:</strong> You earn 4%
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                <strong>Level 3 invests:</strong> You earn 3%
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                <strong>Level 4 invests:</strong> You earn 2%
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Sharing -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fas fa-share-square text-primary"></i>
                                Share Your Link
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">Choose your preferred method to share your referral link:</p>

                            <div class="row g-3">
                                <div class="col-lg-4 col-md-6">
                                    <a href="https://wa.me/?text=Join%20our%20MLM%20Investment%20System%20and%20earn%20daily%20returns!%20{{ urlencode($referralLink) }}" target="_blank" class="btn btn-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                        <i class="fab fa-whatsapp fa-2x mb-2"></i>
                                        <strong>WhatsApp</strong>
                                        <small class="opacity-75">Share via WhatsApp</small>
                                    </a>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($referralLink) }}" target="_blank" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                        <i class="fab fa-facebook fa-2x mb-2"></i>
                                        <strong>Facebook</strong>
                                        <small class="opacity-75">Share on Facebook</small>
                                    </a>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <a href="https://twitter.com/intent/tweet?text=Join%20our%20MLM%20Investment%20System%20and%20earn%20daily%20returns!&url={{ urlencode($referralLink) }}" target="_blank" class="btn btn-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4">
                                        <i class="fab fa-twitter fa-2x mb-2"></i>
                                        <strong>Twitter</strong>
                                        <small class="opacity-75">Share on Twitter</small>
                                    </a>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <button type="button" class="btn btn-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4" onclick="shareViaEmail()">
                                        <i class="fas fa-envelope fa-2x mb-2"></i>
                                        <strong>Email</strong>
                                        <small class="opacity-75">Send via Email</small>
                                    </button>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <button type="button" class="btn btn-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4" onclick="shareViaSMS()">
                                        <i class="fas fa-sms fa-2x mb-2"></i>
                                        <strong>SMS</strong>
                                        <small class="opacity-75">Send via SMS</small>
                                    </button>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <button type="button" class="btn btn-dark w-100 h-100 d-flex flex-column align-items-center justify-content-center p-4" onclick="copyToClipboard()">
                                        <i class="fas fa-copy fa-2x mb-2"></i>
                                        <strong>Copy Link</strong>
                                        <small class="opacity-75">Copy to clipboard</small>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Investment Benefits -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fas fa-gift"></i>
                                MLM Investment Benefits
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <h6 class="fw-semibold text-primary mb-3">
                                        <i class="fas fa-chart-line"></i>
                                        Investment Returns:
                                    </h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <strong>1% daily returns</strong> for 200 days
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <strong>200% total return</strong> on investment
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            Minimum investment: <strong>$5</strong>
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            Maximum investment: <strong>$100</strong> per package
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-semibold text-primary mb-3">
                                        <i class="fas fa-users"></i>
                                        MLM Benefits:
                                    </h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <strong>10% commission</strong> on direct referrals
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            Earn from <strong>4 generations</strong> deep
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <strong>Instant payouts</strong> to your wallet
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <strong>No joining fees</strong> or monthly charges
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mt-4 p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="fw-semibold mb-1">Ready to start earning?</h6>
                                        <small class="text-muted">Share your referral link and start building your network today!</small>
                                    </div>
                                    <div class="text-end">
                                        <button type="button" class="btn btn-primary" onclick="copyToClipboard()">
                                            <i class="fas fa-copy"></i> Copy Link
                                        </button>
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

<style>
.text-gradient {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.input-group .form-control:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
}

@media (max-width: 767.98px) {
    .card-body .row .col-lg-4 .btn,
    .card-body .row .col-lg-3 .btn {
        padding: 2rem 1rem;
        min-height: 120px;
    }
}
</style>

<script>
function copyToClipboard() {
    const referralLink = document.getElementById('referralLink');
    referralLink.select();
    referralLink.setSelectionRange(0, 99999);

    try {
        document.execCommand('copy');

        // Show success feedback
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
        btn.classList.remove('btn-success', 'btn-dark');
        btn.classList.add('btn-success');

        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-dark');
        }, 2000);

    } catch (err) {
        console.error('Failed to copy: ', err);
        alert('Failed to copy link. Please copy manually.');
    }
}

function shareViaEmail() {
    const subject = encodeURIComponent('Join our MLM Investment System');
    const body = encodeURIComponent(`Hi!\n\nI'd like to invite you to join our MLM Investment System where you can earn 1% daily returns for 200 days.\n\nJoin using my referral link: {{ $referralLink }}\n\nBenefits:\n- 1% daily returns for 200 days\n- Minimum investment: $5\n- Maximum investment: $100 per package\n- Build your own team and earn commissions\n\nGet started today!`);

    window.open(`mailto:?subject=${subject}&body=${body}`, '_blank');
}

function shareViaSMS() {
    const message = encodeURIComponent(`Join our MLM Investment System and earn 1% daily returns! Use my referral link: {{ $referralLink }}`);
    window.open(`sms:?body=${message}`, '_blank');
}
</script>
@endsection
