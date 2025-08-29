@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2 fw-bold">
                        <i class="fas fa-user text-primary"></i>
                        Profile Settings
                    </h1>
                    <p class="text-muted mb-0">Manage your personal information and account settings</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span class="d-none d-sm-inline">Back to Dashboard</span>
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Profile Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-id-card text-primary"></i>
                        Personal Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                       value="{{ old('phone', $user->phone) }}" placeholder="Optional">
                                @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="referral_code" class="form-label">Referral Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $user->referral_code }}" readonly>
                                    <button type="button" class="btn btn-outline-secondary" onclick="copyReferralCode()">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Your unique referral code</small>
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3"
                                          placeholder="Optional">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-info-circle text-info"></i>
                        Account Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Member Since:</strong>
                            <p class="text-muted mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Account Status:</strong>
                            <span class="badge bg-success">Active</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Wallet Balance:</strong>
                            <p class="text-success mb-0 fw-semibold">${{ number_format($user->wallet_balance, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Commission Balance:</strong>
                            <p class="text-primary mb-0 fw-semibold">${{ number_format($user->commission_balance, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Sponsor:</strong>
                            <p class="text-muted mb-0">
                                @if($user->sponsor)
                                    {{ $user->sponsor->name }} ({{ $user->sponsor->referral_code }})
                                @else
                                    No Sponsor
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>Total Team Members:</strong>
                            <p class="text-muted mb-0">{{ $user->children->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Network Overview -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-sitemap text-warning"></i>
                        Network Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                <h4 class="mb-1">{{ $user->children->count() }}</h4>
                                <small class="text-muted">Direct Referrals</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                <i class="fas fa-chart-line fa-2x text-success mb-2"></i>
                                <h4 class="mb-1">{{ $user->investments->count() }}</h4>
                                <small class="text-muted">Total Investments</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                                <i class="fas fa-percentage fa-2x text-info mb-2"></i>
                                <h4 class="mb-1">${{ number_format($user->commissions->where('status', 'paid')->sum('amount'), 2) }}</h4>
                                <small class="text-muted">Total Commissions</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('mlm.referral-link') }}" class="btn btn-primary me-2">
                            <i class="fas fa-share-alt"></i> Get Referral Link
                        </a>
                        <a href="{{ route('mlm.genealogy') }}" class="btn btn-outline-info">
                            <i class="fas fa-sitemap"></i> View Genealogy
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyReferralCode() {
    const referralInput = document.querySelector('input[value="{{ $user->referral_code }}"]');
    referralInput.select();
    referralInput.setSelectionRange(0, 99999); // For mobile devices

    navigator.clipboard.writeText(referralInput.value).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalHtml = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-success');

        setTimeout(function() {
            button.innerHTML = originalHtml;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-secondary');
        }, 2000);
    });
}
</script>
@endsection
