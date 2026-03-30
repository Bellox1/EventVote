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
            'campaign_id' => 'required|exists:campaigns,id',
            'votes_count' => 'required|integer|min:1|max:100',
        ]);

        $candidate = Candidate::findOrFail($request->candidate_id);
        $campaign = Campaign::findOrFail($request->campaign_id);
        
        $amount = $campaign->vote_price * $request->votes_count;
        $user = Auth::user();

        try {
            // Create transaction using FedaPay SDK
            $transaction = Transaction::create([
                'description' => "Vote pour " . $candidate->name . " - " . $request->votes_count . " votes",
                'amount' => (int) $amount,
                'currency' => ['iso' => 'XOF'],
                'callback_url' => route('payment.callback'),
                'customer' => [
                    'firstname' => $user->name,
                    'email' => $user->email,
                    'phone_number' => [
                        'number' => preg_replace('/[^0-9]/', '', $user->phone ?? '90000000'),
                        'country' => 'bj' // Assuming Benin
                    ]
                ]
            ]);

            $token = $transaction->generateToken();
            $url = $token->url;

            // Create a pending vote record
            Vote::create([
                'campaign_id' => $campaign->id,
                'candidate_id' => $candidate->id,
                'user_id' => $user->id,
                'votes_count' => $request->votes_count,
                'amount' => $amount,
                'payment_id' => $transaction->id,
                'status' => 'pending',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return redirect()->away($url);

        } catch (\Exception $e) {
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
