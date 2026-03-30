@extends('layouts.app')

@section('content')
    @php
        $totalVotes = $campaign->votes()->count();
    @endphp

    <div style="margin-bottom: 60px;">
        <a href="{{ route('campaigns.index') }}"
            style="text-decoration: none; color: var(--accent); font-weight: 700; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 15px; text-transform: uppercase; letter-spacing: 0.3em; transition: opacity 0.3s;"
            onmouseover="this.style.opacity='0.6'" onmouseout="this.style.opacity='1'">
            &larr; Retour à la sélection
        </a>
    </div>

    <!-- Vidéo Custom Premium -->
    @if ($campaign->video_path)
        <div style="margin: 0 0 80px; padding: 0 10%;">
            <div x-data="{ 
                playing: false, 
                showControls: false, 
                progress: 0,
                volume: 1,
                togglePlay() {
                    if (this.$refs.video.paused) {
                        this.$refs.video.play();
                        this.playing = true;
                    } else {
                        this.$refs.video.pause();
                        this.playing = false;
                    }
                },
                skip(time) {
                    this.$refs.video.currentTime += time;
                },
                updateProgress() {
                    this.progress = (this.$refs.video.currentTime / this.$refs.video.duration) * 100;
                },
                toggleFullscreen() {
                    if (!document.fullscreenElement) {
                        this.$refs.container.requestFullscreen();
                    } else {
                        document.exitFullscreen();
                    }
                }
            }" 
            x-ref="container"
            @mouseenter="showControls = true" 
            @mouseleave="showControls = false"
            style="position: relative; width: 100%; border-radius: 8px; overflow: hidden; box-shadow: 0 30px 60px rgba(0,0,0,0.4); background: #000; aspect-ratio: 16/9; cursor: pointer;">
                
                <video x-ref="video" 
                       @click="togglePlay()"
                       @timeupdate="updateProgress()"
                       @ended="playing = false"
                       style="width: 100%; height: 100%; object-fit: contain;">
                    <source src="{{ Str::startsWith($campaign->video_path, 'http') ? $campaign->video_path : asset('storage/' . $campaign->video_path) }}" type="video/mp4">
                </video>

                <!-- Boutons de saut -10s / +10s (Centrés au survol) -->
                <div x-show="showControls" x-transition.opacity 
                     style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; gap: 50px; background: rgba(0,0,0,0.2); z-index: 10;">
                    
                    <button @click.stop="skip(-10)" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white; width: 60px; height: 60px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.3s; backdrop-filter: blur(5px);">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 17l-5-5 5-5M18 17l-5-5 5-5"/></svg>
                        <span style="position: absolute; bottom: -25px; font-size: 0.75rem; font-weight: 700;">-10s</span>
                    </button>

                    <button @click.stop="togglePlay()" style="background: var(--accent); color: white; width: 80px; height: 80px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.3s; border: none; box-shadow: 0 0 30px rgba(212, 174, 109, 0.4);">
                        <template x-if="!playing">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                        </template>
                        <template x-if="playing">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                        </template>
                    </button>

                    <button @click.stop="skip(10)" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); color: white; width: 60px; height: 60px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.3s; backdrop-filter: blur(5px);">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 17l5-5-5-5M6 17l5-5-5-5"/></svg>
                        <span style="position: absolute; bottom: -25px; font-size: 0.75rem; font-weight: 700;">+10s</span>
                    </button>
                </div>

                <!-- Barre de contrôle (BAS) -->
                <div x-show="showControls" x-transition:enter.duration.400ms 
                     style="position: absolute; bottom: 0; left: 0; right: 0; padding: 30px; background: linear-gradient(transparent, rgba(0,0,0,0.8)); z-index: 20;">
                    
                    <!-- Progress Bar -->
                    <div style="width: 100%; height: 4px; background: rgba(255,255,255,0.2); border-radius: 2px; margin-bottom: 20px; cursor: pointer; position: relative;"
                         @click.stop="$refs.video.currentTime = ($event.offsetX / $el.offsetWidth) * $refs.video.duration">
                        <div :style="'width: ' + progress + '%'" style="height: 100%; background: var(--accent); border-radius: 2px; transition: width 0.1s linear;"></div>
                    </div>

                    <div style="display: flex; justify-content: flex-end; align-items: center; gap: 20px;">
                        <button @click.stop="toggleFullscreen()" style="background: none; border: none; color: white; cursor: pointer; padding: 5px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Event Showcase (Image + Info) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 60px; margin-bottom: 120px; align-items: start;">
        
        <!-- Image -->
        @if ($campaign->image_path)
            <div style="width: 100%; border-radius: 4px; overflow: hidden; box-shadow: var(--shadow-hard);">
                <img src="{{ Str::startsWith($campaign->image_path, 'http') ? $campaign->image_path : asset('storage/' . $campaign->image_path) }}" 
                     style="width: 100%; height: auto; display: block; object-fit: cover;">
            </div>
        @endif

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
                    <div style="font-size: 2.5rem; font-family: 'Cormorant Garamond', serif; color: var(--primary);">{{ $totalVotes }}</div>
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
        .btn-vote, .btn-view {
            width: 100%; padding: 15px 5px; 
            border: none; font-weight: 700; font-size: 0.7rem; letter-spacing: 0.2em; 
            text-transform: uppercase; cursor: pointer; transition: all 0.3s;
            display: flex; align-items: center; justify-content: center; text-decoration: none;
        }
        .btn-vote { background: var(--accent); color: white; }
        .btn-vote:hover { background: var(--primary); color: white; }
        
        .btn-view { background: rgba(255,255,255,0.95); color: var(--primary); }
        .btn-view:hover { background: var(--accent); color: white; }
    </style>

    <!-- Elite Selection (Top 3 Leaders) -->
    @if($topCandidates->count() > 0)
        @if ($totalVotes > 0)
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
                            
                            <div class="candidate-card" style="position: relative; aspect-ratio: 1/1; overflow: hidden; border-radius: 4px; margin: 0 auto 24px; box-shadow: var(--shadow-soft); background: var(--primary); cursor: pointer; max-width: 400px;">
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
                                        <div style="display: flex; gap: 10px; width: 100%;">
                                            <button type="button" class="btn-vote" style="flex: 1;" 
                                                onclick="buyVotes({{ $candidate->id }}, '{{ addslashes($candidate->name) }}', {{ $campaign->vote_price }})">
                                                VOTER
                                            </button>
                                            <a href="{{ route('candidates.show', [$campaign->slug, $candidate->id]) }}" class="btn-view" style="flex: 1;">VOIR LE PROFIL</a>
                                        </div>
                                    @endif
                                 </div>
                            </div>
                            
                            <a href="{{ route('candidates.show', [$campaign->slug, $candidate->id]) }}" style="text-decoration: none;">
                                <h4 style="font-size: 1.8rem; color: var(--primary); margin-bottom: 8px; font-family: 'Cormorant Garamond', serif;">{{ $candidate->name }}</h4>
                            </a>
                            <div style="font-size: 0.85rem; font-weight: 700; color: var(--accent); letter-spacing: 0.15em; text-transform: uppercase;">
                                {{ $candidate->votes_count }} voix ({{ $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 1) : 0 }}%)
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <!-- Compact Alert Banner for 0 Votes -->
            <div style="margin-bottom: 80px; padding: 25px 40px; border: 1px dashed var(--accent); border-radius: 4px; background: rgba(212, 174, 109, 0.05); display: flex; align-items: center; justify-content: center; gap: 20px; box-shadow: var(--shadow-soft);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                </svg>
                <div style="color: var(--primary); font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; font-weight: 600;">
                    Pas encore de leader pour le moment. <span style="font-family: 'Jost', sans-serif; font-size: 0.8rem; letter-spacing: 0.1em; text-transform: uppercase; opacity: 0.7; margin-left: 10px;">Rendez un ou une candidat(e) leader par votre vote !</span>
                </div>
            </div>
        @endif
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
                                <div style="display: flex; gap: 10px; width: 100%;">
                                    <button type="button" class="btn-vote" style="flex: 1;"
                                        onclick="buyVotes({{ $candidate->id }}, '{{ addslashes($candidate->name) }}', {{ $campaign->vote_price }})">
                                        VOTER
                                    </button>
                                    <a href="{{ route('candidates.show', [$campaign->slug, $candidate->id]) }}" class="btn-view" style="flex: 1;">VOIR ({{ $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 1) : 0 }}%)</a>
                                </div>
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
                            <div style="color: var(--text-dim); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;">{{ $candidate->votes_count }} Voix ({{ $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 1) : 0 }}%)</div>
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

    <!-- Payment Initiation Form (Hidden) -->
    <form id="payment-form" action="{{ route('payment.initiate') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="candidate_id" id="payment-candidate-id">
        <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
        <input type="hidden" name="votes_count" id="payment-votes-count">
    </form>

    <script>
        function buyVotes(candidateId, candidateName, unitPrice) {
            Swal.fire({
                title: 'Soutenir ' + candidateName,
                html: `
                    <div style="padding: 20px 0;">
                        <p style="color: var(--text-dim); font-family: 'Cormorant Garamond', serif; font-style: italic; font-size: 1.1rem; margin-bottom: 25px;">
                            Saisissez le nombre de voix que vous souhaitez attribuer (Max 100).
                        </p>
                        <div style="margin-bottom: 20px;">
                            <input type="number" id="votes-input" value="1" min="1" max="100" 
                                style="width: 100px; padding: 15px; text-align: center; font-size: 1.5rem; border: 1px solid var(--border); background: #F9F9F9; font-weight: 700; outline: none; color: var(--primary);"
                                oninput="document.getElementById('total-price').innerText = (this.value * ${unitPrice}).toLocaleString()">
                        </div>
                        <div style="background: var(--primary); color: white; padding: 15px; border-radius: 4px;">
                            <span style="text-transform: uppercase; letter-spacing: 0.2em; font-size: 0.7rem; opacity: 0.7;">Montant Total:</span><br>
                            <span style="font-size: 1.8rem; font-family: 'Cormorant Garamond', serif;"><span id="total-price">${unitPrice.toLocaleString()}</span> FCFA</span>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Procéder au paiement',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#003229',
                cancelButtonColor: '#ef4444',
                background: '#fff8e7',
                color: '#003229',
                preConfirm: () => {
                    const count = document.getElementById('votes-input').value;
                    if (count < 1 || count > 100) {
                        Swal.showValidationMessage('Veuillez choisir entre 1 et 100 votes.');
                        return false;
                    }
                    return count;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('payment-candidate-id').value = candidateId;
                    document.getElementById('payment-votes-count').value = result.value;
                    document.getElementById('payment-form').submit();
                }
            });
        }
    </script>
@endsection
