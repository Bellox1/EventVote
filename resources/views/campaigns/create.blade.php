@extends('layouts.app')

@section('title', 'Lancer un Scrutin d\'Exception')

@section('content')
<div style="max-width: 900px; margin: 60px auto; padding: 0 20px;">
    <div class="card" style="border-bottom: 6px solid var(--accent); padding: 80px 60px; box-shadow: var(--shadow-hard);">
        
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 80px;">
            <div style="font-family: 'Cormorant Garamond', serif; font-size: 3rem; color: var(--primary); letter-spacing: 0.05em; margin-bottom: 16px; font-weight: 300;">
                NOUVEAU <span style="font-weight: 500; color: var(--accent);">•</span> SCRUTIN
            </div>
            <div class="ornament" style="margin: 0 auto 32px;"></div>
            <h1 style="font-size: 1.2rem; color: var(--primary); text-transform: uppercase; letter-spacing: 0.4em; font-weight: 400; font-family: 'Jost', sans-serif;">
                Lancement d'une Session</h1>
        </div>
        
        <form action="{{ route('campaigns.store') }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 50px;">
            @csrf
            
            <!-- Section 1: Informations Générales -->
            <div style="display: flex; flex-direction: column; gap: 35px;">
                <div style="font-size: 0.7rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.3em; margin-bottom: -15px;">Détails de la Session</div>
                
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Nom de la Campagne</label>
                    <input type="text" name="name" required placeholder="EX: VOTE PRÉSIDENT CLUB 2026" autofocus
                        style="height: 65px; font-size: 1.1rem; border-radius: 4px; border: 1px solid var(--border); width: 100%; padding: 0 25px;">
                </div>

                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Description / Objectifs</label>
                    <textarea name="description" rows="4" placeholder="Veuillez décrire l'enjeu de ce scrutin..."
                        style="width: 100%; border: 1px solid var(--border); padding: 20px 25px; border-radius: 4px; font-size: 1.1rem; font-family: 'Jost', sans-serif; resize: vertical; min-height: 150px;"></textarea>
                </div>
            </div>

            <!-- Section 2: Programmation Temporelle (Optionnelle) -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                <div style="grid-column: span 2; font-size: 0.7rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.3em; margin-bottom: -15px;">Temporalité (Optionnel)</div>
                
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Ouverture des Votes</label>
                    <input type="datetime-local" name="start_at"
                        style="height: 65px; font-size: 1rem; border-radius: 4px; border: 1px solid var(--border); width: 100%; padding: 0 20px;">
                </div>

                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Clôture des Votes</label>
                    <input type="datetime-local" name="end_at"
                        style="height: 65px; font-size: 1rem; border-radius: 4px; border: 1px solid var(--border); width: 100%; padding: 0 20px;">
                </div>
            </div>

            <!-- Section 3: Médias -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                <div style="grid-column: span 2; font-size: 0.7rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.3em; margin-bottom: -15px;">Identité Visuelle</div>
                
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Affiche (Image)</label>
                    <input type="file" name="image" accept="image/*"
                           style="width: 100%; border: 1px solid var(--border); padding: 15px; border-radius: 4px; font-size: 0.8rem;">
                </div>

                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Vidéo (Optionnel)</label>
                    <input type="file" name="video" accept="video/*"
                           style="width: 100%; border: 1px solid var(--border); padding: 15px; border-radius: 4px; font-size: 0.8rem;">
                </div>
            </div>

            <div style="padding: 24px; background: rgba(212, 174, 109, 0.05); border-left: 4px solid var(--accent); border-radius: 0 8px 8px 0;">
                <p style="margin: 0; color: var(--primary); font-size: 0.9rem; font-style: italic;">
                    Note : Votre scrutin passera par une phase de validation administrative avant d'être ouvert au public.
                </p>
            </div>

            <div style="display: flex; gap: 20px; margin-top: 30px;">
                <a href="{{ route('dashboard') }}" class="btn btn-outline" style="flex: 1; height: 75px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">ANNULER</a>
                <button type="submit" class="btn btn-primary" style="flex: 2; height: 75px; font-size: 1rem; border-radius: 4px;">SOUUMETTRE POUR APPROBATION</button>
            </div>
        </form>
    </div>
</div>
@endsection
