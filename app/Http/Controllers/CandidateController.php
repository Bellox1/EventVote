<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Candidate;
use App\Models\CampaignVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\CandidateStatusMail;

class CandidateController extends Controller
{
    public function show($slug, $id)
    {
        $campaign = Campaign::where('slug', '=', $slug)->firstOrFail();
        $candidate = Candidate::where('campaign_id', '=', $campaign->id)
            ->where('id', '=', $id)
            ->where('status', '=', 'accepted')
            ->firstOrFail();

        // Track candidate view/visit
        $visit = CampaignVisit::firstOrCreate([
            'campaign_id'  => $campaign->id,
            'candidate_id' => $candidate->id,
            'ip_address'   => request()->ip(),
            'session_id'   => Session::getId(),
        ]);
        if (!$visit->wasRecentlyCreated) {
            $visit->increment('hits'); // +1 vue (retour)
        } elseif (Auth::check()) {
            $visit->update(['user_id' => Auth::id()]);
        }

        return view('candidates.show', compact('campaign', 'candidate'));
    }

    public function apply($slug)
    {
        $campaign = Campaign::where('slug', '=', $slug)->firstOrFail();
        
        // Vérifier si l'utilisateur a déjà postulé
        if (Candidate::where('campaign_id', '=', $campaign->id)->where('user_id', '=', Auth::id())->exists()) {
            return redirect()->route('campaigns.show', $campaign->slug)
                ->with('error', 'Vous avez déjà déposé une candidature pour ce scrutin.');
        }

        return view('candidates.apply', compact('campaign'));
    }

    public function storeApply(Request $request, $slug)
    {
        $campaign = Campaign::where('slug', '=', $slug)->firstOrFail();

        // Sécurité supplémentaire
        if (Candidate::where('campaign_id', '=', $campaign->id)->where('user_id', '=', Auth::id())->exists()) {
            return redirect()->route('campaigns.show', $campaign->slug)
                ->with('error', 'Vous avez déjà déposé une candidature pour ce scrutin.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'nullable|image|max:10000',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv,quicktime,webm,mkv|max:50000'
        ], [
            'video.mimes' => 'Le format de la vidéo doit être : mp4, webm, mov, avi ou mkv.',
            'video.max' => 'La vidéo est trop lourde (maximum 50 Mo).',
            'image_path.image' => 'Le portrait doit être une image valide.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('candidates', 'public');
        }

        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('candidates_videos', 'public');
        }

        $candidate = Candidate::create([
            'campaign_id' => $campaign->id,
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'image_path' => $imagePath,
            'video_path' => $videoPath,
            'status' => 'pending'
        ]);

        try {
            // Mail au candidat
            \Illuminate\Support\Facades\Mail::to(Auth::user()->email)->send(new \App\Mail\CandidateAppliedMail($candidate));
            
            // Mail au créateur
            \Illuminate\Support\Facades\Mail::to($campaign->creator->email)->send(new \App\Mail\NewCandidateNotificationMail($candidate));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erreur envoi notifications candidature : ' . $e->getMessage());
        }

        return redirect()->route('campaigns.show', $slug)->with('success', 'Votre candidature a été soumise au créateur de la campagne.');
    }

    public function manageApplication(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);
        $campaign = $candidate->campaign;

        if ($campaign->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected',
            'sort_order' => 'nullable|integer',
            'rejection_reason' => 'nullable|string'
        ]);

        $data = ['status' => $request->status];
        
        if ($request->status === 'rejected') {
            $data['rejection_reason'] = $request->rejection_reason;
        }

        if ($request->status === 'accepted') {
            if ($request->has('sort_order') && $request->sort_order !== null) {
                $data['sort_order'] = $request->sort_order;
            } else {
                // Auto-increment logic
                $maxOrder = Candidate::where('campaign_id', '=', $campaign->id)->max('sort_order');
                $data['sort_order'] = $maxOrder + 1;
            }
        }

        $candidate->update($data);

        // Envoyer un email de notification au candidat via son User rattaché
        if ($candidate->user) {
            Mail::to($candidate->user->email)->send(new CandidateStatusMail($candidate, $request->status));
        }

        return back()->with('success', 'Candidature mise à jour et candidat notifié.');
    }

    public function destroy($id)
    {
        $candidate = Candidate::findOrFail($id);
        
        if ($candidate->campaign->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $candidate->delete();
        return back()->with('success', 'Candidat archivé avec succès.');
    }

    public function restore($id)
    {
        $candidate = Candidate::withTrashed()->findOrFail($id);
        
        if ($candidate->campaign->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $candidate->restore();
        return back()->with('success', 'Candidat restauré avec succès.');
    }

    public function forceDestroy($id)
    {
        $candidate = Candidate::withTrashed()->findOrFail($id);
        
        if ($candidate->campaign->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
        
        // Optionally delete files from storage here
        $candidate->forceDelete();
        return back()->with('success', 'Candidat supprimé définitivement.');
    }

    public function editApply($id)
    {
        $candidate = Candidate::where('user_id', '=', Auth::id())->findOrFail($id);
        if ($candidate->status !== 'pending') {
            return back()->with('error', 'Modification impossible.');
        }
        $campaign = $candidate->campaign;
        return view('candidates.edit', compact('candidate', 'campaign'));
    }

    public function updateApply(Request $request, $id)
    {
        $candidate = Candidate::where('user_id', '=', Auth::id())->findOrFail($id);
        if ($candidate->status !== 'pending') {
            return back()->with('error', 'Modification impossible.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image_path' => 'nullable|image|max:10000',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv,quicktime,webm,mkv|max:50000'
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        if ($request->hasFile('image_path')) {
            $data['image_path'] = $request->file('image_path')->store('candidates', 'public');
        }

        if ($request->hasFile('video')) {
            $data['video_path'] = $request->file('video')->store('candidates_videos', 'public');
        }

        $candidate->update($data);

        return redirect()->route('dashboard')->with('success', 'Candidature mise à jour.');
    }

    public function destroyApply($id)
    {
        $candidate = Candidate::where('user_id', '=', Auth::id())->findOrFail($id);
        $candidate->delete();
        return redirect()->route('dashboard')->with('success', 'Candidature annulée.');
    }

    public function stats($id)
    {
        $candidate = Candidate::where('user_id', '=', Auth::id())->where('status', '=', 'accepted')->findOrFail($id);
        $campaign = $candidate->campaign;
        
        $competitors = Candidate::where('campaign_id', '=', $campaign->id)
            ->where('status', '=', 'accepted')
            ->withCount('votes')
            ->orderBy('votes_count', 'desc')
            ->get();
            
        $myRank = 1;
        foreach($competitors as $index => $c) {
            if($c->id === $candidate->id) {
                $myRank = $index + 1;
                break;
            }
        }

        return view('candidates.stats', compact('candidate', 'campaign', 'competitors', 'myRank'));
    }
}
