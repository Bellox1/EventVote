<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Candidate;
use App\Models\CampaignVisit;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!Auth::user()->isAdmin()) abort(403);
        
        $pendingCampaigns = Campaign::where('status', 'pending')->with('creator')->latest()->get();
        $pendingCandidates = Candidate::where('status', 'pending')->with('campaign', 'user')->latest()->get();
        
        $users = User::withCount(['campaigns', 'campaigns as active_campaigns_count' => function($query) {
            $query->where('status', 'active');
        }])->latest()->get();

        $campaigns = Campaign::with('creator')
            ->withCount('allCandidates')
            ->withCount('votes')
            ->withCount('allCandidates as candidates_count')
            ->get();

        // Calculate Views/Visits manually for each campaign (or via count)
        $campaignsStats = Campaign::with('creator')
            ->select('campaigns.*')
            ->withCount(['allCandidates', 'votes'])
            ->leftJoin('campaign_visits', 'campaigns.id', '=', 'campaign_visits.campaign_id')
            ->selectRaw('COUNT(campaign_visits.id) as unique_views_count')
            ->selectRaw('SUM(campaign_visits.hits) as total_visits_count')
            ->groupBy('campaigns.id')
            ->get();

        $mostViewedCampaign = Campaign::with('creator')
            ->leftJoin('campaign_visits', 'campaigns.id', '=', 'campaign_visits.campaign_id')
            ->select('campaigns.*')
            ->selectRaw('COUNT(campaign_visits.id) as unique_views_count')
            ->groupBy('campaigns.id')
            ->orderByDesc('unique_views_count')
            ->first();

        return view('admin.dashboard', compact(
            'pendingCampaigns', 
            'pendingCandidates',
            'users', 
            'campaignsStats',
            'mostViewedCampaign'
        ));
    }

    public function manageCampaign(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) abort(403);
        $campaign = Campaign::findOrFail($id);

        $request->validate(['status' => 'required|in:active,rejected']);
        $campaign->update(['status' => $request->status]);

        return back()->with('success', 'Campaign updated.');
    }

    public function banUser($userId)
    {
        if (!Auth::user()->isAdmin()) abort(403);
        $user = User::findOrFail($userId);
        if ($user->isAdmin()) return back()->with('error', 'Cannot ban admin.');

        $user->update(['is_banned' => true]);
        return back()->with('success', 'User banned.');
    }
}
