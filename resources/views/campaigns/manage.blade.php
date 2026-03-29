@extends('layouts.app')

@section('title', 'Gérer – ' . $campaign->name)

@section('content')
<div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 40px; margin-bottom: 80px; border-bottom: 1px solid var(--border); padding-bottom: 40px;">
    <div>
        <div style="font-size: 0.8rem; font-weight: 600; color: var(--accent); text-transform: uppercase; letter-spacing: 0.4em; margin-bottom: 16px;">Administration du Scrutin</div>
        <h1 style="font-size: 3.5rem; color: var(--primary); margin-bottom: 15px; font-weight: 300;">Gestion de <span style="font-style: italic; font-weight: 400;">Campagne.</span></h1>
        <p style="color: var(--text-dim); font-size: 1.1rem; max-width: 600px; line-height: 1.8;">Supervisez les candidatures, validez les participations et suivez l'intégrité des résultats en temps réel.</p>
    </div>
    
    <div style="text-align: right; background: white; padding: 32px 48px; border-radius: var(--radius); border: none; box-shadow: var(--shadow-soft);">
        <div style="font-size: 0.75rem; color: var(--accent); font-weight: 700; text-transform: uppercase; letter-spacing: 0.25em; margin-bottom: 8px;">Suffrages Exprimés</div>
        <div style="font-size: 4rem; font-family: 'Cormorant Garamond', serif; font-weight: 300; color: var(--primary); line-height: 1;">{{ $votesCount }}</div>
    </div>
</div>

<div class="grid" style="align-items: flex-start; gap: 60px;">
    <!-- Live Results & Ordering -->
    <div>
        <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 40px;">
            <div style="width: 40px; height: 1px; background: var(--accent);"></div>
            <h2 style="font-size: 2rem; color: var(--primary); font-weight: 400; margin: 0;">Classement & Ordre d'affichage</h2>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 24px;">
            @forelse($results as $index => $candidate)
                <div class="card" style="padding: 24px; display: flex; align-items: center; justify-content: space-between; border: none; background: white; border-left: 6px solid {{ $index === 0 ? 'var(--accent)' : 'var(--border)' }};">
                    <div style="display: flex; align-items: center; gap: 24px; flex: 1;">
                        <span style="font-size: 1.5rem; font-family: 'Cormorant Garamond', serif; color: var(--accent); width: 25px; opacity: 0.6;">{{ sprintf('%02d', $index + 1) }}</span>
                        <div style="flex: 1;">
                            <div style="font-size: 1.3rem; color: var(--primary); font-weight: 400; font-family: 'Cormorant Garamond', serif;">{{ $candidate->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px;">{{ $candidate->votes_count }} voix • Ordre actuel: {{ $candidate->sort_order }}</div>
                        </div>
                    </div>
                    
                    <!-- Update Order Form -->
                    <form action="{{ route('candidate-applications.manage', $candidate->id) }}" method="POST" style="display: flex; gap: 10px; align-items: center;">
                        @csrf
                        <input type="hidden" name="status" value="accepted">
                        <input type="number" name="sort_order" value="{{ $candidate->sort_order }}" style="width: 60px; height: 40px; text-align: center; border: 1px solid var(--border); font-weight: 700;">
                        <button type="submit" class="btn" style="padding: 8px 15px; font-size: 0.65rem; background: var(--primary); color: white;">FIXER</button>
                    </form>
                </div>
            @empty
                <p style="color: var(--text-dim); font-style: italic; font-family: 'Cormorant Garamond', serif; font-size: 1.25rem;">Aucun candidat n'est encore validé.</p>
            @endforelse
        </div>
    </div>

    <!-- Application Requests -->
    <div>
        <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 40px;">
            <div style="width: 40px; height: 1px; background: var(--accent);"></div>
            <h2 style="font-size: 2rem; color: var(--primary); font-weight: 400; margin: 0;">Candidatures en Suspens</h2>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 24px;">
            @php $pending = $allCandidates->where('status', 'pending'); @endphp
            @forelse($pending as $candidate)
                <div class="card" style="padding: 40px; border: none; background: white;">
                    <h3 style="margin: 0 0 16px; color: var(--primary); font-size: 1.8rem; font-weight: 400; font-family: 'Cormorant Garamond', serif;">{{ $candidate->name }}</h3>
                    <p style="color: var(--text-dim); font-size: 1.05rem; margin: 0 0 32px; line-height: 1.8; font-style: italic;">{{ $candidate->description }}</p>
                    
                    <form action="{{ route('candidate-applications.manage', $candidate->id) }}" method="POST">
                        @csrf
                        <div style="background: #F9F6F0; padding: 25px; border-radius: 4px; margin-bottom: 25px;">
                            <label style="display: block; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 12px; color: var(--primary);">Définir l'Ordre d'Apparition (Facultatif)</label>
                            <input type="number" name="sort_order" placeholder="Par défaut: auto" style="width: 100%; height: 50px; border: 1px solid var(--border);">
                            <div style="font-size: 0.65rem; color: var(--text-dim); margin-top: 8px; font-style: italic;">Plus le chiffre est petit, plus le candidat apparaît en premier.</div>
                        </div>

                        <div style="display: flex; gap: 16px;">
                            <input type="hidden" name="status" value="accepted">
                            <button type="submit" class="btn btn-primary" style="flex: 2; font-size: 0.75rem; padding: 18px;">Approuver la demande</button>
                            <button type="button" @click="if(confirm('Refuser cette candidature ?')) { $el.nextElementSibling.submit() }" class="btn btn-outline" style="flex: 1; font-size: 0.75rem; padding: 18px; border-width: 1px;">Décliner</button>
                        </div>
                    </form>
                    <form action="{{ route('candidate-applications.manage', $candidate->id) }}" method="POST" style="display: none;">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                    </form>
                </div>
            @empty
                <div style="padding: 60px; text-align: center; background: white; border-radius: var(--radius); border: 1px dashed var(--border);">
                    <div style="font-size: 2.5rem; color: var(--accent); opacity: 0.2; margin-bottom: 16px;">✧</div>
                    <p style="color: var(--text-dim); font-style: italic; font-family: 'Cormorant Garamond', serif; font-size: 1.15rem;">Toutes les demandes ont été traitées.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
