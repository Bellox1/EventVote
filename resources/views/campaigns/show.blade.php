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

    <!-- Main Event Showcase -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 60px; margin-bottom: 120px; align-items: start;">
        
        <div style="display: flex; flex-direction: column; gap: 30px;">
            @if ($campaign->video_path)
                <div x-data="{ 
                    ui: false, timeout: null,
                    show() { this.ui = true; clearTimeout(this.timeout); this.timeout = setTimeout(() => this.ui = false, 2000); },
                    skip(s) { $refs.mVid.currentTime += s; this.f(s > 0 ? 'r' : 'l'); this.show(); },
                    fL: false, fR: false,
                    f(side) { side === 'l' ? (this.fL = true, setTimeout(()=>this.fL=false, 500)) : (this.fR = true, setTimeout(()=>this.fR=false, 500)); }
                }" @mousemove="show()" @mouseleave="ui = false"
                style="width: 100%; border-radius: 4px; overflow: hidden; box-shadow: var(--shadow-hard); background: black; position: relative;">
                    
                    <!-- Video Area -->
                    <div style="position: relative; width: 100%; display: flex; align-items: center; justify-content: center;">
                        <video x-ref="mVid" controls style="width: 100%; display: block; cursor: pointer; max-height: 80vh;"
                               @dblclick="($event.offsetX < $el.clientWidth / 2) ? skip(-10) : skip(10)">
                            <source src="{{ Str::startsWith($campaign->video_path, 'http') ? $campaign->video_path : asset('storage/' . $campaign->video_path) }}" type="video/mp4">
                        </video>

                        <!-- Overlays perfectly inside -->
                        <div x-show="ui" x-transition.opacity style="position: absolute; inset: 0; pointer-events: none; z-index: 10; display: flex; align-items: center; justify-content: space-between; padding: 0 40px;">
                            <button @click="skip(-10)" style="pointer-events: auto; background: rgba(0,0,0,0.5); color: white; border: none; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 17L6 12L11 7"/><path d="M18 17L13 12L18 7"/></svg>
                            </button>
                            <button @click="skip(10)" style="pointer-events: auto; background: rgba(0,0,0,0.5); color: white; border: none; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M13 17L18 12L13 7"/><path d="M6 17L11 12L6 7"/></svg>
                            </button>
                        </div>

                        <!-- Flash -->
                        <div x-show="fL" x-transition style="position: absolute; left: 15%; background: rgba(255,255,255,0.2); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; pointer-events: none; z-index: 20;">-10s</div>
                        <div x-show="fR" x-transition style="position: absolute; right: 15%; background: rgba(255,255,255,0.2); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; pointer-events: none; z-index: 20;">+10s</div>
                    </div>
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

    <!-- Rest of the page styles and sections -->
    <style>
        .candidate-card:hover .voting-overlay { opacity: 1; bottom: 30px; }
        .candidate-card:hover .media-item { transform: scale(1.05); }
        .voting-overlay { position: absolute; bottom: 0; left: 20px; right: 20px; opacity: 0; transition: all 0.5s; z-index: 30; }
        .btn-vote, .btn-view { width: 100%; padding: 15px 5px; border: none; font-weight: 700; font-size: 0.7rem; letter-spacing: 0.2em; text-transform: uppercase; cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; text-decoration: none; }
        .btn-vote { background: var(--accent); color: white; }
        .btn-view { background: rgba(255,255,255,0.95); color: var(--primary); }
    </style>

    @if($topCandidates->count() > 0 && $totalVotes > 0)
        <div style="background: #F9F6F0; margin: 0 -100px 120px; padding: 100px;">
            <div style="max-width: 1400px; margin: 0 auto;">
                <div style="text-align: center; margin-bottom: 80px;">
                    <span style="font-size: 0.8rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.4em; display: block; margin-bottom: 20px;">Le Trio de Tête</span>
                    <h2 style="font-size: 3rem; color: var(--primary); font-family: 'Cormorant Garamond', serif;">Élite du <span style="font-style: italic;">Scrutin.</span></h2>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 60px;">
                    @foreach($topCandidates as $index => $candidate)
                        <div style="position: relative; text-align: center;">
                            <div style="position: absolute; top: -20px; left: 50%; transform: translateX(-50%); background: var(--accent); color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; z-index: 10;">{{ $index + 1 }}</div>
                            <div class="candidate-card" style="position: relative; aspect-ratio: 4/5; overflow: hidden; border-radius: 4px; margin-bottom: 24px;">
                                <a href="{{ route('candidates.show', [$campaign->slug, $candidate->id]) }}">
                                    @if($candidate->image_path)
                                        <img src="{{ asset('storage/' . $candidate->image_path) }}" class="media-item" style="width: 100%; height: 100%; object-fit: cover;">
                                    @elseif($candidate->video_path)
                                        <video autoplay loop muted playsinline class="media-item" style="width: 100%; height: 100%; object-fit: cover;"><source src="{{ asset('storage/' . $candidate->video_path) }}" type="video/mp4"></video>
                                    @endif
                                </a>
                            </div>
                            <h4 style="font-size: 1.8rem; color: var(--primary); font-family: 'Cormorant Garamond', serif;">{{ $candidate->name }}</h4>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection
