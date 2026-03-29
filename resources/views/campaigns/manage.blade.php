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

<!-- Session Parameter Configuration -->
<div class="card" style="padding: 40px; border: none; background: white; margin-bottom: 60px; box-shadow: var(--shadow-soft);">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px;">
        <div>
            <h2 style="font-size: 2rem; color: var(--primary); font-family: 'Cormorant Garamond', serif; margin: 0 0 10px;">Paramètres de Session</h2>
            <p style="color: var(--text-dim); margin: 0; font-size: 1rem;">Définissez les dates d'ouverture et de clôture, ou forcez l'état manuellement.</p>
        </div>
        <div style="padding: 10px 20px; border-radius: 4px; font-weight: 700; font-size: 0.8rem; letter-spacing: 0.15em; text-transform: uppercase; 
            @if($campaign->status === 'active') background: rgba(212, 174, 109, 0.1); color: var(--accent);
            @elseif($campaign->status === 'paused') background: rgba(107, 122, 119, 0.1); color: var(--text-dim);
            @else background: rgba(220, 38, 38, 0.1); color: #dc2626; @endif">
            État Actuel : {{ $campaign->status === 'active' ? 'OUVERT' : ($campaign->status === 'paused' ? 'EN PAUSE' : 'CLÔTURÉ') }}
        </div>
    </div>

    <form action="{{ route('campaigns.settings', $campaign->slug) }}" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; align-items: end;">
        @csrf
        @method('PUT')
        
        <!-- Status -->
        <div>
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 12px;">Contrôle Manuel</label>
            <select name="status" style="width: 100%; height: 50px; padding: 0 15px; border: 1px solid var(--border); border-radius: 4px; font-family: 'Jost', sans-serif; font-size: 1rem; color: var(--primary); background: #fdfcf9;">
                <option value="active" {{ $campaign->status === 'active' ? 'selected' : '' }}>Ouvert / Actif</option>
                <option value="paused" {{ $campaign->status === 'paused' ? 'selected' : '' }}>En Pause / Masqué</option>
                <option value="ended" {{ $campaign->status === 'ended' ? 'selected' : '' }}>Clôturé / Terminé</option>
            </select>
        </div>

        <!-- Start Date -->
        <div>
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 12px;">Début Prévu (Auto)</label>
            <input type="datetime-local" name="start_at" value="{{ $campaign->start_at ? $campaign->start_at->format('Y-m-d\TH:i') : '' }}" style="width: 100%; height: 50px; padding: 0 15px; border: 1px solid var(--border); border-radius: 4px; font-family: 'Jost', sans-serif; font-size: 1rem; color: var(--primary); background: #fdfcf9;">
        </div>

        <!-- End Date -->
        <div>
            <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 12px;">Fin Prévue (Auto)</label>
            <input type="datetime-local" name="end_at" value="{{ $campaign->end_at ? $campaign->end_at->format('Y-m-d\TH:i') : '' }}" style="width: 100%; height: 50px; padding: 0 15px; border: 1px solid var(--border); border-radius: 4px; font-family: 'Jost', sans-serif; font-size: 1rem; color: var(--primary); background: #fdfcf9;">
        </div>

        <!-- Submit -->
        <div>
            <button type="submit" class="btn" style="width: 100%; height: 50px; background: var(--primary); color: white; border: none; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; cursor: pointer; border-radius: 4px;">APPLIQUER</button>
        </div>
    </form>
    
    <div style="font-size: 0.85rem; color: var(--text-dim); margin-top: 25px; font-style: italic; border-left: 2px solid var(--accent); padding-left: 15px;">
        * Note : Si le statut global est "Ouvert" mais que la date de début n'est pas encore atteinte (ou date de fin dépassée), les votes seront automatiquement bloqués côté public. Laissez les dates vides pour une campagne toujours ouverte.
    </div>
</div>

<div style="display: grid; grid-template-columns: 1.5fr 1fr; align-items: flex-start; gap: 60px; margin-bottom: 120px;">
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
                        
                        <div style="width: 80px; height: 100px; border-radius: 8px; overflow: hidden; background: var(--border); flex-shrink: 0; box-shadow: var(--shadow-soft);">
                            @if($candidate->image_path)
                                <img src="{{ Str::startsWith($candidate->image_path, 'http') ? $candidate->image_path : asset('storage/' . $candidate->image_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: white; background: var(--accent); font-weight: bold; font-family: 'Cormorant Garamond', serif; font-size: 2rem;">
                                    {{ substr($candidate->name, 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <div style="flex: 1;">
                            <div style="font-size: 1.3rem; color: var(--primary); font-weight: 400; font-family: 'Cormorant Garamond', serif;">{{ $candidate->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px;">{{ $candidate->votes_count }} voix • Ordre actuel: {{ $candidate->sort_order }}</div>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <!-- Update Order Form -->
                        <form action="{{ route('candidate-applications.manage', $candidate->id) }}" method="POST" style="display: flex; gap: 10px; align-items: center; margin: 0;">
                            @csrf
                            <input type="hidden" name="status" value="accepted">
                            <input type="number" name="sort_order" value="{{ $candidate->sort_order }}" style="width: 60px; height: 35px; text-align: center; border: 1px solid var(--border); font-weight: 700;">
                            <button type="submit" class="btn" style="padding: 8px 15px; font-size: 0.65rem; background: var(--primary); color: white;">FIXER</button>
                        </form>

                        <!-- Archive Form -->
                        <form action="{{ route('candidates.archive', $candidate->id) }}" method="POST" style="margin: 0;" id="archive-form-{{ $candidate->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="Swal.fire({
                                title: 'Archiver ce candidat ?',
                                text: 'Il perdra sa place dans la liste des candidats, mais ses votes seront conservés.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#dc2626',
                                cancelButtonColor: '#d4ae6d',
                                confirmButtonText: 'Oui, archiver',
                                cancelButtonText: 'Annuler',
                                background: '#fff8e7',
                                color: '#003229'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.getElementById('archive-form-{{ $candidate->id }}').submit();
                                }
                            })" class="btn" style="padding: 8px 15px; font-size: 0.65rem; background: #fff; color: #dc2626; border: 1px solid #dc2626;">ARCHIVER</button>
                        </form>
                    </div>
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
                    <div style="display: flex; gap: 30px; margin-bottom: 30px; flex-wrap: wrap;">
                        <!-- Media Preview -->
                        <div style="width: 140px; height: 180px; border-radius: 8px; overflow: hidden; background: var(--border); box-shadow: var(--shadow-soft); flex-shrink: 0; position: relative;">
                            @if($candidate->image_path)
                                <img src="{{ Str::startsWith($candidate->image_path, 'http') ? $candidate->image_path : asset('storage/' . $candidate->image_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @elseif($candidate->video_path)
                                <video autoplay loop muted playsinline style="width: 100%; height: 100%; object-fit: cover;">
                                    <source src="{{ Str::startsWith($candidate->video_path, 'http') ? $candidate->video_path : asset('storage/' . $candidate->video_path) }}" type="video/mp4">
                                </video>
                                <div style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.5); padding: 4px; border-radius: 4px; color: white;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                                </div>
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: white; background: var(--accent); font-weight: bold; font-family: 'Cormorant Garamond', serif; font-size: 3rem;">
                                    {{ substr($candidate->name, 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <!-- Data & Text -->
                        <div style="flex: 1; min-width: 200px;">
                            <h3 style="margin: 0 0 10px; color: var(--primary); font-size: 1.8rem; font-weight: 400; font-family: 'Cormorant Garamond', serif;">{{ $candidate->name }}</h3>
                            <div style="font-size: 0.7rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 12px;">Discours / Présentation</div>
                            <p style="color: var(--text-dim); font-size: 1.05rem; margin: 0; line-height: 1.8; font-style: italic; background: #fdfcf9; padding: 20px; border-left: 2px solid var(--accent);">"{{ $candidate->description }}"</p>
                        </div>
                    </div>
                    
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
</div>

<!-- Archived Candidates -->
<div style="margin-top: 80px; padding-top: 80px; padding-bottom: 120px; border-top: 1px dashed var(--accent);">
    <div style="display: flex; flex-direction: column; align-items: center; text-align: center; margin-bottom: 60px;">
        <span style="font-size: 0.8rem; font-weight: 700; color: #dc2626; text-transform: uppercase; letter-spacing: 0.4em; display: block; margin-bottom: 16px;">Zone Restreinte</span>
        <h2 style="font-size: 3rem; color: var(--primary); font-family: 'Cormorant Garamond', serif; margin: 0;">Archives & <span style="font-style: italic;">Historique.</span></h2>
    </div>
    
    <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 24px; max-width: 1000px; margin: 0 auto;">
        @php $archived = $allCandidates->whereNotNull('deleted_at'); @endphp
        @forelse($archived as $candidate)
            <div class="card" style="width: 100%; max-width: 480px; padding: 24px; display: flex; align-items: center; justify-content: space-between; border: 1px dashed var(--border); background: #fdfdfd;">
                <div style="display: flex; align-items: center; gap: 20px; flex: 1;">
                    <div style="width: 80px; height: 100px; border-radius: 8px; overflow: hidden; background: var(--border); flex-shrink: 0; opacity: 0.5;">
                        @if($candidate->image_path)
                            <img src="{{ Str::startsWith($candidate->image_path, 'http') ? $candidate->image_path : asset('storage/' . $candidate->image_path) }}" style="width: 100%; height: 100%; object-fit: cover; filter: grayscale(100%);">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: white; background: var(--text-dim); font-weight: bold; font-family: 'Cormorant Garamond', serif; font-size: 2rem;">
                                {{ substr($candidate->name, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <div style="flex: 1; opacity: 0.6;">
                        <div style="font-size: 1.3rem; color: var(--text-dim); font-weight: 400; font-family: 'Cormorant Garamond', serif; text-decoration: line-through;">{{ $candidate->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px;">{{ $candidate->votes_count }} voix • Retiré</div>
                    </div>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <!-- Restore Form -->
                    <form action="{{ route('candidates.restore', $candidate->id) }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn" style="width: 100%; padding: 8px 15px; font-size: 0.65rem; background: var(--accent); color: white;">RESTAURER</button>
                    </form>
                    
                    <!-- Force Delete Form -->
                    <form action="{{ route('candidates.force', $candidate->id) }}" method="POST" style="margin: 0;" id="force-form-{{ $candidate->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="Swal.fire({
                            title: 'Destruction Définitive',
                            text: 'Voulez-vous supprimer DÉFINITIVEMENT ce candidat de la base de données ? Toutes les voix associées seront supprimées de manière irrévocable.',
                            icon: 'error',
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#d4ae6d',
                            confirmButtonText: 'Oui, détruire',
                            cancelButtonText: 'Annuler',
                            background: '#fff8e7',
                            color: '#003229'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('force-form-{{ $candidate->id }}').submit();
                            }
                        })" class="btn" style="width: 100%; padding: 8px 15px; font-size: 0.65rem; background: transparent; color: #dc2626; border: 1px solid #dc2626; transition: 0.3s;" onmouseover="this.style.background='#dc2626'; this.style.color='#fff'" onmouseout="this.style.background='transparent'; this.style.color='#dc2626'">DÉTRUIRE</button>
                    </form>
                </div>
            </div>
        @empty
            <p style="color: var(--text-dim); font-style: italic; font-family: 'Cormorant Garamond', serif; font-size: 1.25rem;">La section archives est vide.</p>
        @endforelse
    </div>
</div>
@endsection
