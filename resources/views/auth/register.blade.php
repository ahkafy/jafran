@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <div class="mb-3">
                        <img src="https://i.postimg.cc/xj3zjCcD/logo.png" alt="Network Investment" style="height: 48px; width: auto;">
                    </div>
                    {{ __('Register') }} - Join Network Investment System
                </div>

                <div class="card-body">
                    @if($sponsor)
                        <div class="alert alert-info">
                            <i class="fas fa-user-plus"></i> You were referred by <strong>{{ $sponsor->name }}</strong>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        @if($referralCode)
                            <input type="hidden" name="referral_code" value="{{ $referralCode }}">
                        @endif

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="phone" class="col-md-4 col-form-label text-md-end">{{ __('Phone') }}</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" autocomplete="phone">

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        @if(!$referralCode)
                        <div class="row mb-3">
                            <label for="referral_code" class="col-md-4 col-form-label text-md-end">{{ __('Referral Code (Optional)') }}</label>

                            <div class="col-md-6">
                                <input id="referral_code" type="text" class="form-control @error('referral_code') is-invalid @enderror" name="referral_code" value="{{ old('referral_code') }}" placeholder="Enter referral code">

                                @error('referral_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @endif

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-user-plus"></i> {{ __('Register & Join Network') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-4 text-center">
                        <small class="text-muted">
                            By registering, you agree to join our network investment system where you can earn through direct referrals and team building.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
