@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">
                        <i class="fas fa-sitemap text-primary"></i>
                        Genealogy Tree
                    </h2>
                    <p class="text-muted mb-0">Visualize your MLM network structure</p>
                </div>
                <a href="{{ route('mlm.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Back to MLM Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-tree"></i>
                        Your Network Tree
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="genealogy-container">
                        <div class="tree-wrapper">
                            <!-- Root User (Current User) -->
                            <div class="tree-level level-0">
                                <div class="user-node root-node">
                                    <div class="user-card">
                                        <div class="user-avatar">
                                            <i class="fas fa-user-crown"></i>
                                        </div>
                                        <div class="user-info">
                                            <h6 class="mb-1">{{ Auth::user()->name }}</h6>
                                            <small class="text-muted">{{ Auth::user()->referral_code }}</small>
                                            <div class="mt-2">
                                                {!! Auth::user()->getGenealogyRankBadge() !!}
                                            </div>
                                            <div class="badge badge-success mt-1">You</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(isset($tree) && count($tree) > 0)
                                <!-- Level 1 (Direct Referrals) -->
                                <div class="tree-level level-1">
                                    <div class="row justify-content-center">
                                        @foreach($tree as $level1User)
                                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
                                            <div class="user-node">
                                                <div class="user-card level-1-card">
                                                    <div class="user-avatar bg-primary">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <div class="user-info">
                                                        <h6 class="mb-1">{{ $level1User->name }}</h6>
                                                        <small class="text-muted">{{ $level1User->referral_code }}</small>
                                                        <div class="mt-2">
                                                            {!! $level1User->getGenealogyRankBadge() !!}
                                                        </div>
                                                        <div class="badge badge-primary mt-1">Level 1</div>
                                                        <div class="mt-2">
                                                            <small class="text-success">
                                                                <i class="fas fa-users"></i>
                                                                {{ $level1User->referrals->count() }} referrals
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Level 2 (Under this Level 1 user) -->
                                            @if($level1User->referrals->count() > 0)
                                            <div class="sub-tree mt-3">
                                                <div class="row">
                                                    @foreach($level1User->referrals->take(4) as $level2User)
                                                    <div class="col-6 mb-2">
                                                        <div class="user-node small-node">
                                                            <div class="user-card level-2-card">
                                                                <div class="user-avatar bg-info">
                                                                    <i class="fas fa-user"></i>
                                                                </div>
                                                                <div class="user-info">
                                                                    <h6 class="mb-1">{{ $level2User->name }}</h6>
                                                                    <small class="text-muted">{{ $level2User->referral_code }}</small>
                                                                    <div class="mt-1">
                                                                        {!! $level2User->getGenealogyRankBadge() !!}
                                                                    </div>
                                                                    <div class="badge badge-info mt-1">Level 2</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                    @if($level1User->referrals->count() > 4)
                                                    <div class="col-12 text-center">
                                                        <small class="text-muted">
                                                            +{{ $level1User->referrals->count() - 4 }} more
                                                        </small>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="fas fa-users fa-4x text-muted"></i>
                                    </div>
                                    <h4 class="text-muted mb-3">No Team Members Yet</h4>
                                    <p class="text-muted mb-4">Start building your network by sharing your referral link</p>
                                    <a href="{{ route('mlm.referral-link') }}" class="btn btn-primary btn-lg">
                                        <i class="fas fa-share-alt"></i>
                                        Get Your Referral Link
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Network Statistics -->
    <div class="row mt-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-gradient-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1">Direct Referrals</h6>
                            <h3 class="mb-0">{{ isset($tree) ? count($tree) : 0 }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-user-plus fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-gradient-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1">Level 2 Team</h6>
                            <h3 class="mb-0">
                                @if(isset($tree))
                                    {{ $tree->sum(function($user) { return $user->referrals->count(); }) }}
                                @else
                                    0
                                @endif
                            </h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-gradient-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1">Members+ in Network</h6>
                            <h3 class="mb-0">
                                @if(isset($tree))
                                    @php
                                        $membersCount = $tree->where('rank', '!=', 'Guest')->where('rank', '!=', null)->count();
                                        $level2Members = $tree->sum(function($user) {
                                            return $user->referrals->where('rank', '!=', 'Guest')->where('rank', '!=', null)->count();
                                        });
                                    @endphp
                                    {{ $membersCount + $level2Members }}
                                @else
                                    0
                                @endif
                            </h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-trophy fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-gradient-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-1">Total Network</h6>
                            <h3 class="mb-0">
                                @if(isset($tree))
                                    {{ count($tree) + $tree->sum(function($user) { return $user->referrals->count(); }) }}
                                @else
                                    0
                                @endif
                            </h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-sitemap fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rank Distribution -->
    @if(isset($tree) && count($tree) > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie text-primary"></i>
                        Network Rank Distribution
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $allUsers = collect([$tree, $tree->pluck('referrals')->flatten()])->flatten();
                        $rankCounts = $allUsers->groupBy('rank')->map->count();
                        $totalUsers = $allUsers->count();
                    @endphp

                    <div class="row">
                        @foreach(['Guest', 'Member', 'Counsellor', 'Leader', 'Trainer', 'Senior Trainer'] as $rank)
                            @php
                                $count = $rankCounts->get($rank, 0);
                                $percentage = $totalUsers > 0 ? round(($count / $totalUsers) * 100, 1) : 0;
                                $colors = [
                                    'Guest' => 'secondary',
                                    'Member' => 'success',
                                    'Counsellor' => 'info',
                                    'Leader' => 'warning',
                                    'Trainer' => 'danger',
                                    'Senior Trainer' => 'dark'
                                ];
                            @endphp
                            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                                <div class="text-center">
                                    <div class="mb-2">
                                        <span class="badge bg-{{ $colors[$rank] }} fs-6 p-2">{{ $rank }}</span>
                                    </div>
                                    <h4 class="mb-1">{{ $count }}</h4>
                                    <small class="text-muted">{{ $percentage }}% of network</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.genealogy-container {
    overflow-x: auto;
    min-height: 400px;
}

.tree-wrapper {
    min-width: 800px;
    padding: 20px;
}

.tree-level {
    margin-bottom: 40px;
}

.user-node {
    position: relative;
    margin-bottom: 20px;
}

.user-card {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 15px;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.user-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.root-node .user-card {
    border-color: #007bff;
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
}

.level-1-card {
    border-color: #28a745;
}

.level-2-card {
    border-color: #17a2b8;
}

.user-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-size: 20px;
    color: #6c757d;
}

.small-node .user-avatar {
    width: 35px;
    height: 35px;
    font-size: 14px;
}

.small-node .user-card {
    padding: 10px;
}

.small-node h6 {
    font-size: 0.8rem;
}

.badge {
    font-size: 0.7rem;
    padding: 0.25em 0.6em;
}

.genealogy-rank-badge {
    font-size: 0.65rem !important;
    padding: 0.2em 0.5em !important;
    border-radius: 0.25rem !important;
    font-weight: 600 !important;
}

.genealogy-rank-badge i {
    font-size: 0.7em;
}

.user-info .badge {
    margin: 0.1rem;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
}

@media (max-width: 768px) {
    .tree-wrapper {
        min-width: 100%;
        padding: 10px;
    }

    .user-card {
        padding: 10px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
}
</style>
@endsection
