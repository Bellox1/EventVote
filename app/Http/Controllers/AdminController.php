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
            ->withSum(['votes as total_amount' => function($q) { $q->where('status', '=', 'confirmed'); }], 'amount')
            ->addSelect(['unique_views_count' => CampaignVisit::selectRaw('COUNT(*)')
                ->whereColumn('campaign_id', 'campaigns.id')
            ])
            ->get()
            ->map(function($campaign) {
                $total = $campaign->total_amount ?? 0;
                $campaign->site_fee = $total * 0.02;
                $campaign->aggregator_fee = $this->calculateAggregatorFee($total);
                $campaign->net_admin = $campaign->site_fee - $campaign->aggregator_fee;
                $campaign->creator_net = $total - $campaign->site_fee;
                return $campaign;
            });

        $globalStats = [
            'total_reserved' => $campaignsStats->sum('site_fee'),
            'total_aggregator' => $campaignsStats->sum('aggregator_fee'),
            'total_net_admin' => $campaignsStats->sum('net_admin'),
            'total_revenue' => $totalRevenue
        ];

        $recentTransactions = Vote::where('status', '=', 'confirmed')
            ->with(['user', 'campaign', 'candidate'])
            ->latest()
            ->take(10)
            ->get();

        $totalDemandes = Campaign::count();
        $acceptedDemandes = Campaign::whereNotIn('status', ['pending', 'rejected'])->count();
        $rejectedDemandes = Campaign::where('status', '=', 'rejected')->count();

        return view('admin.dashboard', compact(
            'pendingCampaigns', 
            'rejectedCampaigns',
            'acceptedCampaigns',
            'users', 
            'campaignsStats',
            'totalRevenue',
            'globalStats',
            'recentTransactions',
            'totalDemandes',
            'acceptedDemandes',
            'rejectedDemandes',
            'allCandidates',
            'bannedUsers'
        ));
    }

    private function calculateAggregatorFee($amount)
    {
        if ($amount == 0) return 0;
        if ($amount <= 10000) return 150;
        if ($amount <= 50000) return 300;
        if ($amount <= 150000) return 800;
        if ($amount <= 500000) return 2000;
        return 2500;
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

    public function sendContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $adminEmail = config('app.super_admin_email');
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'contactMessage' => $request->message
        ];

        Mail::send('emails.contact-message', $data, function ($message) use ($request, $adminEmail) {
            $message->to($adminEmail)
                    ->subject("Nouveau Contact [{$request->subject}] de {$request->name}");
        });

        return back()->with('success', 'Votre Excellence, votre demande a bien été transmise. Nos experts vous contacteront sous peu.');
    }
}
