<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ForgotPasswordController;

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

// Password Reset Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.email');
Route::get('/verify-otp', [ForgotPasswordController::class, 'showVerifyOtp'])->name('password.verify-otp');
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verify-otp-post');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset-form');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// User restricted
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $contextLabel = 'Mes Sessions';
        
        // 1. My created campaigns
        $myCreated = $user->campaigns()->latest()->get();
        $myPending = $myCreated->where('status', 'pending');
        $myActive = $myCreated->where('status', '!=', 'pending');
        
        // 2. Participations (if active ones are empty)
        $participations = collect();
        if ($myActive->isEmpty()) {
            $votedCampaignIds = $user->votes()->pluck('campaign_id')->unique();
            $participations = \App\Models\Campaign::whereIn('id', $votedCampaignIds)->where('status', 'active')->latest()->get();
            if ($participations->isNotEmpty()) {
                $contextLabel = 'Participations';
            }
        }

        $myVotes = $user->votes()->with(['campaign', 'candidate'])->latest()->get();
        return view('dashboard', [
            'myActive' => $myActive,
            'myPending' => $myPending,
            'participations' => $participations,
            'myVotes' => $myVotes,
            'contextLabel' => $contextLabel
        ]);
    })->name('dashboard');


    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{slug}/manage', [CampaignController::class, 'manage'])->name('campaigns.manage');
    Route::get('/campaigns/{slug}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{slug}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{slug}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
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
