<!-- Recursive Tree Node Component -->
<div class="tree-node" style="animation-delay: {{ $level * 0.1 }}s">
    <div class="level-indicator">Level {{ $level }}</div>

    <div class="user-card">
        <div class="user-avatar">
            {{ strtoupper(substr($user->name, 0, 2)) }}
        </div>
        <div class="user-name">{{ $user->name }}</div>
        <div class="user-stats">
            ${{ number_format($user->total_investment ?? 0, 2) }}<br>
            {{ $user->referrals->count() }} referrals
        </div>
        <span class="rank-badge {{ strtolower($user->rank ?? 'guest') }}">
            {{ $user->rank ?? 'Guest' }}
        </span>
    </div>

    @if($user->referrals->count() > 0 && $level < 5)
        <div class="connection-line vertical-line"></div>
        <div class="children-container">
            @if($user->referrals->count() > 1)
                <div class="connection-line horizontal-line"
                     style="width: {{ ($user->referrals->count() - 1) * 170 }}px;"></div>
            @endif

            @foreach($user->referrals as $child)
                @include('mlm.partials.tree-node', [
                    'user' => $child,
                    'level' => $level + 1,
                    'isLast' => $loop->last
                ])
            @endforeach
        </div>
    @elseif($user->referrals->count() > 0 && $level >= 5)
        <div class="more-indicator">
            <i class="fas fa-ellipsis-h"></i>
            <span>{{ $user->referrals->count() }} more levels</span>
        </div>
    @endif
</div>
