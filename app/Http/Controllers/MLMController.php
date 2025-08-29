<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commission;
use App\Models\User;
use App\Services\MLMService;
use Illuminate\Support\Facades\Auth;

class MLMController extends Controller
{
    protected $mlmService;

    public function __construct(MLMService $mlmService)
    {
        $this->middleware('auth');
        $this->mlmService = $mlmService;
    }

    /**
     * Show MLM dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $stats = $this->mlmService->getUserMLMStats($user);

        $recentCommissions = $user->commissions()
            ->with(['fromUser', 'investment'])
            ->latest()
            ->limit(10)
            ->get();

        return view('mlm.index', compact('stats', 'recentCommissions'));
    }

    /**
     * Show genealogy tree
     */
    public function genealogy()
    {
        $user = Auth::user();

        // Update current user's rank
        $user->updateRank();

        // Get all 5 levels of referrals
        $tree = $user->referrals()->with([
            'referrals.referrals.referrals.referrals' // Load 5 levels deep
        ])->get();

        // Update ranks for all users in the tree recursively
        $this->updateTreeRanks($tree);

        return view('mlm.genealogy', compact('tree'));
    }

    /**
     * Recursively update ranks for all users in the tree
     */
    private function updateTreeRanks($users)
    {
        foreach ($users as $user) {
            $user->updateRank();
            if ($user->referrals->count() > 0) {
                $this->updateTreeRanks($user->referrals);
            }
        }
    }

    /**
     * Show referral details
     */
    public function referrals()
    {
        $user = Auth::user();
        $directReferrals = $user->referrals()
            ->with(['investments' => function ($query) {
                $query->selectRaw('user_id, SUM(amount) as total_investment, COUNT(*) as investment_count')
                    ->groupBy('user_id');
            }])
            ->get();

        return view('mlm.referrals', compact('directReferrals'));
    }

    /**
     * Show commissions history
     */
    public function commissions()
    {
        $commissions = Auth::user()->commissions()
            ->with(['fromUser', 'investment.investmentPackage'])
            ->latest()
            ->paginate(15);

        return view('mlm.commissions', compact('commissions'));
    }

    /**
     * Show team overview
     */
    public function team()
    {
        $user = Auth::user();
        $stats = $this->mlmService->getUserMLMStats($user);

        // Get team members by levels (all 5 levels)
        $teamLevels = [];
        for ($level = 1; $level <= 5; $level++) {
            $teamLevels[$level] = $this->getTeamMembersByLevel($user, $level);
        }

        return view('mlm.team', compact('stats', 'teamLevels'));
    }

    /**
     * Get team members by level
     */
    private function getTeamMembersByLevel(User $user, $targetLevel, $currentLevel = 0)
    {
        if ($currentLevel >= $targetLevel) {
            return collect([$user]);
        }

        $members = collect();
        foreach ($user->referrals as $referral) {
            if ($currentLevel + 1 === $targetLevel) {
                $members->push($referral);
            } else {
                $members = $members->merge($this->getTeamMembersByLevel($referral, $targetLevel, $currentLevel + 1));
            }
        }

        return $members;
    }

    /**
     * Show referral link
     */
    public function referralLink()
    {
        $user = Auth::user();
        $referralLink = route('register') . '?ref=' . $user->referral_code;

        return view('mlm.referral-link', compact('referralLink'));
    }

    /**
     * Show interactive genealogy graph view
     */
    public function genealogyGraph()
    {
        return view('mlm.genealogy-graph');
    }

    /**
     * Return genealogy JSON data (supports lazy loading)
     */
    public function genealogyData(Request $request)
    {
        $user = Auth::user();

        // If parent param is provided, return its direct children
        if ($request->has('parent')) {
            $parentId = (int) $request->get('parent');
            $parent = User::with('referrals')->find($parentId);
            if (!$parent) {
                return response()->json(['error' => 'Parent not found'], 404);
            }

            $children = $parent->referrals->map(function ($c) {
                return [
                    'id' => $c->id,
                    'label' => $c->name,
                    'rank' => $c->rank ?? 'Guest',
                    'investment' => $c->total_investment ?? 0,
                    'referrals_count' => $c->referrals->count()
                ];
            });

            return response()->json(['nodes' => $children]);
        }

        // Otherwise return nested tree up to depth (default 2)
        $depth = (int) $request->get('depth', 2);

        $build = function ($u, $current = 0) use (&$build, $depth) {
            $node = [
                'id' => $u->id,
                'label' => $u->name,
                'rank' => $u->rank ?? 'Guest',
                'investment' => $u->total_investment ?? 0,
                'referrals_count' => $u->referrals->count(),
                'children' => []
            ];

            if ($current < $depth) {
                $u->load('referrals');
                foreach ($u->referrals as $r) {
                    $node['children'][] = $build($r, $current + 1);
                }
            }

            return $node;
        };

        $root = $build($user, 0);

        return response()->json($root);
    }
}
