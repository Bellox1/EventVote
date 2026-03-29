@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div style="text-align: center; margin-bottom: 80px;">
    <h1 style="font-size: 4rem; color: var(--primary); margin-bottom: 16px;">Bonjour, {{ Auth::user()->name }}</h1>
    <div class="ornament" style="margin: 0 auto 32px;"></div>
    <p style="color: var(--text-dim); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Votre espace privilégié pour superviser vos scrutins et gérer votre participation électorale.</p>
</div>

<div style="display: flex; justify-content: center; margin-bottom: 100px;">
    <a href="{{ route('campaigns.create') }}" class="btn btn-primary" 
        style="padding: 25px 60px; font-size: 0.9rem; letter-spacing: 0.2em; border: 1px solid var(--accent); border-radius: 4px; box-shadow: 0 10px 30px rgba(0, 51, 43, 0.1);">
        CRÉER UN SCRUTIN D'EXCEPTION
    </a>
</div>

<div style="max-width: 1000px; margin: 0 auto;">
    <!-- Liste Unique Filtrée -->
    <h2 style="font-size: 2.5rem; color: var(--primary); margin-bottom: 40px; text-align: center;">{{ $contextLabel }}</h2>
    
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
                    <a href="{{ route('campaigns.show', $campaign->slug) }}" class="btn btn-outline" style="padding: 12px 24px;">VOIR LE SCRUTIN</a>
                    @if(Auth::id() === $campaign->user_id)
                        <a href="{{ route('campaigns.manage', $campaign->slug) }}" class="btn btn-accent" style="padding: 12px 24px;">GÉRER</a>
                    @endif
                </div>
            </div>
        @empty
            <div style="padding: 80px; text-align: center; border: 1px solid var(--border); background: var(--surface);">
                <div style="font-size: 2rem; color: var(--accent); margin-bottom: 20px;">✧</div>
                <p style="color: var(--text-dim); margin-bottom: 32px; font-style: italic; font-size: 1.1rem;">Aucun événement ne correspond à vos participations actuelles.</p>
                <a href="{{ route('campaigns.index') }}" class="btn btn-primary" style="padding: 15px 35px;">EXPLORER LES VOTES</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
