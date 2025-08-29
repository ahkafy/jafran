@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Genealogy Debug Test</div>
                <div class="card-body">
                    <h5>Authentication Status</h5>
                    @auth
                        <div class="alert alert-success">
                            ✅ Authenticated as: {{ auth()->user()->name }} (ID: {{ auth()->user()->id }})
                        </div>

                        <h5>User Details</h5>
                        <ul>
                            <li>Email: {{ auth()->user()->email }}</li>
                            <li>Rank: {{ auth()->user()->rank ?? 'Guest' }}</li>
                            <li>Total Investment: ${{ number_format(auth()->user()->total_investment ?? 0, 2) }}</li>
                            <li>Direct Referrals: {{ auth()->user()->referrals->count() }}</li>
                        </ul>

                        <div class="mt-4">
                            <button onclick="testGenealogyAPI()" class="btn btn-primary">Test Genealogy API</button>
                            <a href="{{ route('mlm.genealogy.graph') }}" class="btn btn-info">Go to Genealogy Graph</a>
                        </div>

                        <div id="api-results" class="mt-4"></div>
                    @else
                        <div class="alert alert-danger">
                            ❌ Not authenticated
                        </div>
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function testGenealogyAPI() {
    const results = document.getElementById('api-results');
    results.innerHTML = '<div class="alert alert-info">Testing API...</div>';

    try {
        const response = await fetch('{{ route('mlm.genealogy.data') }}?depth=2', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin'
        });

        console.log('Response status:', response.status);

        if (response.ok) {
            const data = await response.json();
            results.innerHTML = `
                <div class="alert alert-success">
                    <h6>✅ API Success!</h6>
                    <pre>${JSON.stringify(data, null, 2)}</pre>
                </div>
            `;
        } else {
            const text = await response.text();
            results.innerHTML = `
                <div class="alert alert-danger">
                    <h6>❌ API Error (${response.status})</h6>
                    <p>${text}</p>
                </div>
            `;
        }

    } catch (error) {
        console.error('Fetch error:', error);
        results.innerHTML = `
            <div class="alert alert-danger">
                <h6>❌ Network Error</h6>
                <p>${error.message}</p>
            </div>
        `;
    }
}
</script>
@endsection
