<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateController extends Controller
{
    public function show($slug, $id)
    {
        $campaign = Campaign::where('slug', '=', $slug)->firstOrFail();
        $candidate = Candidate::where('campaign_id', '=', $campaign->id)
            ->where('id', $id)
            ->where('status', 'accepted')
            ->firstOrFail();

        return view('candidates.show', compact('campaign', 'candidate'));
    }

    public function apply($slug)
    {
        $campaign = Campaign::where('slug', '=', $slug)->firstOrFail();
        return view('candidates.apply', compact('campaign'));
    }

    public function storeApply(Request $request, $slug)
    {
        $campaign = Campaign::where('slug', '=', $slug)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_path' => 'nullable|image|max:5000',
            'video' => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo|max:20000'
        ]);

        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('candidates', 'public');
        }

        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('candidates_videos', 'public');
        }

        Candidate::create([
            'campaign_id' => $campaign->id,
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'image_path' => $imagePath,
            'video_path' => $videoPath,
            'status' => 'pending'
        ]);

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
            'sort_order' => 'nullable|integer'
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'accepted') {
            if ($request->has('sort_order') && $request->sort_order !== null) {
                $data['sort_order'] = $request->sort_order;
            } else {
                // Auto-increment logic
                $maxOrder = Candidate::where('campaign_id', $campaign->id)->max('sort_order');
                $data['sort_order'] = $maxOrder + 1;
            }
        }

        $candidate->update($data);

        return back()->with('success', 'Candidature mise à jour avec l\'ordre défini.');
    }
}
