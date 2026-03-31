<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Candidate;
use App\Models\CampaignVisit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\CampaignStatusMail;
use App\Models\Vote;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!Auth::user()->isAdmin()) abort(403);
        
        $pendingCampaigns = Campaign::where('status', '=', 'pending')
            ->with(['creator' => function($query) {
                $query->withCount([
                    'campaigns as active_campaigns_count' => function($q) { $q->where('status', '=', 'active'); },
                    'campaigns as rejected_campaigns_count' => function($q) { $q->where('status', '=', 'rejected'); }
                ]);
            }])
            ->latest()
            ->get();
            
        $rejectedCampaigns = Campaign::where('status', '=', 'rejected')
            ->with(['creator' => function($query) {
                $query->withCount([
                    'campaigns as active_campaigns_count' => function($q) { $q->where('status', '=', 'active'); },
                    'campaigns as rejected_campaigns_count' => function($q) { $q->where('status', '=', 'rejected'); }
                ]);
            }])
            ->latest()
            ->get();
            
        $acceptedCampaigns = Campaign::whereNotIn('status', ['pending', 'rejected'])
            ->with(['creator' => function($query) {
                $query->withCount([
                    'campaigns as active_campaigns_count' => function($q) { $q->where('status', '=', 'active'); },
                    'campaigns as rejected_campaigns_count' => function($q) { $q->where('status', '=', 'rejected'); }
                ]);
            }])
            ->latest()
            ->get();
        
        $users = User::where(function($query) {
            $query->where('is_banned', false)->orWhereNull('is_banned');
        })->withCount(['campaigns', 'campaigns as active_campaigns_count' => function($query) {
            $query->where('status', '=', 'active');
        }])->latest()->get();

        $bannedUsers = User::where('is_banned', true)
            ->withCount(['campaigns', 'campaigns as active_campaigns_count' => function($query) {
                $query->where('status', '=', 'active');
            }])->latest()->get();

        $campaigns = Campaign::with('creator')
            ->withCount('allCandidates')
            ->withCount('votes')
            ->withCount('allCandidates as candidates_count')
            ->get();

        // Calculate Views/Visits manually for each campaign (or via count)
        $allCandidates = Candidate::with(['campaign', 'user'])->latest()->get();

        // 💰 Financial Stats
        $totalRevenue = Vote::where('status', '=', 'confirmed')->sum('amount');
        
        $campaignsStats = Campaign::with('creator')
            ->select('campaigns.*')
            ->withCount(['allCandidates'])
            ->withSum(['votes as votes_sum_count' => function($q) { $q->where('status', '=', 'confirmed'); }], 'votes_count')
            ->withSum(['votes as revenue' => function($q) { $q->where('status', '=', 'confirmed'); }], 'amount')
            ->leftJoin('campaign_visits', 'campaigns.id', '=', 'campaign_visits.campaign_id')
            ->selectRaw('COUNT(campaign_visits.id) as unique_views_count')
            ->groupBy('campaigns.id')
            ->get();

        $recentTransactions = Vote::where('status', '=', 'confirmed')
            ->with(['user', 'campaign', 'candidate'])
            ->latest()
            ->take(10)
            ->get();

        $totalDemandes = Campaign::count('*');
        $acceptedDemandes = Campaign::whereNotIn('status', ['pending', 'rejected'])->count('*');
        $rejectedDemandes = Campaign::where('status', '=', 'rejected')->count('*');

        return view('admin.dashboard', compact(
            'pendingCampaigns', 
            'rejectedCampaigns',
            'acceptedCampaigns',
            'users', 
            'campaignsStats',
            'totalRevenue',
            'recentTransactions',
            'totalDemandes',
            'acceptedDemandes',
            'rejectedDemandes',
            'allCandidates',
            'bannedUsers'
        ));
    }

    public function manageCampaign(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) abort(403);
        $campaign = Campaign::findOrFail($id);

        $request->validate([
            'status' => 'required|in:active,rejected',
            'rejection_reason' => 'nullable|string'
        ]);
        
        $campaign->update([
            'status' => $request->status,
            'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null
        ]);

        // Envoyer l'email au créateur
        Mail::to($campaign->creator->email)->send(new CampaignStatusMail($campaign, $request->status));

        return back()->with('success', 'La campagne a été mise à jour et le créateur a été notifié.');
    }

    public function banUser($userId)
    {
        if (!Auth::user()->isAdmin()) abort(403);
        $user = User::findOrFail($userId);
        if ($user->isAdmin()) return back()->with('error', 'Cannot ban admin.');

        $user->update(['is_banned' => true]);
        return back()->with('success', 'User banned.');
    }

    public function unbanUser($userId)
    {
        if (!Auth::user()->isAdmin()) abort(403);
        $user = User::findOrFail($userId);

        $user->update(['is_banned' => false]);
        return back()->with('success', 'User restored.');
    }
}
