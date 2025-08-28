<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MLMService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $mlmService;

    public function __construct(MLMService $mlmService)
    {
        $this->middleware('auth');
        $this->mlmService = $mlmService;
    }

    /**
     * Show the main dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Update user's rank
        $user->updateRank();

        $stats = $this->mlmService->getUserMLMStats($user);

        // Recent activities
        $recentInvestments = $user->investments()
            ->with('investmentPackage')
            ->latest()
            ->limit(5)
            ->get();

        $recentReturns = $user->dailyReturns()
            ->with(['investment', 'investment.investmentPackage'])
            ->where('status', 'processed')
            ->latest('processed_at')
            ->limit(5)
            ->get();

        $recentCommissions = $user->commissions()
            ->with(['fromUser', 'investment'])
            ->where('status', 'paid')
            ->latest('paid_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recentInvestments', 'recentReturns', 'recentCommissions'));
    }

    /**
     * Get user profile
     */
    public function profile()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $user->update($request->only(['name', 'email', 'phone', 'address']));

        return back()->with('success', 'Profile updated successfully');
    }

    /**
     * Show wallet
     */
    public function wallet()
    {
        $user = Auth::user();

        // Get recent transactions (returns and commissions)
        $transactions = collect();

        // Add daily returns
        $returns = $user->dailyReturns()
            ->where('status', 'processed')
            ->latest('processed_at')
            ->limit(20)
            ->get()
            ->map(function ($return) {
                return [
                    'type' => 'return',
                    'amount' => $return->amount,
                    'description' => 'Daily Return - Day ' . $return->day_number,
                    'date' => $return->processed_at,
                    'status' => 'credit'
                ];
            });

        // Add commissions
        $commissions = $user->commissions()
            ->where('status', 'paid')
            ->latest('paid_at')
            ->limit(20)
            ->get()
            ->map(function ($commission) {
                return [
                    'type' => 'commission',
                    'amount' => $commission->amount,
                    'description' => ucfirst($commission->type) . ' Commission - Level ' . $commission->level,
                    'date' => $commission->paid_at,
                    'status' => 'credit'
                ];
            });

        $transactions = $returns->concat($commissions)
            ->sortByDesc('date')
            ->take(20);

        return view('wallet', compact('user', 'transactions'));
    }
}
