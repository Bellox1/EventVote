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

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!Auth::user()->isAdmin()) abort(403);
        
        $pendingCampaigns = Campaign::where('status', 'pending')
            ->with(['creator' => function($query) {
                $query->withCount([
                    'campaigns as active_campaigns_count' => function($q) { $q->where('status', 'active'); },
                    'campaigns as rejected_campaigns_count' => function($q) { $q->where('status', 'rejected'); }
                ]);
            }])
            ->latest()
            ->get();
            
        $rejectedCampaigns = Campaign::where('status', 'rejected')
            ->with(['creator' => function($query) {
                $query->withCount([
                    'campaigns as active_campaigns_count' => function($q) { $q->where('status', 'active'); },
                    'campaigns as rejected_campaigns_count' => function($q) { $q->where('status', 'rejected'); }
                ]);
            }])
            ->latest()
            ->get();
            
        $acceptedCampaigns = Campaign::whereNotIn('status', ['pending', 'rejected'])
            ->with(['creator' => function($query) {
                $query->withCount([
                    'campaigns as active_campaigns_count' => function($q) { $q->where('status', 'active'); },
                    'campaigns as rejected_campaigns_count' => function($q) { $q->where('status', 'rejected'); }
                ]);
            }])
            ->latest()
            ->get();
        
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

        $totalDemandes = Campaign::count();
        $acceptedDemandes = Campaign::whereNotIn('status', ['pending', 'rejected'])->count();
        $rejectedDemandes = Campaign::where('status', 'rejected')->count();
        
        $allCandidates = Candidate::with(['campaign', 'user'])->latest()->get();

        return view('admin.dashboard', compact(
            'pendingCampaigns', 
            'rejectedCampaigns',
            'acceptedCampaigns',
            'users', 
            'campaignsStats',
            'mostViewedCampaign',
            'totalDemandes',
            'acceptedDemandes',
            'rejectedDemandes',
            'allCandidates'
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
}
