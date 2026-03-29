@extends('layouts.app')

@section('title', 'Lancer une campagne')

@section('content')
<div style="max-width: 600px; margin: 40px auto;">
    <div class="glass-card" style="padding: 48px; border-radius: 32px;">
        <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 32px; letter-spacing: -1px;">Nouvelle campagne</h1>
        
        <form action="{{ route('campaigns.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="input-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; font-size: 0.9rem; color: var(--text-dim);">Nom de la campagne</label>
                <input type="text" name="name" required placeholder="Ex: Vote Président Club 2026" autofocus>
            </div>

            <div class="input-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; font-size: 0.9rem; color: var(--text-dim);">Détails / Description</label>
                <textarea name="description" rows="5" style="width: 100%; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--glass-border); padding: 14px 20px; border-radius: 14px; color: white; font-family: inherit; resize: vertical;" placeholder="Quel est l'objectif de ce vote ?"></textarea>
            </div>

            <div class="input-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; font-size: 0.9rem; color: var(--text-dim);">Affiche de la campagne (Image)</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <div class="input-group">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; font-size: 0.9rem; color: var(--text-dim);">Vidéo de présentation (Upload)</label>
                <input type="file" name="video" accept="video/*">
            </div>

            <div style="padding: 24px; background: rgba(99, 102, 241, 0.05); border: 1px solid rgba(99, 102, 241, 0.2); border-radius: 18px; margin-bottom: 32px;">
                <p style="margin: 0; color: var(--primary); font-size: 0.85rem; font-weight: 600; line-height: 1.5;">
                    💡 Votre campagne sera soumise à l'approbation de l'administrateur avant d'être activée.
                </p>
            </div>

            <div style="display: flex; gap: 16px;">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary" style="flex-grow: 1; justify-content: center; font-size: 1rem;">Soumettre la campagne</button>
            </div>
        </form>
    </div>
</div>
@endsection
