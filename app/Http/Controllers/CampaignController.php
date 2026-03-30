<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\CampaignVisit;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewCampaignMail;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->has('search')) {
            session(['last_campaigns_index' => $request->fullUrl()]);
        }
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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $campaigns = $query->latest()->paginate($perPage);
        return view('campaigns.index', compact('campaigns'));
    }

    public function show(Request $request, $slug)
    {
        $campaign = Campaign::where('slug', $slug)
            ->orWhere('code', strtoupper($slug))
            ->firstOrFail();

        if ($campaign->status !== 'active' && (!Auth::check() || (Auth::id() !== $campaign->user_id && !Auth::user()->isAdmin()))) {
            abort(403, 'Campagne indisponible.');
        }

        $candidateQuery = $campaign->candidates()
            ->where('status', 'accepted');

        if ($request->filled('search')) {
            $search = $request->search;
            $candidateQuery->where('name', 'like', "%{$search}%");
        }

        $candidates = $candidateQuery
            ->orderByRaw('sort_order = 0')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $topCandidates = $campaign->candidates()->where('status', 'accepted')->orderByDesc('votes_count')->limit(3)->get();

        // Track Visit & View
        $visit = CampaignVisit::firstOrCreate([
            'campaign_id' => $campaign->id,
            'ip_address' => request()->ip(),
            'session_id' => Session::getId(),
        ]);

        if (!$visit->wasRecentlyCreated) {
            $visit->increment('hits');
        } else if (Auth::check()) {
            $visit->update(['user_id' => Auth::id()]);
        }

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
            'image' => 'nullable|image|max:50000',
            'video' => 'nullable|file|mimes:mp4,webm,mkv,avi,mov,mpeg,ogg|max:100000',
            'vote_price' => 'required|integer|min:0',
            'bank_account' => 'nullable|string|max:255'
        ], [
            'name.required' => 'Le nom du scrutin est obligatoire.',
            'image.image' => 'L\'affiche doit être une image valide.',
            'image.max' => 'L\'image ne doit pas dépasser 50 Mo.',
            'video.max' => 'La vidéo ne doit pas dépasser 100 Mo.',
            'video.mimes' => 'Le format de la vidéo n\'est pas supporté (Formats acceptés : MP4, WEBM, MKV, AVI, MOV).',
            'image.uploaded' => 'L\'image est trop volumineuse pour être traitée.',
            'video.uploaded' => 'La vidéo est trop volumineuse pour être traitée.',
        ]);





        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('campaigns', 'public');
        }

        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('campaigns_videos', 'public');
        }

        \Illuminate\Support\Facades\Log::info('Création de campagne initiée.', [
            'name' => $request->name,
            'user_id' => Auth::id()
        ]);

        $campaign = Campaign::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'image_path' => $imagePath,
            'video_path' => $videoPath,
            'vote_price' => $request->vote_price,
            'bank_account' => $request->bank_account,
            'status' => 'pending' // Admin must validate
        ]);

        \Illuminate\Support\Facades\Log::info('Campagne créée avec succès.', ['id' => $campaign->id]);

        // Notify Super Admin
        $superAdminEmail = env('SUPER_ADMIN_EMAIL', 'mantinoubello123@gmail.com');
        try {
            Mail::to($superAdminEmail)->send(new NewCampaignMail($campaign));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur lors de l\'envoi du mail super admin.', [
                'error' => $e->getMessage()
            ]);
        }

        return redirect('/dashboard')->with('success', 'Le scrutin a été soumis avec succès et est en attente de validation par l\'administration.');

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
            return redirect()->route('candidates.apply', $campaign->slug);
        }

        return back()->withErrors(['code' => 'Code de campagne invalide.']);
    }

    public function edit($slug)
    {
        $campaign = Campaign::where('slug', $slug)->where('user_id', Auth::id())->firstOrFail();
        return view('campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, $slug)
    {
        $campaign = Campaign::where('slug', $slug)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:50000',
            'video' => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/mpeg,video/ogg,video/webm,video/x-matroska|max:100000',
            'vote_price' => 'required|integer|min:0',
            'bank_account' => 'nullable|string|max:255'
        ]);

        if ($request->hasFile('image')) {
            if ($campaign->image_path) {
                Storage::disk('public')->delete($campaign->image_path);
            }
            $campaign->image_path = $request->file('image')->store('campaigns', 'public');
        }

        if ($request->hasFile('video')) {
            if ($campaign->video_path) {
                Storage::disk('public')->delete($campaign->video_path);
            }
            $campaign->video_path = $request->file('video')->store('campaigns_videos', 'public');
        }

        $campaign->update([
            'name' => $request->name,
            'description' => $request->description,
            'vote_price' => $request->vote_price,
            'bank_account' => $request->bank_account,
        ]);

        return redirect()->route('dashboard')->with('success', 'Scrutin mis à jour avec succès.');
    }

    public function destroy($slug)
    {
        $campaign = Campaign::where('slug', $slug)->where('user_id', Auth::id())->firstOrFail();

        if ($campaign->image_path) {
            Storage::disk('public')->delete($campaign->image_path);
        }
        if ($campaign->video_path) {
            Storage::disk('public')->delete($campaign->video_path);
        }

        $campaign->delete();

        return redirect()->route('dashboard')->with('success', 'Le scrutin a été définitivement annulé.');
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
