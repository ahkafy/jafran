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
        $tree = $this->mlmService->getGenealogyTree($user);

        return view('mlm.genealogy', compact('tree'));
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

        // Get team members by levels
        $teamLevels = [];
        for ($level = 1; $level <= 4; $level++) {
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
}
