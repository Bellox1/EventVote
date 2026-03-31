<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\PaymentController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Campaign;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    $activeCampaigns = Campaign::where('status', '=', 'active')
        ->withCount(['candidates', 'votes'])
        ->latest()
        ->take(6)
        ->get();
    return view('welcome', compact('activeCampaigns'));
})->name('welcome');

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
        $myPending = $myCreated->whereIn('status', ['pending', 'rejected']);
        $myActive = $myCreated->whereIn('status', ['active', 'paused', 'ended']);
        
        // 2. Mes Dossiers (Candidatures envoyées)
        $myCandidacies = \App\Models\Candidate::where('user_id', '=', $user->id)
            ->with(['campaign' => function($q) {
                $q->withCount('votes');
            }])
            ->withCount('votes')
            ->latest()
            ->get();

        // 3. Participations (en tant qu'électeur)
        $participations = collect();
        if ($myActive->isEmpty() && $myCandidacies->isEmpty()) {
            $votedCampaignIds = $user->votes()->pluck('campaign_id')->unique();
            $participations = \App\Models\Campaign::whereIn('id', $votedCampaignIds)->where('status', '=', 'active')->latest()->get();
            if ($participations->isNotEmpty()) {
                $contextLabel = 'Participations';
            }
        }

        $myVotes = $user->votes()->with(['campaign', 'candidate'])->latest()->get();
        return view('dashboard', [
            'myPending' => $myPending,
            'myActive' => $myActive,
            'myCandidacies' => $myCandidacies,
            'participations' => $participations,
            'myVotes' => $myVotes,
            'contextLabel' => $contextLabel
        ]);
    })->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/delete-request', [ProfileController::class, 'deleteRequest'])->name('profile.delete.request');
    Route::post('/profile/delete-confirm', [ProfileController::class, 'deleteConfirm'])->name('profile.delete.confirm');

    // Candidatures par le candidat lui-même
    Route::get('/candidacies/{id}/edit', [CandidateController::class, 'editApply'])->name('candidates.edit-apply');
    Route::post('/candidacies/{id}/update', [CandidateController::class, 'updateApply'])->name('candidates.update-apply');
    Route::delete('/candidacies/{id}', [CandidateController::class, 'destroyApply'])->name('candidates.destroy-apply');
    Route::get('/candidacies/{id}/stats', [CandidateController::class, 'stats'])->name('candidates.stats');

    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{slug}/manage', [CampaignController::class, 'manage'])->name('campaigns.manage');
    Route::get('/campaigns/{slug}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{slug}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{slug}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
    Route::put('/campaigns/{slug}/settings', [CampaignController::class, 'updateSettings'])->name('campaigns.settings');
    
    // Candidate applications
    Route::get('/campaigns/{slug}/apply', [CandidateController::class, 'apply'])->name('candidates.apply');
    Route::post('/campaigns/{slug}/apply', [CandidateController::class, 'storeApply'])->name('candidates.store-apply');
    Route::post('/candidate-applications/{id}', [CandidateController::class, 'manageApplication'])->name('candidate-applications.manage');
    Route::delete('/candidates/{id}/archive', [CandidateController::class, 'destroy'])->name('candidates.archive');
    Route::post('/candidates/{id}/restore', [CandidateController::class, 'restore'])->name('candidates.restore');
    Route::delete('/candidates/{id}/force', [CandidateController::class, 'forceDestroy'])->name('candidates.force');
    Route::post('/campaigns/join', [CampaignController::class, 'join'])->name('campaigns.join');
});

// Campaign and Vote
Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{slug}', [CampaignController::class, 'show'])->name('campaigns.show');
Route::get('/api/campaigns/{slug}/stats', [CampaignController::class, 'getStats'])->name('api.campaigns.stats');
Route::post('/campaigns/{slug}/vote', [VoteController::class, 'cast'])->name('vote.cast');

Route::get('/campaigns/{slug}/candidates/{id}', [CandidateController::class, 'show'])->name('candidates.show');
Route::get('/candidates/{id}/stats', [CandidateController::class, 'stats'])->name('candidates.stats');

// Admin restricted
Route::middleware(['auth', 'admin'])->group(function() {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/campaigns/{id}', [AdminController::class, 'manageCampaign'])->name('admin.campaigns.manage');
    Route::post('/admin/users/{id}/ban', [AdminController::class, 'banUser'])->name('admin.users.ban');
});

// FedaPay Routes
Route::post('/payment/initiate', [PaymentController::class, 'initiatePayment'])->name('payment.initiate');
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');
