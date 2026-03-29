<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function index()
    {
        $query = Campaign::where('status', 'active');
        $total = $query->count();
        
        // Boutique Dynamic Pagination Logic
        if ($total < 10) {
            $perPage = 20; // Show all on 1 page
        } elseif ($total <= 20) {
            $perPage = ceil($total / 2); // Split in 2 equal parts
        } else {
            $perPage = 9; // Grid-friendly layout for large counts
        }

        $campaigns = $query->latest()->paginate($perPage);
        return view('campaigns.index', compact('campaigns'));
    }

    public function show($slug)
    {
        $campaign = Campaign::where('slug', $slug)
            ->orWhere('code', strtoupper($slug))
            ->firstOrFail();

        if ($campaign->status !== 'active' && (!Auth::check() || (Auth::id() !== $campaign->user_id && !Auth::user()->isAdmin()))) {
            abort(403, 'Campagne indisponible.');
        }

        $candidates = $campaign->candidates()
            ->where('status', 'accepted')
            ->orderByRaw('sort_order = 0')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $topCandidates = $campaign->candidates()->where('status', 'accepted')->orderByDesc('votes_count')->limit(3)->get();

        return view('campaigns.show', compact('campaign', 'candidates', 'topCandidates'));
    }

    public function create()
    {
        return view('campaigns.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:5000',
            'video' => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo|max:20000'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('campaigns', 'public');
        }

        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('campaigns_videos', 'public');
        }

        Campaign::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'image_path' => $imagePath,
            'video_path' => $videoPath,
            'status' => 'pending' // Admin must validate
        ]);

        return redirect('/dashboard')->with('success', 'Campaign submitted for validation.');
    }

    public function manage($slug)
    {
        $campaign = Campaign::where('slug', $slug)->where('user_id', Auth::id())->firstOrFail();
        $allCandidates = $campaign->allCandidates()->latest()->get();
        $votesCount = $campaign->votes()->count();
        $results = $campaign->candidates()
            ->withCount('votes')
            ->orderBy('votes_count', 'desc')
            ->get();

        return view('campaigns.manage', compact('campaign', 'allCandidates', 'votesCount', 'results'));
    }

    public function join(Request $request)
    {
        $code = strtoupper($request->code);
        $campaign = Campaign::where('code', $code)->first();

        if ($campaign) {
            return redirect()->route('campaigns.show', $campaign->slug);
        }

        return back()->withErrors(['code' => 'Code de campagne invalide.']);
    }

    public function updateSettings(Request $request, $slug)
    {
        $campaign = Campaign::where('slug', $slug)->where('user_id', Auth::id())->firstOrFail();
        
        $request->validate([
            'status' => 'required|in:active,paused,ended',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
        ]);

        $campaign->update([
            'status' => $request->status,
            'start_at' => $request->filled('start_at') ? $request->start_at : null,
            'end_at' => $request->filled('end_at') ? $request->end_at : null,
        ]);

        return back()->with('success', 'Paramètres temporels mis à jour avec succès.');
    }
}
