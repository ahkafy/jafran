@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Investment Packages</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($packages as $package)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header text-center">
                                    <h5>{{ $package->name }}</h5>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <p class="card-text">{{ $package->description }}</p>

                                    <div class="mt-auto">
                                        <ul class="list-unstyled">
                                            <li><strong>Investment:</strong> ${{ number_format($package->amount, 2) }}</li>
                                            <li><strong>Daily Return:</strong> {{ $package->daily_return_percentage }}% (${{ number_format($package->getDailyReturnAmount(), 2) }})</li>
                                            <li><strong>Duration:</strong> {{ $package->return_days }} days</li>
                                            <li><strong>Total Return:</strong> ${{ number_format($package->getTotalReturnAmount(), 2) }}</li>
                                            <li><strong>Net Profit:</strong> ${{ number_format($package->getTotalReturnAmount() - $package->amount, 2) }}</li>
                                        </ul>

                                        @if(Auth::user()->wallet_balance >= $package->amount)
                                            <form action="{{ route('investments.invest') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="package_id" value="{{ $package->id }}">
                                                <input type="hidden" name="amount" value="{{ $package->amount }}">
                                                <button type="submit" class="btn btn-success btn-block">
                                                    Invest ${{ number_format($package->amount, 2) }}
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-secondary btn-block" disabled>
                                                Insufficient Balance
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if($userInvestments->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Your Active Investments</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Package</th>
                                    <th>Amount</th>
                                    <th>Daily Return</th>
                                    <th>Days Completed</th>
                                    <th>Total Returned</th>
                                    <th>Status</th>
                                    <th>Start Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userInvestments as $investment)
                                <tr>
                                    <td>{{ $investment->investmentPackage->name }}</td>
                                    <td>${{ number_format($investment->amount, 2) }}</td>
                                    <td>${{ number_format($investment->daily_return, 2) }}</td>
                                    <td>{{ $investment->days_completed }}/{{ $investment->investmentPackage->return_days }}</td>
                                    <td>${{ number_format($investment->total_returned, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $investment->status == 'active' ? 'success' : ($investment->status == 'completed' ? 'primary' : 'secondary') }}">
                                            {{ ucfirst($investment->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $investment->start_date->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('investments.details', $investment) }}" class="btn btn-sm btn-info">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
