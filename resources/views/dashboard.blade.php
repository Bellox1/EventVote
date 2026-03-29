@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div style="text-align: center; margin-bottom: 80px;">
    <h1 style="font-size: 4rem; color: var(--primary); margin-bottom: 16px;">Bonjour, {{ Auth::user()->name }}</h1>
    <div class="ornament" style="margin: 0 auto 32px;"></div>
    <p style="color: var(--text-dim); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Votre espace privilégié pour superviser vos scrutins et gérer votre participation électorale.</p>
</div>

<div style="display: flex; justify-content: center; margin-bottom: 100px;">
    <a href="{{ route('campaigns.create') }}" class="btn btn-primary" style="padding: 20px 48px; font-size: 1rem;">CRÉER UNE NOUVELLE SESSION</a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 60px;">
    <!-- Mes Campagnes -->
    <div>
        <h2 style="font-size: 2.5rem; color: var(--primary); margin-bottom: 40px;">Mes Sessions de Vote</h2>
        
        <div style="display: flex; flex-direction: column; gap: 32px;">
            @forelse($myCampaigns as $campaign)
                <div class="card" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 4px solid var(--accent); padding: 40px;">
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div style="font-size: 0.7rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.25em;">RÉFÉRENCE #{{ $campaign->code }}</div>
                        <h3 style="margin: 0; font-size: 2rem; color: var(--primary);">{{ $campaign->name }}</h3>
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <span class="badge {{ $campaign->status === 'active' ? 'badge-active' : 'badge-pending' }}">{{ $campaign->status }}</span>
                            <span style="font-size: 0.85rem; color: var(--text-dim);">{{ $campaign->votes()->count() }} suffrages enregistrés</span>
                        </div>
                    </div>
                    <div style="display: flex; gap: 16px;">
                        <a href="{{ route('campaigns.show', $campaign->slug) }}" class="btn btn-outline" style="padding: 12px 24px;">VOIR</a>
                        <a href="{{ route('campaigns.manage', $campaign->slug) }}" class="btn btn-accent" style="padding: 12px 24px;">GÉRER</a>
                    </div>
                </div>
            @empty
                <div style="padding: 80px; text-align: center; border: 1px solid var(--border); background: var(--surface);">
                    <p style="color: var(--text-dim); margin-bottom: 32px; font-style: italic;">Vous n'avez pas encore initié de session de vote.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Mon Historique de Vote -->
    <div>
        <h2 style="font-size: 2.5rem; color: var(--primary); margin-bottom: 40px;">Activités</h2>
        <div style="background: var(--surface); border: 1px solid var(--border); padding: 40px; border-radius: 4px; box-shadow: var(--shadow-soft);">
            @forelse($myVotes as $vote)
                <div style="padding-bottom: 32px; margin-bottom: 32px; border-bottom: 1px solid var(--border);">
                    <div style="font-weight: 700; font-size: 0.65rem; color: var(--accent); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 12px;">Vote Enregistré</div>
                    <div style="font-size: 1.1rem; color: var(--primary); margin-bottom: 8px;">{{ $vote->candidate->name }}</div>
                    <div style="font-size: 0.85rem; color: var(--text-dim); margin-bottom: 16px;">{{ $vote->campaign->name }}</div>
                    <div style="font-size: 0.7rem; color: var(--text-dim); opacity: 0.6; text-transform: uppercase;">{{ $vote->created_at->format('d/m/Y H:i') }}</div>
                </div>
            @empty
                <p style="color: var(--text-dim); font-size: 1rem; margin: 0; font-style: italic;">Aucune participation pour l'instant.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
