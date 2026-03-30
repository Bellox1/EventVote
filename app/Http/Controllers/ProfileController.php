<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Mail\DeleteAccountOTPMail;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        $completion = 0;
        if (!empty($user->name)) $completion += 33;
        if (!empty($user->email)) $completion += 33;
        if (!empty($user->phone)) $completion += 34;
        
        return view('profile', compact('user', 'completion'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($request->only('name', 'email', 'phone'));

        return back()->with('success', 'Informations du profil mises à jour.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'current_password.current_password' => 'Votre mot de passe actuel est incorrect.',
            'password.confirmed' => 'La confirmation du nouveau mot de passe ne correspond pas.'
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Votre mot de passe a été modifié.');
    }

    public function deleteRequest(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [
            'password.current_password' => 'Le mot de passe saisi est incorrect.'
        ]);

        $otp = rand(100000, 999999);
        session(['delete_account_otp' => $otp, 'delete_account_otp_time' => now()]);

        try {
            Mail::to(Auth::user()->email)->send(new DeleteAccountOTPMail($otp));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Impossible d\'envoyer le mail de confirmation. Vérifiez votre connexion.'], 500);
        }

        return response()->json(['success' => true, 'message' => 'Le code OTP a été envoyé sur votre email.']);
    }

    public function deleteConfirm(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'numeric'],
        ]);

        if (session('delete_account_otp') != $request->otp) {
            return response()->json(['success' => false, 'message' => 'Le code OTP est incorrect.'], 422);
        }

        // Check if OTP expired (optional, e.g. 10 mins)
        $user = Auth::user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['success' => true, 'redirect' => route('welcome')]);
    }
}
