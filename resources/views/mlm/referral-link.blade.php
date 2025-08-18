@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-link"></i> Your Referral Link</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Share this link with others to earn commissions when they invest!
                    </div>

                    <div class="form-group">
                        <label for="referralLink">Your Unique Referral Link:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="referralLink" value="{{ $referralLink }}" readonly>
                            <div class="input-group-append">
                                <button class="btn btn-success" type="button" onclick="copyToClipboard()">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Your Referral Code</h5>
                                    <h3 class="text-primary">{{ Auth::user()->referral_code }}</h3>
                                    <p class="text-muted">Share this code directly</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5>Commission Structure</h5>
                                    <ul class="list-unstyled">
                                        <li><strong>Direct Referrals:</strong> 10%</li>
                                        <li><strong>Level 2:</strong> 4%</li>
                                        <li><strong>Level 3:</strong> 3%</li>
                                        <li><strong>Level 4:</strong> 2%</li>
                                        <li><strong>Level 5:</strong> 2%</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5>How to Share:</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <a href="https://wa.me/?text=Join%20our%20MLM%20Investment%20System%20and%20earn%20daily%20returns!%20{{ urlencode($referralLink) }}" target="_blank" class="btn btn-success btn-block">
                                    <i class="fab fa-whatsapp"></i> Share on WhatsApp
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($referralLink) }}" target="_blank" class="btn btn-primary btn-block">
                                    <i class="fab fa-facebook"></i> Share on Facebook
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="https://twitter.com/intent/tweet?text=Join%20our%20MLM%20Investment%20System%20and%20earn%20daily%20returns!&url={{ urlencode($referralLink) }}" target="_blank" class="btn btn-info btn-block">
                                    <i class="fab fa-twitter"></i> Share on Twitter
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded">
                        <h6>MLM Benefits:</h6>
                        <ul>
                            <li>Earn <strong>1% daily returns</strong> for 200 days on investments</li>
                            <li>Get <strong>10% commission</strong> on direct referral investments</li>
                            <li>Earn from <strong>4 generations</strong> of your downline</li>
                            <li>Minimum investment: <strong>$5</strong></li>
                            <li>Maximum investment: <strong>$100</strong> per package</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard() {
    const referralLink = document.getElementById('referralLink');
    referralLink.select();
    referralLink.setSelectionRange(0, 99999);

    try {
        document.execCommand('copy');
        alert('Referral link copied to clipboard!');
    } catch (err) {
        console.error('Failed to copy: ', err);
        alert('Failed to copy link. Please copy manually.');
    }
}
</script>
@endsection
