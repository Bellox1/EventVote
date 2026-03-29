<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\VoteController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Campaign;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    $activeCampaigns = Campaign::where('status', 'active')->latest()->take(6)->get();
    return view('welcome', compact('activeCampaigns'));
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// User restricted
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $contextLabel = 'Mes Sessions';
        
        // 1. Created campaigns
        $displayCampaigns = $user->campaigns()->latest()->get();
        
        // 2. If none created, check participation
        if ($displayCampaigns->isEmpty()) {
            $votedCampaignIds = $user->votes()->pluck('campaign_id')->unique();
            $displayCampaigns = \App\Models\Campaign::whereIn('id', $votedCampaignIds)->latest()->get();
            $contextLabel = 'Participations';
        }
        
        // 3. If no participation, show all active
        if ($displayCampaigns->isEmpty()) {
            $displayCampaigns = \App\Models\Campaign::where('status', 'active')->latest()->get();
            $contextLabel = 'Toutes les Sessions';
        }

        $myVotes = $user->votes()->with(['campaign', 'candidate'])->latest()->get();
        return view('dashboard', [
            'myCampaigns' => $displayCampaigns,
            'myVotes' => $myVotes,
            'contextLabel' => $contextLabel
        ]);
    })->name('dashboard');

    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{slug}/manage', [CampaignController::class, 'manage'])->name('campaigns.manage');
    Route::put('/campaigns/{slug}/settings', [CampaignController::class, 'updateSettings'])->name('campaigns.settings');
    
    // Candidate applications
    Route::get('/campaigns/{slug}/apply', [CandidateController::class, 'apply'])->name('candidates.apply');
    Route::post('/campaigns/{slug}/apply', [CandidateController::class, 'storeApply']);
    Route::post('/candidate-applications/{id}', [CandidateController::class, 'manageApplication'])->name('candidate-applications.manage');
    Route::delete('/candidates/{id}/archive', [CandidateController::class, 'destroy'])->name('candidates.archive');
    Route::post('/candidates/{id}/restore', [CandidateController::class, 'restore'])->name('candidates.restore');
    Route::delete('/candidates/{id}/force', [CandidateController::class, 'forceDestroy'])->name('candidates.force');
});

// Campaign and Vote
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{slug}', [CampaignController::class, 'show'])->name('campaigns.show');
Route::post('/campaigns/join', [CampaignController::class, 'join'])->name('campaigns.join');
Route::post('/campaigns/{slug}/vote', [VoteController::class, 'cast'])->name('vote.cast');

Route::get('/campaigns/{slug}/candidates/{id}', [CandidateController::class, 'show'])->name('candidates.show');

// Admin restricted
Route::middleware(['auth', 'admin'])->group(function() {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/campaigns/{id}', [AdminController::class, 'manageCampaign'])->name('admin.campaigns.manage');
    Route::post('/admin/users/{id}/ban', [AdminController::class, 'banUser'])->name('admin.users.ban');
});
