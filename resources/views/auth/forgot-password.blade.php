@extends('layouts.app')

@section('title', 'Récupération de Compte')

@section('content')
    <div style="max-width: 600px; margin: 60px auto; padding: 0 20px;">
        <div class="card"
            style="border-bottom: 6px solid var(--accent); padding: 80px 60px; box-shadow: var(--shadow-hard);">
            <div style="text-align: center; margin-bottom: 60px;">
                <div
                    style="font-family: 'Cormorant Garamond', serif; font-size: 3rem; color: var(--primary); letter-spacing: 0.05em; margin-bottom: 16px; font-weight: 300;">
                    VOTE <span style="font-weight: 500; color: var(--accent);">•</span> ÉVÉNEMENTIELLE
                </div>
                <div class="ornament" style="margin: 0 auto 32px;"></div>
                <h1
                    style="font-size: 1.4rem; color: var(--primary); text-transform: uppercase; letter-spacing: 0.3em; font-weight: 400; font-family: 'Jost', sans-serif;">
                    Mot de Passe Oublié</h1>
                <p style="color: var(--text-dim); font-size: 0.9rem; margin-top: 20px; font-family: 'Jost', sans-serif; line-height: 1.6;">
                    Entrez votre adresse e-mail pour recevoir un code de vérification à 6 chiffres.
                </p>
            </div>

            @if (session('status'))
                <div style="background: rgba(26, 77, 46, 0.1); color: var(--primary); padding: 15px; border-radius: 4px; margin-bottom: 30px; font-size: 0.9rem; border-left: 4px solid var(--primary);">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" style="display: flex; flex-direction: column; gap: 35px;">
                @csrf
                <div class="form-group">
                    <label
                        style="display: block; font-weight: 600; font-size: 0.7rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.25em; opacity: 0.8;">Identifiant
                        Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="votreemail@exemple.com"
                        style="width: 100%; height: 60px; padding: 0 0; background: transparent; border: none; border-bottom: 1px solid var(--border); font-family: 'Jost', sans-serif; font-size: 1.1rem; color: var(--primary); letter-spacing: 0.05em; transition: all 0.4s; outline: none;"
                        onfocus="this.style.borderBottomColor='var(--primary)'; this.style.paddingLeft='10px';"
                        onblur="this.style.borderBottomColor='var(--border)'; this.style.paddingLeft='0';">
                    @error('email')
                        <span style="color: #bc3e3e; font-size: 0.8rem; margin-top: 8px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-top: 10px;">
                    <button type="submit" class="btn"
                        style="width: 100%; height: 70px; background: var(--primary); color: white; border: none; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.3em; text-transform: uppercase; cursor: pointer; transition: all 0.4s; border-radius: 4px;"
                        onmouseover="this.style.background='var(--primary-light)'; this.style.transform='translateY(-2px)';"
                        onmouseout="this.style.background='var(--primary)'; this.style.transform='translateY(0)';"
                    >ENVOYER LE CODE</button>
                </div>
            </form>

            <div style="margin-top: 50px; text-align: center; border-top: 1px solid var(--border); padding-top: 30px;">
                <a href="{{ route('login') }}"
                    style="color: var(--accent); font-weight: 700; text-decoration: none; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.15em; transition: opacity 0.3s;"
                    onmouseover="this.style.opacity='0.6'" onmouseout="this.style.opacity='1'">&larr; Retour à la connexion</a>
            </div>
        </div>
    </div>
@endsection
