<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!Auth::user()->isAdmin()) abort(403);
        
        $pendingCampaigns = Campaign::where('status', 'pending')->latest()->get();
        $allCampaigns = Campaign::latest()->get();
        $usersCount = User::count();
        $votesCount = User::count(); // Just placeholder for logic
        
        return view('admin.dashboard', compact('pendingCampaigns', 'allCampaigns', 'usersCount'));
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
