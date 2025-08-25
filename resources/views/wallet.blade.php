@extends('layouts.app')

@section('content')
<script>
    // Redirect to the new enhanced wallet
    window.location.href = "{{ route('wallet') }}";
</script>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">Redirecting to enhanced wallet...</p>
                    <p><a href="{{ route('wallet') }}">Click here if not redirected automatically</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
