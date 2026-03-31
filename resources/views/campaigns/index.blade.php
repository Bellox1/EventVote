@extends('layouts.app')

@section('title', 'Explorer les programmes')

@section('content')
    <div style="text-align: center; margin-bottom: 80px;">
        <div
            style="font-size: 0.75rem; font-weight: 600; color: var(--accent); text-transform: uppercase; letter-spacing: 0.4em; margin-bottom: 24px; opacity: 0.9;">
            Excellence & Intégrité</div>
        <h1 style="font-size: clamp(2.2rem, 8vw, 4rem); color: var(--primary); margin-bottom: 20px; font-weight: 300; line-height: 1.1;">Sessions <span
                style="font-style: italic; font-weight: 400;">Ouvertes.</span></h1>
        <div class="ornament" style="margin: 0 auto 32px; width: 60px;"></div>
        <p
            style="color: var(--text-dim); font-size: 1.1rem; max-width: 650px; margin: 0 auto; line-height: 1.8; font-family: 'Jost', sans-serif; padding: 0 15px;">
            Parcourez les scrutins actuellement en cours sur notre plateforme exclusive. Chaque session est un engagement
            vers la transparence et la distinction.</p>
    </div>

    <div id="results" style="max-width: 600px; margin: 0 auto 60px; padding: 0 15px;">
        <form action="{{ route('campaigns.index') }}#results" method="GET" style="position: relative; display: flex; align-items: center;">
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Recherche..." 
                style="width: 100%; padding: 18px 25px; padding-right: 90px; border: 1px solid var(--border); border-radius: 4px; font-family: 'Jost', sans-serif; font-size: 0.95rem; color: var(--primary); background: white; transition: all 0.3s; box-shadow: var(--shadow-soft);"
                onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='var(--shadow-hard)'"
                onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='var(--shadow-soft)'">
            <button type="submit" 
                style="position: absolute; right: 6px; top: 6px; bottom: 6px; background: var(--primary); color: white; border: none; padding: 0 15px; font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; cursor: pointer; transition: all 0.3s; border-radius: 2px;"
                onmouseover="this.style.background='var(--accent)';"
                onmouseout="this.style.background='var(--primary)';"
            >ALLER</button>
        </form>
        @if(request('search'))
            <div style="margin-top: 20px; text-align: center; color: var(--text-dim); font-size: 0.9rem; font-style: italic;">
                Résultats pour : <strong>"{{ request('search') }}"</strong> • <a href="{{ session('last_campaigns_index', route('campaigns.index')) }}#results" style="color: var(--accent); text-decoration: underline;">Effacer</a>
            </div>
        @endif
    </div>

    <div class="grid"
        style="margin-bottom: 120px; display: grid; grid-template-columns: repeat(auto-fill, minmax(min(400px, 100%), 1fr)); gap: 40px;">
        @forelse($campaigns as $campaign)
            <div class="card"
                style="text-decoration: none; display: flex; flex-direction: column; border: none; background: white; padding: 0; overflow: hidden; transition: all 0.5s;">
                
                <div class="event-card" 
                    onmouseenter="let v = this.querySelector('video'); if(v) v.play();"
                    onmouseleave="let v = this.querySelector('video'); if(v) { v.pause(); v.currentTime = 0; }"
                    style="position: relative; aspect-ratio: 4/5; overflow: hidden; background: var(--primary); cursor: pointer;">
                    
                    <!-- Vidéo en arrière-plan (Prête à jouer) -->
                    @if($campaign->video_path)
                        <video src="{{ Str::startsWith($campaign->video_path, 'http') ? $campaign->video_path : asset('storage/' . $campaign->video_path) }}" 
                            muted loop playsinline
                            style="width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; z-index: 1;">
                        </video>
                    @endif

                    <!-- Image par-dessus (S'efface au survol si vidéo présente) -->
                    @if($campaign->image_path)
                        <img src="{{ Str::startsWith($campaign->image_path, 'http') ? $campaign->image_path : asset('storage/' . $campaign->image_path) }}" 
                            style="width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; z-index: 2; transition: all 0.5s ease; {{ $campaign->video_path ? '' : 'filter: brightness(0.9);' }}"
                            @if($campaign->video_path)
                                onmouseenter="this.style.opacity='0'; this.style.transform='scale(1.08)';"
                                onmouseleave="this.style.opacity='1'; this.style.transform='scale(1)';"
                            @else
                                onmouseenter="this.style.transform='scale(1.08)';"
                                onmouseleave="this.style.transform='scale(1)';"
                            @endif
                        >
                    @elseif(!$campaign->video_path)
                        <!-- Placeholder si RIEN n'existe -->
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; position: absolute; inset: 0; z-index: 0;">
                            <span style="font-family: 'Cormorant Garamond', serif; font-size: 5rem; color: white; opacity: 0.1;">#{{ substr($campaign->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>

                <div class="campaign-card-body" style="padding: 30px;">
                    <div
                        style="font-size: 0.7rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.3em; margin-bottom: 20px;">
                        @auth @if(Auth::id() === $campaign->user_id || Auth::user()->isAdmin()) REF: #{{ $campaign->code }} @endif @endauth</div>
                    <h3
                        style="color: var(--primary); font-size: 2rem; margin: 0 0 24px; font-weight: 400; line-height: 1.1;">
                        {{ $campaign->name }}</h3>

                    <p
                        style="color: var(--text-dim); font-size: 1rem; line-height: 1.8; margin-bottom: 40px; flex-grow: 1; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                        {{ $campaign->description }}
                    </p>

                    <div
                        style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: 24px;">
                        <div style="font-size: 0.75rem; font-weight: 600; color: var(--primary); opacity: 0.6;">
                            {{ $campaign->candidates()->count() }} candidat(e)s
                        </div>
                        <a href="{{ route('campaigns.show', $campaign->slug) }}" class="btn btn-outline"
                            style="padding: 10px 20px; font-size: 0.65rem; border-width: 1px;">DÉCOUVRIR</a>
                    </div>
                </div>
            </div>
        @empty
            <div
                style="grid-column: 1 / -1; text-align: center; padding: 120px; background: white; border-radius: var(--radius); border: 1px solid var(--border);">
                <div style="font-size: 3rem; color: var(--accent); margin-bottom: 20px;">✧</div>
                <p
                    style="color: var(--text-dim); font-size: 1.25rem; font-style: italic; font-family: 'Cormorant Garamond', serif;">
                    Aucun scrutin de ce nom "{{ request('search') }}" n'est disponible pour le moment dans cette section.</p>
            </div>
        @endforelse
    </div>

    <div style="margin-top: 80px; display: flex; justify-content: center;">
        {{ $campaigns->withQueryString()->links() }}
    </div>
@endsection
