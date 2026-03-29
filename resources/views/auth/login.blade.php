@extends('layouts.app')

@section('title', 'Authentification ÉVÉNEMENTIELLE')

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
                    Connexion Privée</h1>
            </div>

            <form method="POST" action="{{ route('login') }}" style="display: flex; flex-direction: column; gap: 40px;">
                @csrf
                <div>
                    <label
                        style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 15px; color: var(--primary); letter-spacing: 0.2em;">Adresse
                        Email</label>
                    <input type="email" name="email" required autofocus placeholder="VOTRE EMAIL"
                        style="height: 70px; font-size: 1.05rem; letter-spacing: 0.05em; border-radius: 4px;">
                </div>

                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <label
                            style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; color: var(--primary); letter-spacing: 0.2em;">Mot
                            de passe</label>
                        <a href="#"
                            style="color: var(--accent); font-size: 0.65rem; font-weight: 700; text-decoration: none; text-transform: uppercase; letter-spacing: 0.1em; opacity: 0.8;">Récupérer
                            ?</a>
                    </div>
                    <input type="password" name="password" required placeholder="••••••••"
                        style="height: 70px; font-size: 1.05rem; letter-spacing: 0.05em; border-radius: 4px;">
                </div>

                <button type="submit" class="btn btn-primary"
                    style="width: 100%; height: 75px; font-size: 1rem; border-radius: 4px;">ACCÉDER AU BUREAU</button>
            </form>

            <div style="margin-top: 80px; text-align: center; border-top: 1px solid var(--border); padding-top: 50px;">
                <p
                    style="color: var(--text-dim); font-size: 0.9rem; margin-bottom: 30px; font-style: italic; font-family: 'Cormorant Garamond', serif;">
                    Vous n'avez pas encore rejoint le collectif ?</p>
                <a href="{{ route('register') }}"
                    style="color: var(--accent); font-weight: 700; text-decoration: none; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.15em; border-bottom: 2px solid var(--accent); padding-bottom: 6px; transition: opacity 0.3s;"
                    onmouseover="this.style.opacity='0.6'" onmouseout="this.style.opacity='1'">Créer un profil &rarr;</a>
            </div>
        </div>
    </div>
@endsection
