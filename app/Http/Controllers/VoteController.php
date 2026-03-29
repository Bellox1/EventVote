<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Candidate;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function cast(Request $request, $slug)
    {
        $campaign = Campaign::where('slug', $slug)->where('status', 'active')->firstOrFail();
        $candidateId = $request->candidate_id;

        $candidate = Candidate::where('campaign_id', $campaign->id)
            ->where('id', $candidateId)
            ->where('status', 'accepted')
            ->firstOrFail();

        // Infinite voting allowed as per user request

        Vote::create([
            'campaign_id' => $campaign->id,
            'candidate_id' => $candidate->id,
            'user_id' => Auth::id(),
            'session_id' => !Auth::check() ? session()->getId() : null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        $candidate->increment('votes_count');

        return back()->with('success', 'Votre vote a été pris en compte !');
    }
}
