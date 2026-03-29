@extends('layouts.app')

@php $isWelcome = true; @endphp

@section('welcome-hero')
<!-- Pure Tamarin Hero Slider (Defilement) -->
<div x-data="{ 
        activeSlide: 0, 
        slides: [
            'https://i.pinimg.com/736x/a0/bf/2b/a0bf2b8a050bd6238dbf71ab542ff86f.jpg',
            'https://i.pinimg.com/736x/a4/36/4b/a4364b16b6d013320086f0e2684e7711.jpg',
            'https://i.pinimg.com/1200x/da/d4/eb/dad4ebad3262dd717cdf6066579ecbdb.jpg'
        ],
        next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
        init() { setInterval(() => this.next(), 6000) }
     }" 
     style="height: 120vh; position: relative; margin-top: -100px; overflow: hidden; background: #000;">
    
    <!-- Slides -->
    <template x-for="(slide, index) in slides" :key="index">
        <div x-show="activeSlide === index" 
             x-transition:enter="transition ease-out duration-1200"
             x-transition:enter-start="opacity-0 scale-105"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-1200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="position: absolute; inset: 0; z-index: 1;">
            <img :src="slide" style="width: 100%; height: 100%; object-fit: cover; object-position: top;">
        </div>
    </template>

    <!-- Natural Dark Overlay -->
    <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.3)); z-index: 2;"></div>
    
    <!-- Content Overlay -->
    <div style="position: relative; text-align: center; color: white; padding: 20px; z-index: 10; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 500)" x-show="show" 
             x-transition:enter="transition ease-out duration-1200"
             x-transition:enter-start="opacity-0 translate-y-20"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display: flex; flex-direction: column; align-items: center; text-shadow: 0 4px 30px rgba(0,0,0,0.5);">
            <div style="font-size: 0.7rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.6em; margin-bottom: 30px; opacity: 0.9;">Hautes Décisions • Distinction Pure</div>
            <h1 style="font-size: clamp(3rem, 10vw, 7rem); color: white; margin-bottom: 50px; line-height: 1.05; font-weight: 300; max-width: 1100px; padding: 0 10px;">Voter, soutenez vos proches <br> <span style="font-style: italic; font-weight: 400; color: var(--accent);">ou faites-vous plaisir.</span></h1>
            
            <a href="#campaigns" class="btn" style="padding: 22px 60px; font-size: 0.85rem; background: white; color: var(--primary); border: none; text-transform: uppercase; letter-spacing: 0.3em; font-weight: 700; text-decoration: none; border-radius: 0; transition: all 0.4s;" onmouseover="this.style.transform='scale(1.05)'; this.style.letterSpacing='0.4em'" onmouseout="this.style.transform='scale(1)'; this.style.letterSpacing='0.3em'">Découvrir les Scrutins</a>
        </div>
    </div>

    <!-- Slider Indicators -->
    <div style="position: absolute; bottom: 60px; right: 60px; display: flex; gap: 20px; z-index: 30;">
        <template x-for="(slide, index) in slides" :key="index">
            <button @click="activeSlide = index" 
                    :style="{ background: activeSlide === index ? 'var(--accent)' : 'rgba(255,255,255,0.3)' }"
                    style="width: 8px; height: 8px; border-radius: 50%; border: none; cursor: pointer; transition: all 0.4s;"></button>
        </template>
    </div>

    <!-- Scroll Indicator -->
    <div style="position: absolute; bottom: 60px; left: 50%; transform: translateX(-50%); display: flex; flex-direction: column; align-items: center; gap: 15px; z-index: 20;">
        <span style="color: white; font-size: 0.55rem; letter-spacing: 0.4em; opacity: 0.7; text-transform: uppercase;">Scroll Explore</span>
        <div style="width: 1px; height: 80px; background: linear-gradient(to bottom, white, transparent);"></div>
    </div>
</div>
@endsection

@section('content')
<div id="campaigns" style="padding-top: 100px;">
    <style>
        .event-card:hover .event-info { opacity: 1 !important; transform: translateY(0) !important; }
        .event-card:hover img, .event-card:hover video { transform: scale(1.08); }
    </style>
    <div style="display: flex; flex-direction: column; align-items: center; gap: 20px; margin-bottom: 80px; text-align: center;">
        <span style="font-size: 0.85rem; font-weight: 600; color: var(--accent); text-transform: uppercase; letter-spacing: 0.5em;">Sélection Exclusive</span>
        <h2 style="font-size: clamp(2rem, 5vw, 3.5rem); color: var(--primary); font-weight: 300; margin: 0;">Sessions en <span style="font-style: italic;">Cours.</span></h2>
        <div style="width: 40px; height: 1px; background: var(--accent); margin-top: 25px;"></div>
    </div>

    <!-- 3-Column Grid for Campaigns -->
    <div style="max-width: 1400px; margin: 0 auto 150px; padding: 0 40px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(420px, 1fr)); gap: 30px;">
            @forelse($activeCampaigns as $campaign)
                <div class="event-card" style="position: relative; aspect-ratio: 4/5; overflow: hidden; cursor: pointer; group">
                    
                    <!-- Media -->
                    @if($campaign->image_path)
                        <img src="{{ Str::startsWith($campaign->image_path, 'http') ? $campaign->image_path : asset('storage/' . $campaign->image_path) }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s cubic-bezier(0.19, 1, 0.22, 1); filter: brightness(0.9);">
                    @elseif($campaign->video_path)
                        <div style="width: 100%; height: 100%; overflow: hidden;">
                            <video autoplay loop muted playsinline style="width: 100%; height: 100%; object-fit: cover; filter: brightness(0.9); transition: transform 0.8s cubic-bezier(0.19, 1, 0.22, 1);">
                                <source src="{{ Str::startsWith($campaign->video_path, 'http') ? $campaign->video_path : asset('storage/' . $campaign->video_path) }}" type="video/mp4">
                            </video>
                        </div>
                    @endif
                    
                    <!-- Info Overlay (Appears on hover) -->
                    <div class="event-info" style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,50,41,0.95), rgba(0,50,41,0.4)); display: flex; flex-direction: column; justify-content: flex-end; padding: 40px; opacity: 0; transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1); transform: translateY(20px); z-index: 10;">
                        <span style="font-size: 0.75rem; color: var(--accent); font-weight: 700; letter-spacing: 0.3em; text-transform: uppercase;">Actuellement Disponible</span>
                        <h3 style="color: white; font-family: 'Cormorant Garamond', serif; font-size: 2.2rem; margin: 15px 0 25px; font-weight: 300; line-height: 1.1;">{{ $campaign->name }}</h3>
                        <a href="{{ route('campaigns.show', $campaign->slug) }}" class="btn" style="background: white; color: var(--primary); padding: 15px 35px; border: none; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; text-decoration: none; text-align: center;">Découvrir la session</a>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 100px 0; border: 1px dashed var(--border);">
                     <div style="font-size: 2rem; color: var(--accent); margin-bottom: 20px;">✧</div>
                    <p style="color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.2em;">Aucune session active à afficher.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Participation Privée Section -->
<div id="join-section" style="background: var(--primary); padding: 150px 0; margin: 0 -60px; position: relative; overflow: hidden;">
    <!-- Background Decor (Superposed images) -->
    <div style="position: absolute; left: -100px; top: 10%; width: 500px; height: 500px; opacity: 0.15; border: 1px solid var(--accent); pointer-events: none;"></div>
    <div style="position: absolute; right: -50px; bottom: 5%; width: 400px; height: 400px; background: url('https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80') center/cover; opacity: 0.2; transform: rotate(5deg); pointer-events: none;"></div>

    <div style="max-width: 1200px; margin: 0 auto; padding: 0 40px; position: relative; z-index: 10;">
        <div style="display: flex; gap: 100px; align-items: center; flex-wrap: wrap;">
            
            <!-- Left: Text content -->
            <div style="flex: 1; min-width: 320px;">
                <span style="font-size: 0.85rem; font-weight: 600; color: var(--accent); text-transform: uppercase; letter-spacing: 0.5em; display: block; margin-bottom: 30px;">Accès aux Scrutins</span>
                <h2 style="font-size: clamp(2.5rem, 5vw, 4rem); color: white; font-family: 'Cormorant Garamond', serif; font-weight: 300; margin: 0; line-height: 1.1;">Participation <br> <span style="font-style: italic;">Privée.</span></h2>
                <p style="color: rgba(255,255,255,0.7); font-size: 1.2rem; line-height: 1.9; margin-top: 35px; font-family: 'Cormorant Garamond', serif; font-style: italic; max-width: 500px;">
                    Accédez à votre session de vote sécurisée en saisissant votre code unique. Ce service garantit l'intégrité et l'exclusivité de chaque participation.
                </p>
            </div>

            <!-- Right: Access Card -->
            <div style="flex: 0.8; min-width: 380px;">
                <div style="background: white; padding: 60px; box-shadow: 0 40px 100px rgba(0,0,0,0.3); border-radius: 0; position: relative;">
                    <form action="{{ route('campaigns.join') }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 40px;">
                            <label style="font-size: 0.75rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.2em; display: block; margin-bottom: 20px;">Code d'accès exclusif</label>
                            <input type="text" name="code" placeholder="EX: LUX-VOTE-2024" required style="width: 100%; padding: 20px; border: 1px solid var(--border); border-radius: 0; font-family: 'Jost', sans-serif; font-size: 1rem; letter-spacing: 0.1em; color: var(--primary); background: #F9F9F9; text-transform: uppercase;">
                        </div>
                        <button type="submit" style="width: 100%; padding: 22px; background: var(--primary); color: white; border: none; font-weight: 700; font-size: 0.8rem; letter-spacing: 0.35em; text-transform: uppercase; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.background='var(--primary-light)'" onmouseout="this.style.background='var(--primary)'">
                            Vérifier l'accès
                        </button>
                    </form>
                    
                    <!-- Decorative Corner -->
                    <div style="position: absolute; top: -15px; right: -15px; width: 60px; height: 60px; background: var(--accent); opacity: 0.2; z-index: -1;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
