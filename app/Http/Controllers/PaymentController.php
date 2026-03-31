<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vote;
use App\Models\Candidate;
use App\Models\Campaign;
use Illuminate\Support\Facades\Log;
use FedaPay\FedaPay;
use FedaPay\Transaction;

class PaymentController extends Controller
{
    public function __construct()
    {
        FedaPay::setApiKey(env('FEDAPAY_SECRET_KEY'));
        $isSandbox = filter_var(env('FEDAPAY_SANDBOX', true), FILTER_VALIDATE_BOOLEAN);
        FedaPay::setEnvironment($isSandbox ? 'sandbox' : 'live');
    }

    public function initiatePayment(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'campaign_id'  => 'required|exists:campaigns,id',
            'votes_count'  => 'required|integer|min:1|max:100',
        ]);

        $candidate = Candidate::findOrFail($request->candidate_id);
        $campaign  = Campaign::findOrFail($request->campaign_id);
        $amount    = $campaign->vote_price * $request->votes_count;
        $user      = Auth::user(); // peut être null (vote anonyme)

        // Infos client pour FedaPay (anonyme si pas connecté)
        $customerFirstname = $user ? $user->name  : 'Électeur';
        $customerEmail     = $user ? $user->email : 'anonyme@vote-platform.bj';
        $customerPhone     = $user ? preg_replace('/[^0-9]/', '', $user->phone ?? '90000000') : '90000000';

        try {
            $transaction = Transaction::create([
                'description' => "Vote pour " . $candidate->name . " - " . $request->votes_count . " vote(s)",
                'amount'      => (int) $amount,
                'currency'    => ['iso' => 'XOF'],
                'callback_url' => route('payment.callback'),
                'customer'    => [
                    'firstname'    => $customerFirstname,
                    'email'        => $customerEmail,
                    'phone_number' => [
                        'number'  => $customerPhone,
                        'country' => 'bj'
                    ]
                ]
            ]);

            $token = $transaction->generateToken();
            $url   = $token->url;

            // Enregistrer le vote en attente (user_id = null si anonyme)
            Vote::create([
                'campaign_id'  => $campaign->id,
                'candidate_id' => $candidate->id,
                'user_id'      => $user?->id,
                'votes_count'  => $request->votes_count,
                'amount'       => $amount,
                'payment_id'   => $transaction->id,
                'status'       => 'pending',
            ]);

            return redirect()->away($url);

        } catch (\Exception $e) {
            Log::error('FedaPay error: ' . $e->getMessage());
            return back()->with('error', 'Erreur FedaPay : ' . $e->getMessage());
        }
    }

    public function webhook(Request $request)
    {
        // Simple manual check for now as requested
        $id = $request->input('id');
        $status = $request->input('status');

        if ($status === 'approved') {
            $vote = Vote::where('payment_id', $id)->first();
            if ($vote && $vote->status === 'pending') {
                $vote->update(['status' => 'confirmed']);
                // Increment candidate votes_count
                $vote->candidate()->increment('votes_count', $vote->votes_count);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function callback(Request $request)
    {
        return redirect()->route('dashboard')->with('success', 'Votre session de vote a été initiée. Vos voix seront comptabilisées dès confirmation du paiement.');
    }
}
