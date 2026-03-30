@extends('layouts.app')

@section('title', 'Tableau de Bord Candidat')

@section('content')
<div style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
    
    <!-- Bouton Retour -->
    <a href="{{ route('dashboard') }}" style="display: flex; align-items: center; gap: 10px; color: var(--text-dim); text-decoration: none; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 40px; transition: 0.3s;" onmouseover="this.style.color='var(--primary)'">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> RETOUR AU TABLEAU DE BORD
    </a>

    <!-- Header & Rang -->
    <div style="text-align: center; margin-bottom: 60px;">
        <div style="font-family: 'Cormorant Garamond', serif; font-size: 3.5rem; color: var(--primary); margin-bottom: 10px; line-height: 1;">Performance</div>
        <div class="ornament" style="margin: 0 auto 30px;"></div>
        
        <div style="display: inline-flex; flex-direction: column; align-items: center; background: white; padding: 40px 60px; border-radius: 4px; box-shadow: var(--shadow-soft); border-bottom: 6px solid {{ $myRank == 1 ? '#d4ae6d' : 'var(--primary)' }}; position: relative; overflow: hidden;">
            @if($myRank == 1)
                <div style="position: absolute; top: 0; left: 0; background: #d4ae6d; color: white; width: 100%; font-size: 0.6rem; font-weight: 700; text-transform: uppercase; padding: 4px 0; letter-spacing: 0.3em;">LEADER DU SCRUTIN</div>
            @endif
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.3em; margin-bottom: 20px;">Votre Rang Actuel</div>
            <div style="font-size: 5rem; font-family: 'Cormorant Garamond', serif; color: var(--primary); line-height: 1; font-weight: 400;">{{ $myRank }}<span style="font-size: 2rem;">{{ $myRank == 1 ? 'er' : 'ème' }}</span></div>
            <div style="font-size: 0.9rem; color: var(--accent); font-weight: 600; margin-top: 15px; text-transform: uppercase; letter-spacing: 0.1em;">sur {{ $competitors->count() }} Candidats</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 40px;">
        <!-- Colonne Gauche : Mon Profil -->
        <div style="display: flex; flex-direction: column; gap: 30px;">
            <div class="card" style="padding: 40px;">
                <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                    <div style="width: 150px; height: 150px; border-radius: 50%; border: 4px solid var(--accent); padding: 5px; margin-bottom: 25px;">
                        @if($candidate->image_path)
                            <img src="{{ Str::startsWith($candidate->image_path, 'http') ? $candidate->image_path : asset('storage/' . $candidate->image_path) }}" 
                                 style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; border-radius: 50%; background: var(--border); display: flex; align-items: center; justify-content: center; font-size: 3rem; color: var(--primary); font-family: 'Cormorant Garamond', serif;">{{ substr($candidate->name, 0, 1) }}</div>
                        @endif
                    </div>
                    <div style="font-family: 'Cormorant Garamond', serif; font-size: 1.8rem; color: var(--primary); line-height: 1.2;">{{ $candidate->name }}</div>
                    <div style="font-size: 0.7rem; color: var(--accent); text-transform: uppercase; font-weight: 700; letter-spacing: 0.2em; margin-top: 10px;">ID CANDIDAT #{{ $candidate->id }}</div>
                </div>

                <div style="margin-top: 40px; text-align: center;">
                    <div style="font-size: 0.7rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 10px;">Total des Voix</div>
                    <div style="font-size: 3rem; font-family: 'Cormorant Garamond', serif; color: var(--primary);">{{ $candidate->votes_count }}</div>
                    <div style="height: 6px; background: #f0f0f0; border-radius: 10px; margin-top: 15px; overflow: hidden;">
                        @php $percent = $competitors->sum('votes_count') > 0 ? ($candidate->votes_count / $competitors->sum('votes_count') * 100) : 0; @endphp
                        <div style="width: {{ $percent }}%; height: 100%; background: var(--accent);"></div>
                    </div>
                    <div style="font-size: 0.65rem; color: var(--text-dim); margin-top: 8px; text-transform: uppercase; letter-spacing: 0.05em;">{{ number_format($percent, 1) }}% de l'électorat</div>
                </div>
            </div>
            
            <div style="background: var(--primary); padding: 30px; border-radius: 4px; color: white; text-align: center;">
                <div style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: var(--accent); margin-bottom: 15px;">Partagez votre fiche</div>
                <p style="font-size: 0.85rem; margin-bottom: 20px; opacity: 0.8;">Invitez vos contacts à voter pour vous sur votre page officielle.</p>
                <a href="{{ route('campaigns.show', $campaign->slug) }}#candidate-{{ $candidate->id }}" style="display: block; padding: 15px; background: white; color: var(--primary); text-decoration: none; font-size: 0.75rem; font-weight: 700; border-radius: 4px;">VOIR MA FICHE PUBLIQUE</a>
            </div>
        </div>

        <!-- Colonne Droite : Classement Général -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 30px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; color: var(--primary); margin: 0;">Classement Général</h3>
                <span style="font-size: 0.65rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.1em; background: #fffcf5; padding: 6px 12px; border: 1px solid var(--border);">EN DIRECT</span>
            </div>

            <div style="max-height: 600px; overflow-y: auto;">
                @foreach($competitors as $index => $c)
                    <div style="padding: 25px 30px; border-bottom: 1px solid #f9f9f9; display: flex; align-items: center; justify-content: space-between; background: {{ $c->id == $candidate->id ? '#fffdf7' : 'transparent' }}; border-left: 4px solid {{ $c->id == $candidate->id ? 'var(--accent)' : 'transparent' }};">
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div style="font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; color: {{ $index == 0 ? '#d4ae6d' : 'var(--text-dim)' }}; width: 30px; font-weight: 700; text-align: center;">
                                #{{ $index + 1 }}
                            </div>
                            <div style="width: 45px; height: 45px; border-radius: 50%; overflow: hidden; border: 2px solid {{ $index == 0 ? '#d4ae6d' : '#eee' }}; flex-shrink: 0;">
                                @if($c->image_path)
                                    <img src="{{ Str::startsWith($c->image_path, 'http') ? $c->image_path : asset('storage/' . $c->image_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700;">{{ substr($c->name, 0, 1) }}</div>
                                @endif
                            </div>
                            <div>
                                <div style="font-weight: 700; color: var(--primary); font-size: 1rem;">
                                    {{ $c->name }}
                                    @if($c->id == $candidate->id) <span style="font-size: 0.6rem; background: var(--accent); color: white; padding: 2px 6px; border-radius: 4px; margin-left: 5px; letter-spacing: 0.05em;">MOI</span> @endif
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-dim);">Dossier n°{{ $c->id }}</div>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; color: var(--primary); font-weight: 700;">{{ $c->votes_count }}</div>
                            <div style="font-size: 0.65rem; color: var(--accent); text-transform: uppercase; font-weight: 700;">Voix</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
