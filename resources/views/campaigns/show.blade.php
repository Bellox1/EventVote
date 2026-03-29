@extends('layouts.app')

@section('content')
    <div style="margin-bottom: 60px;">
        <a href="{{ route('campaigns.index') }}"
            style="text-decoration: none; color: var(--accent); font-weight: 700; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 15px; text-transform: uppercase; letter-spacing: 0.3em; transition: opacity 0.3s;"
            onmouseover="this.style.opacity='0.6'" onmouseout="this.style.opacity='1'">
            &larr; Retour à la sélection
        </a>
    </div>

    <!-- Main Event Showcase (Media + Info) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 60px; margin-bottom: 120px; align-items: start;">
        
        <!-- Media Stack (Video + Image) -->
        <div style="display: flex; flex-direction: column; gap: 30px;">
            @if ($campaign->video_path)
                <div style="width: 100%; border-radius: 4px; overflow: hidden; box-shadow: var(--shadow-hard); background: var(--primary);">
                    <video controls style="width: 100%; display: block;">
                        <source src="{{ Str::startsWith($campaign->video_path, 'http') ? $campaign->video_path : asset('storage/' . $campaign->video_path) }}" type="video/mp4">
                    </video>
                </div>
            @endif

            @if ($campaign->image_path)
                <div style="width: 100%; border-radius: 4px; overflow: hidden; box-shadow: var(--shadow-hard);">
                    <img src="{{ Str::startsWith($campaign->image_path, 'http') ? $campaign->image_path : asset('storage/' . $campaign->image_path) }}" 
                         style="width: 100%; height: auto; display: block; object-fit: cover;">
                </div>
            @endif
        </div>

        <!-- Info Section -->
        <div style="padding: 20px 0;">
            <div style="font-size: 0.8rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.4em; margin-bottom: 24px;">RÉF: #{{ $campaign->code }}</div>
            <h1 style="font-size: clamp(2.5rem, 5vw, 4rem); color: var(--primary); margin-bottom: 35px; line-height: 1.1; font-weight: 300;">
                {{ $campaign->name }}
            </h1>
            <div class="ornament" style="margin-bottom: 40px; width: 60px;"></div>
            <p style="color: var(--text-dim); font-size: 1.2rem; line-height: 1.9; font-family: 'Cormorant Garamond', serif; font-style: italic; margin-bottom: 50px;">
                {{ $campaign->description }}
            </p>

            <div style="display: flex; gap: 40px; border-top: 1px solid var(--border); padding-top: 40px;">
                <div>
                    <div style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; opacity: 0.6; margin-bottom: 10px;">Voix Totales</div>
                    <div style="font-size: 2.5rem; font-family: 'Cormorant Garamond', serif; color: var(--primary);">{{ $campaign->votes()->count() }}</div>
                </div>
                <div style="width: 1px; background: var(--border);"></div>
                <div>
                    <div style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; opacity: 0.6; margin-bottom: 10px;">Status</div>
                    <div style="font-size: 1rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.1em; margin-top: 15px;">ACTIF</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hover Controls Global Style -->
    <style>
        .candidate-card:hover .voting-overlay { opacity: 1; bottom: 30px; }
        .candidate-card:hover .media-item { transform: scale(1.05); }
        .voting-overlay {
            position: absolute; bottom: 0; left: 20px; right: 20px; 
            opacity: 0; transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1); z-index: 30;
        }
        .btn-vote {
            width: 100%; padding: 15px; background: white; color: var(--primary); 
            border: none; font-weight: 700; font-size: 0.7rem; letter-spacing: 0.2em; 
            text-transform: uppercase; cursor: pointer; transition: all 0.3s;
        }
        .btn-vote:hover { background: var(--accent); color: white; }
    </style>

    <!-- Elite Selection (Top 3 Leaders) -->
    @if($topCandidates->count() > 0)
    <div style="background: #F9F6F0; margin: 0 -100px 120px; padding: 100px;">
        <div style="max-width: 1400px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 80px;">
                <span style="font-size: 0.8rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.4em; display: block; margin-bottom: 20px;">Le Trio de Tête</span>
                <h2 style="font-size: 3rem; color: var(--primary); font-family: 'Cormorant Garamond', serif;">Élite du <span style="font-style: italic;">Scrutin.</span></h2>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 60px;">
                @foreach($topCandidates as $index => $candidate)
                <div style="position: relative; text-align: center;">
                    <div style="position: absolute; top: -20px; left: 50%; transform: translateX(-50%); background: var(--accent); color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; z-index: 10; font-size: 1.2rem; font-family: 'Cormorant Garamond', serif;">
                        {{ $index + 1 }}
                    </div>
                    
                    <div class="candidate-card" style="position: relative; aspect-ratio: 4/5; overflow: hidden; border-radius: 4px; margin-bottom: 24px; box-shadow: var(--shadow-soft); background: var(--primary); cursor: pointer;">
                        <a href="{{ route('candidates.show', [$campaign->slug, $candidate->id]) }}" style="display: block; width: 100%; height: 100%;">
                             @if($candidate->image_path)
                                <img src="{{ Str::startsWith($candidate->image_path, 'http') ? $candidate->image_path : asset('storage/' . $candidate->image_path) }}" 
                                     class="media-item" style="width: 100%; height: 100%; object-fit: cover; transition: all 0.6s;">
                             @elseif($candidate->video_path)
                                <video autoplay loop muted playsinline class="media-item" style="width: 100%; height: 100%; object-fit: cover; transition: all 0.6s;">
                                    <source src="{{ Str::startsWith($candidate->video_path, 'http') ? $candidate->video_path : asset('storage/' . $candidate->video_path) }}" type="video/mp4">
                                </video>
                             @endif
                             <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, transparent 60%, rgba(0,0,0,0.4));"></div>
                        </a>

                         <div class="voting-overlay">
                            @if ($campaign->isActive())
                                <form action="{{ route('vote.cast', $campaign->slug) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
                                    <button type="submit" class="btn-vote">VOTER POUR {{ $candidate->name }}</button>
                                </form>
                            @endif
                         </div>
                    </div>
                    
                    <a href="{{ route('candidates.show', [$campaign->slug, $candidate->id]) }}" style="text-decoration: none;">
                        <h4 style="font-size: 1.8rem; color: var(--primary); margin-bottom: 8px; font-family: 'Cormorant Garamond', serif;">{{ $candidate->name }}</h4>
                    </a>
                    <div style="font-size: 0.85rem; font-weight: 700; color: var(--accent); letter-spacing: 0.15em; text-transform: uppercase;">{{ $candidate->votes_count }} voix exprimées</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Full Gallery (Ordered by sort_order) -->
    <div style="margin-bottom: 120px; padding: 0 20px;">
        <div style="margin-bottom: 80px;">
            <span style="font-size: 0.8rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.4em;">Galerie Complète</span>
            <h2 style="font-size: 3rem; color: var(--primary); font-family: 'Cormorant Garamond', serif; margin-top: 15px;">Nos <span style="font-style: italic;">Candidat(e)s.</span></h2>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 60px 40px;">
            @forelse($candidates as $candidate)
                <div style="display: flex; flex-direction: column;">
                    <div class="candidate-card" style="position: relative; aspect-ratio: 4/5; overflow: hidden; cursor: pointer; transition: all 0.5s; border-radius: 4px; margin-bottom: 25px;">
                        <a href="{{ route('candidates.show', [$campaign->slug, $candidate->id]) }}" style="display: block; width: 100%; height: 100%;">
                            <div style="width: 100%; height: 100%; position: relative; overflow: hidden;">
                                @if ($candidate->image_path)
                                    <img src="{{ Str::startsWith($candidate->image_path, 'http') ? $candidate->image_path : asset('storage/' . $candidate->image_path) }}"
                                        class="media-item" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s cubic-bezier(0.19, 1, 0.22, 1);">
                                @elseif($candidate->video_path)
                                    <video autoplay loop muted playsinline class="media-item" style="width: 100%; height: 100%; object-fit: cover; transition: all 0.6s;">
                                        <source src="{{ Str::startsWith($candidate->video_path, 'http') ? $candidate->video_path : asset('storage/' . $candidate->video_path) }}" type="video/mp4">
                                    </video>
                                @endif
                                <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, transparent 60%, rgba(0,0,0,0.4));"></div>
                            </div>
                        </a>
                        
                        <!-- Voting Action (Hover) -->
                        <div class="voting-overlay">
                            @if ($campaign->isActive())
                                <form action="{{ route('vote.cast', $campaign->slug) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
                                    <button type="submit" class="btn-vote">SÉLECTIONNER CE CHOIX</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Label Below Image -->
                    <div style="text-align: center;">
                        <a href="{{ route('candidates.show', [$campaign->slug, $candidate->id]) }}" style="text-decoration: none;">
                            <h3 style="color: var(--primary); font-size: 1.6rem; margin: 0 0 10px; font-family: 'Cormorant Garamond', serif; font-weight: 400;">{{ $candidate->name }}</h3>
                        </a>
                        <div style="display: flex; justify-content: center; align-items: center; gap: 20px;">
                            <div style="color: var(--accent); font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em;">Ordre: {{ $candidate->sort_order }}</div>
                            <div style="width: 4px; height: 4px; background: var(--border); border-radius: 50%;"></div>
                            <div style="color: var(--text-dim); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;">{{ $candidate->votes_count }} Voix</div>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 100px; border: 1px dashed var(--border);">
                    <p style="color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.2em;">Aucun candidat disponible.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
