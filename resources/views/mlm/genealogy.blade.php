@extends('layouts.app')

@section('title', 'Genealogy Tree')

@section('styles')
<style>
    .mindmap-container {
        overflow-x: auto;
        padding: 30px;
        min-height: 80vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        margin: 20px 0;
        position: relative;
    }

    .mindmap-tree {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-width: 100%;
        position: relative;
    }

    .tree-node {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 0 15px;
        position: relative;
        animation: fadeInUp 0.6s ease-out;
    }

    .user-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        padding: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        text-align: center;
        min-width: 150px;
        max-width: 200px;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
        position: relative;
    }

    .user-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
    }

    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(45deg, #4CAF50, #45a049);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.2em;
        margin: 0 auto 10px;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .user-name {
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
        font-size: 0.9em;
    }

    .user-stats {
        font-size: 0.75em;
        color: #666;
        margin-bottom: 8px;
    }

    .rank-badge {
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 0.7em;
        font-weight: bold;
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .rank-badge.guest { background: linear-gradient(45deg, #9E9E9E, #757575); }
    .rank-badge.member { background: linear-gradient(45deg, #4CAF50, #388E3C); }
    .rank-badge.counsellor { background: linear-gradient(45deg, #2196F3, #1976D2); }
    .rank-badge.associate { background: linear-gradient(45deg, #FF9800, #F57C00); }
    .rank-badge.director { background: linear-gradient(45deg, #9C27B0, #7B1FA2); }

    .children-container {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 30px;
        gap: 20px;
    }

    .connection-line {
        position: absolute;
        background: rgba(255, 255, 255, 0.6);
        z-index: 1;
    }

    .vertical-line {
        width: 2px;
        height: 30px;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
    }

    .horizontal-line {
        height: 2px;
        top: 115px;
        left: 50%;
        transform: translateX(-50%);
    }

    .level-indicator {
        position: absolute;
        top: -40px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(255, 255, 255, 0.9);
        padding: 5px 15px;
        border-radius: 15px;
        font-size: 0.8em;
        font-weight: bold;
        color: #5e35b1;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .root-node .user-card {
        background: linear-gradient(135deg, #FFD700, #FFA500);
        color: #333;
        border: 3px solid #FF8C00;
        transform: scale(1.1);
    }

    .root-node .user-avatar {
        background: linear-gradient(45deg, #FF6B35, #F7931E);
        border-color: #FFD700;
    }

    .more-indicator {
        margin-top: 20px;
        text-align: center;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.8em;
        background: rgba(255, 255, 255, 0.1);
        padding: 8px 15px;
        border-radius: 20px;
        backdrop-filter: blur(5px);
    }

    .more-indicator i {
        margin-right: 5px;
    }

    .empty-state {
        text-align: center;
        color: rgba(255, 255, 255, 0.8);
        padding: 50px;
    }

    .stats-summary {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .stats-summary h5 {
        color: white;
        margin-bottom: 15px;
    }

    .stat-item {
        display: inline-block;
        margin: 5px 15px 5px 0;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.9em;
    }

    .stat-item strong {
        color: #FFD700;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .mindmap-container {
            padding: 15px;
        }

        .user-card {
            min-width: 120px;
            max-width: 150px;
            padding: 10px;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            font-size: 1em;
        }

        .children-container {
            gap: 10px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-sitemap text-primary me-2"></i>
                        Genealogy Tree - Mindmap View
                    </h1>
                    <p class="text-muted mb-0">Your 5-level network visualization</p>
                </div>
                <div>
                    <a href="{{ route('mlm.team') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-list me-1"></i>
                        Table View
                    </a>
                    <a href="{{ route('mlm.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Back to Dashboard
                    </a>
                </div>
            </div>

            @php
                $user = Auth::user();

                // Helper functions for statistics
                function countTotalReferrals($users) {
                    $count = $users->count();
                    foreach ($users as $user) {
                        $count += countTotalReferrals($user->referrals);
                    }
                    return $count;
                }

                function calculateTotalInvestment($users) {
                    $total = 0;
                    foreach ($users as $user) {
                        $total += $user->total_investment ?? 0;
                        $total += calculateTotalInvestment($user->referrals);
                    }
                    return $total;
                }

                function countActiveReferrals($users) {
                    $count = 0;
                    foreach ($users as $user) {
                        if (($user->total_investment ?? 0) > 0) {
                            $count++;
                        }
                        $count += countActiveReferrals($user->referrals);
                    }
                    return $count;
                }

                $totalReferrals = countTotalReferrals($tree);
                $totalInvestment = calculateTotalInvestment($tree);
                $activeReferrals = countActiveReferrals($tree);
            @endphp

            <div class="stats-summary">
                <h5><i class="fas fa-chart-line me-2"></i>Network Overview</h5>
                <div class="stat-item">
                    <strong>{{ $totalReferrals }}</strong> Total Referrals
                </div>
                <div class="stat-item">
                    <strong>{{ $activeReferrals }}</strong> Active Members
                </div>
                <div class="stat-item">
                    <strong>${{ number_format($totalInvestment, 2) }}</strong> Total Investment
                </div>
                <div class="stat-item">
                    <strong>{{ ucfirst($user->rank) }}</strong> Your Rank
                </div>
            </div>

            <div class="mindmap-container">
                @if($tree->count() > 0)
                    <div class="mindmap-tree">
                        <!-- Root Node (Current User) -->
                        <div class="tree-node root-node">
                            <div class="level-indicator">You</div>
                            <div class="user-card">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="user-stats">
                                    ${{ number_format($user->total_investment ?? 0, 2) }}
                                </div>
                                <span class="rank-badge {{ strtolower($user->rank ?? 'guest') }}">
                                    {{ $user->rank ?? 'Guest' }}
                                </span>
                            </div>

                            @if($tree->count() > 0)
                                <div class="connection-line vertical-line"></div>
                                <div class="children-container">
                                    @foreach($tree as $level1User)
                                        @include('mlm.partials.tree-node', [
                                            'user' => $level1User,
                                            'level' => 1,
                                            'isLast' => $loop->last
                                        ])
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-users fa-4x mb-3 opacity-50"></i>
                        <h4>No Referrals Yet</h4>
                        <p>Start building your network by referring new members.</p>
                        <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-plus me-2"></i>
                            Get Your Referral Link
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
