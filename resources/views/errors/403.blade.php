@extends('layouts.app')

@section('title', '403 — Accès Refusé')

@section('content')
    <div style="min-height: 70vh; display: flex; align-items: center; justify-content: center; padding: 80px 20px;">
        <div style="text-align: center; max-width: 600px;">

            <div style="font-family: 'Cormorant Garamond', serif; font-size: 0.9rem; font-weight: 600; color: var(--accent); text-transform: uppercase; letter-spacing: 0.5em; margin-bottom: 40px; opacity: 0.9;">
                Accès Restreint
            </div>

            <div style="font-family: 'Cormorant Garamond', serif; font-size: 12rem; color: var(--primary); line-height: 1; font-weight: 300; opacity: 0.08; position: absolute; left: 50%; transform: translateX(-50%); pointer-events: none; user-select: none;">
                403
            </div>

            <div class="ornament" style="margin: 0 auto 50px;"></div>

            <h1 style="font-size: 3.5rem; color: var(--primary); margin-bottom: 30px; font-weight: 300; font-family: 'Cormorant Garamond', serif; line-height: 1.2;">
                Campagne <span style="font-style: italic;">Indisponible.</span>
            </h1>

            <p style="color: var(--text-dim); font-size: 1.1rem; line-height: 1.9; margin-bottom: 60px; font-family: 'Jost', sans-serif; max-width: 460px; margin-left: auto; margin-right: auto;">
                {{ $exception->getMessage() ?? "Vous n'êtes pas autorisé à accéder à cette session. Veuillez vous identifier ou contacter l'organisateur." }}
            </p>

            <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ url('/') }}"
                    style="display: inline-block; padding: 18px 45px; background: var(--primary); color: white; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.25em; text-transform: uppercase; text-decoration: none; transition: all 0.4s; border-radius: 3px;"
                    onmouseover="this.style.background='var(--primary-light)'; this.style.transform='translateY(-2px)';"
                    onmouseout="this.style.background='var(--primary)'; this.style.transform='translateY(0)';">
                    Retour à l'Accueil
                </a>
                <a href="{{ route('campaigns.index') }}"
                    style="display: inline-block; padding: 18px 45px; border: 1px solid var(--primary); color: var(--primary); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.25em; text-transform: uppercase; text-decoration: none; transition: all 0.4s; border-radius: 3px;"
                    onmouseover="this.style.borderColor='var(--accent)'; this.style.color='var(--accent)';"
                    onmouseout="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)';">
                    Voir les Sessions
                </a>
            </div>

        </div>
    </div>
@endsection
