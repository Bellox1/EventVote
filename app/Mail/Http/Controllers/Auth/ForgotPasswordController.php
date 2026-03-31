<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $otp = rand(100000, 999999);
        
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $otp, // We store the OTP as the token
                'created_at' => Carbon::now()
            ]
        );

        // Send Email (Simplified for now, assuming mail is configured)
        try {
            Mail::send('emails.otp', ['otp' => $otp], function($message) use ($request) {
                $message->to($request->email);
                $message->subject('Votre code de récupération - VOTE ÉVÉNEMENTIELLE');
            });
        } catch (\Exception $e) {
            // Log error or handle it
        }

        return redirect()->route('password.reset.form', ['email' => $request->email])
            ->with('status', 'Un code de vérification a été envoyé à votre adresse email.');
    }

    public function showResetForm(Request $request)
    {
        return view('auth.passwords.reset', ['email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric|digits:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->otp)
            ->first();

        if (!$record || Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return back()->withErrors(['otp' => 'Code invalide ou expiré.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Votre mot de passe a été réinitialisé avec succès.');
    }
}
