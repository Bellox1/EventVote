@extends('layouts.app')

@section('title', 'Candidature – ' . $campaign->name)

@section('content')
<div style="max-width: 600px; margin: 40px auto;">
    <div class="glass-card" style="padding: 48px; border-radius: 32px;">
        <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 24px; letter-spacing: -1px;">Devenir candidat</h1>
        <p style="color: var(--text-dim); margin-bottom: 40px;">Présentez-vous pour la campagne : <span style="color: white; font-weight: 700;">{{ $campaign->name }}</span></p>
        
        <form action="{{ route('candidates.apply', $campaign->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="input-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; font-size: 0.9rem; color: var(--text-dim);">Nom de candidat</label>
                <input type="text" name="name" value="{{ Auth::user()->name }}" required placeholder="Ex: Jean Dupont" autofocus>
            </div>

            <div class="input-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; font-size: 0.9rem; color: var(--text-dim);">Description / Programme</label>
                <textarea name="description" rows="5" style="width: 100%; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); padding: 14px 20px; border-radius: 14px; color: white; font-family: inherit; resize: vertical;" placeholder="Pourquoi voter pour vous ? (Programme, motivations...)"></textarea>
            </div>

            <div class="input-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; font-size: 0.9rem; color: var(--text-dim);">Ma photo (Image)</label>
                <input type="file" name="image_path" accept="image/*">
            </div>

            <div class="input-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; font-size: 0.9rem; color: var(--text-dim);">Ma vidéo de présentation (Upload)</label>
                <input type="file" name="video" accept="video/*">
            </div>

            <div style="padding: 24px; background: rgba(99, 102, 241, 0.05); border: 1px solid rgba(99, 102, 241, 0.2); border-radius: 18px; margin-bottom: 32px;">
                <p style="margin: 0; color: var(--primary); font-size: 0.85rem; font-weight: 600; line-height: 1.5;">
                    💡 Votre demande sera soumise au créateur de la campagne pour validation.
                </p>
            </div>

            <div style="display: flex; gap: 16px;">
                <a href="{{ route('campaigns.show', $campaign->slug) }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary" style="flex-grow: 1; justify-content: center; font-size: 1rem;">Envoyer ma candidature</button>
            </div>
        </form>
    </div>
</div>
@endsection
