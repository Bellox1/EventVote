<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email'], [
            'email.required' => 'L\'adresse e-mail est requise.',
            'email.email' => 'L\'adresse e-mail n\'est pas valide.',
            'email.exists' => 'Cette adresse e-mail n\'est pas reconnue.',
        ]);


        $email = $request->email;
        $otp = rand(100000, 999999);

        // Delete any existing tokens for this email
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Save OTP
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $otp, // We store the numeric OTP here
            'created_at' => Carbon::now()
        ]);

        // Send OTP by mail
        Mail::to($email)->send(new OtpMail($otp));

        return redirect()->route('password.verify-otp', ['email' => $email])
            ->with('success', 'Un code OTP a été envoyé à votre adresse e-mail.');
    }

    public function showVerifyOtp(Request $request)
    {
        $email = $request->email;
        return view('auth.verify-otp', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric'
        ], [
            'email.required' => 'L\'adresse e-mail est requise.',
            'email.email' => 'L\'adresse e-mail n\'est pas valide.',
            'email.exists' => 'Cette adresse e-mail n\'est pas reconnue.',
            'otp.required' => 'Le code OTP est requis.',
            'otp.numeric' => 'Le code OTP doit être composé de chiffres.',
        ]);


        $reset = DB::table('password_reset_tokens')
            ->where('email', '=', $request->email)
            ->where('token', '=', $request->otp)
            ->first();

        if (!$reset) {
            return back()->withErrors(['otp' => 'Le code OTP est invalide ou expiré.']);
        }

        if (Carbon::parse($reset->created_at)->addMinutes(15)->isPast()) {
            return back()->withErrors(['otp' => 'Le code OTP est expiré.']);
        }

        // Redirect to password reset form with email and OTP as identifier
        return redirect()->route('password.reset-form', ['email' => $request->email, 'token' => $request->otp]);
    }

    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'email' => $request->email,
            'token' => $request->token
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.required' => 'L\'adresse e-mail est requise.',
            'email.email' => 'L\'adresse e-mail n\'est pas valide.',
            'email.exists' => 'Cette adresse e-mail n\'est pas reconnue.',
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit faire au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);


        $reset = DB::table('password_reset_tokens')
            ->where('email', '=', $request->email)
            ->where('token', '=', $request->token)
            ->first();

        if (!$reset) {
            return redirect()->route('password.request')->withErrors(['email' => 'L\'opération est invalide.']);
        }

        if (Carbon::parse($reset->created_at)->addMinutes(15)->isPast()) {
            return redirect()->route('password.request')->withErrors(['email' => 'L\'opération a expiré.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Clear token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Votre mot de passe a été réinitialisé avec succès.');
    }
}
