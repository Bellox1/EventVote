@extends('layouts.app')

@section('title', 'Nouveau Mot de Passe')

@section('content')
    <div style="max-width: 600px; margin: 60px auto; padding: 0 20px;">
        <div class="card"
            style="border-bottom: 6px solid var(--accent); padding: 80px 60px; box-shadow: var(--shadow-hard);">
            <div style="text-align: center; margin-bottom: 60px;">
                <div
                    style="font-family: 'Cormorant Garamond', serif; font-size: 3rem; color: var(--primary); letter-spacing: 0.05em; margin-bottom: 16px; font-weight: 300;">
                    SÉCURI <span style="font-weight: 500; color: var(--accent);">•</span> TÉ
                </div>
                <div class="ornament" style="margin: 0 auto 32px;"></div>
                <h1
                    style="font-size: 1.4rem; color: var(--primary); text-transform: uppercase; letter-spacing: 0.3em; font-weight: 400; font-family: 'Jost', sans-serif;">
                    Nouveau Mot de Passe</h1>
                <p style="color: var(--text-dim); font-size: 0.9rem; margin-top: 20px; font-family: 'Jost', sans-serif; line-height: 1.6;">
                    Veuillez définir votre nouvelle clé d'accès sécurisée.
                </p>
            </div>

            <form method="POST" action="{{ route('password.update') }}" style="display: flex; flex-direction: column; gap: 35px;">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group" x-data="{ show: false }">
                    <label
                        style="display: block; font-weight: 600; font-size: 0.7rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.25em; opacity: 0.8;">Nouveau Mot de Passe</label>
                    <div style="position: relative;">
                        <input :type="show ? 'text' : 'password'" name="password" required autofocus placeholder="••••••••••••"
                            style="width: 100%; height: 60px; padding: 0 0; background: transparent; border: none; border-bottom: 1px solid var(--border); font-family: 'Jost', sans-serif; font-size: 1.1rem; color: var(--primary); letter-spacing: 0.1em; transition: all 0.4s; outline: none;"
                            onfocus="this.style.borderBottomColor='var(--primary)'; this.style.paddingLeft='10px';"
                            onblur="this.style.borderBottomColor='var(--border)'; this.style.paddingLeft='0';">
                        <button type="button" @click="show = !show" 
                            style="position: absolute; right: 0; top: 18px; background: none; border: none; color: var(--primary); cursor: pointer; opacity: 0.5;">
                            <svg x-show="!show" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-cloak><path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L5.93 5.93m7.444 7.444l3.95 3.95M13.475 4.835A9.959 9.959 0 0112 5c4.477 0 8.268 2.943 9.542 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <span style="color: #bc3e3e; font-size: 0.8rem; margin-top: 8px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" x-data="{ show: false }">
                    <label
                        style="display: block; font-weight: 600; font-size: 0.7rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.25em; opacity: 0.8;">Confirmer le Mot de Passe</label>
                    <div style="position: relative;">
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" required placeholder="••••••••••••"
                            style="width: 100%; height: 60px; padding: 0 0; background: transparent; border: none; border-bottom: 1px solid var(--border); font-family: 'Jost', sans-serif; font-size: 1.1rem; color: var(--primary); letter-spacing: 0.1em; transition: all 0.4s; outline: none;"
                            onfocus="this.style.borderBottomColor='var(--primary)'; this.style.paddingLeft='10px';"
                            onblur="this.style.borderBottomColor='var(--border)'; this.style.paddingLeft='0';">
                        <button type="button" @click="show = !show" 
                            style="position: absolute; right: 0; top: 18px; background: none; border: none; color: var(--primary); cursor: pointer; opacity: 0.5;">
                            <svg x-show="!show" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="show" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" x-cloak><path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L5.93 5.93m7.444 7.444l3.95 3.95M13.475 4.835A9.959 9.959 0 0112 5c4.477 0 8.268 2.943 9.542 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </div>
                </div>

                <div style="margin-top: 10px;">
                    <button type="submit" class="btn"
                        style="width: 100%; height: 70px; background: var(--primary); color: white; border: none; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.3em; text-transform: uppercase; cursor: pointer; transition: all 0.4s; border-radius: 4px;"
                        onmouseover="this.style.background='var(--primary-light)'; this.style.transform='translateY(-2px)';"
                        onmouseout="this.style.background='var(--primary)'; this.style.transform='translateY(0)';"
                    >RÉINITIALISER LE MOT DE PASSE</button>
                </div>
            </form>

            <div style="margin-top: 50px; text-align: center; border-top: 1px solid var(--border); padding-top: 30px;">
                <a href="{{ route('login') }}"
                    style="color: var(--accent); font-weight: 700; text-decoration: none; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.15em; transition: opacity 0.3s;"
                    onmouseover="this.style.opacity='0.6'" onmouseout="this.style.opacity='1'">&larr; Retour à l'accueil</a>
            </div>
        </div>
    </div>
@endsection
