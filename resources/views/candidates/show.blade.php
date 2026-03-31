@extends('layouts.app')

@section('title', $candidate->name . ' – ' . $campaign->name)

@section('content')
    @php
        $totalVotes = $campaign->votes()->count();
    @endphp

    <div style="margin-bottom: 40px;">
        <a href="{{ route('campaigns.show', $campaign->slug) }}"
            style="text-decoration: none; color: var(--accent); font-weight: 700; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 15px; text-transform: uppercase; letter-spacing: 0.3em; transition: opacity 0.3s;"
            onmouseover="this.style.opacity='0.6'" onmouseout="this.style.opacity='1'">
            &larr; Retour à la campagne
        </a>
    </div>

    <!-- Gallery Component Wrapper -->
    <div x-data="{ 
        showGallery: false, 
        activeIdx: 0,
        globalUi: true,
        uiTimer: null,
        wake() {
            this.globalUi = true;
            clearTimeout(this.uiTimer);
            this.uiTimer = setTimeout(() => {
                const vid = document.querySelector('video[data-gal-vid]');
                if (vid && !vid.paused && this.showGallery) {
                    this.globalUi = false;
                }
            }, 2500);
        },
        items: [
            @if($candidate->image_path)
                { type: 'image', path: '{{ Str::startsWith($candidate->image_path, 'http') ? $candidate->image_path : asset('storage/' . $candidate->image_path) }}' },
            @endif
            @if($candidate->video_path)
                { type: 'video', path: '{{ Str::startsWith($candidate->video_path, 'http') ? $candidate->video_path : asset('storage/' . $candidate->video_path) }}' }
            @endif
        ],
        interval: null,
        next() {
            if (this.items.length <= 1) return;
            this.activeIdx = (this.activeIdx + 1) % this.items.length;
            this.reschedule();
        },
        prev() {
            if (this.items.length <= 1) return;
            this.activeIdx = (this.activeIdx - 1 + this.items.length) % this.items.length;
            this.reschedule();
        },
        reschedule() {
            clearTimeout(this.interval);
            if (!this.showGallery || this.items.length <= 1) return;
            
            const current = this.items[this.activeIdx];
            if (current.type === 'image') {
                this.interval = setTimeout(() => this.next(), 6000);
            }
        },
        open() {
            this.showGallery = true;
            this.activeIdx = 0;
            document.body.style.overflow = 'hidden';
            this.reschedule();
        },
        close() {
            this.showGallery = false;
            document.body.style.overflow = '';
            clearTimeout(this.interval);
        }
    }" @keydown.escape.window="close()">

        <!-- 1. Spotlight Section -->
        <div class="spotlight-section" style="background: var(--primary); padding: 120px 80px; margin: 0 -100px 80px; position: relative; overflow: visible;">
            <style>
                @media (max-width: 768px) {
                    .spotlight-section { padding: 60px 20px !important; margin: 0 -20px 60px !important; }
                    .spotlight-content { gap: 40px !important; }
                    .spotlight-left, .spotlight-right { min-width: 100% !important; flex: 1 !important; text-align: center; }
                    .spotlight-stats { gap: 30px !important; justify-content: center !important; margin-bottom: 40px !important; }
                    .spotlight-stats > div { flex: 1; }
                    .spotlight-stats > div:last-child { border: none !important; }
                    .spotlight-title { font-size: 2.8rem !important; }
                    .spotlight-btn-group { flex-direction: column !important; align-items: stretch !important; gap: 20px !important; }
                    .spotlight-btn { padding: 18px 30px !important; width: 100% !important; }
                    .quote-box { padding: 60px 30px !important; font-size: 1.4rem !important; }
                    .gal-arrow { width: 40px !important; height: 40px !important; left: 10px !important; }
                    .gal-arrow-next { right: 10px !important; }
                }
            </style>
            <div style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; overflow: hidden; pointer-events: none; opacity: 0.05;">
                <div style="position: absolute; top: -50px; right: -50px; width: 400px; height: 400px; border: 1px solid var(--accent); border-radius: 50%;"></div>
            </div>

            <div class="spotlight-content" style="max-width: 1300px; margin: 0 auto; display: flex; gap: 80px; align-items: center; flex-wrap: wrap;">
                <div class="spotlight-left" style="flex: 1.2; min-width: 320px; position: relative; z-index: 10;">
                    <span style="font-size: 0.75rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.4em; display: block; margin-bottom: 30px;">Candidat Officiel — #{{ $candidate->sort_order }}</span>
                    <h1 class="spotlight-title" style="font-size: clamp(2.8rem, 8vw, 5.5rem); color: white; margin: 0 0 45px; line-height: 1.1; font-weight: 300; font-family: 'Cormorant Garamond', serif;">
                        {{ $candidate->name }}. <br> <span style="font-style: italic; font-weight: 400; color: var(--accent);">{{ $campaign->name }}</span>
                    </h1>

                    <div class="spotlight-stats" style="display: flex; gap: 80px; margin-bottom: 70px;">
                        <div>
                            <div style="font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.6); margin-bottom: 12px;">Total de Voix</div>
                            <div style="font-size: 3rem; font-family: 'Cormorant Garamond', serif; color: white;">
                                {{ $candidate->votes_count }}
                                <span style="font-size: 1.3rem; color: var(--accent); font-weight: 600; font-family: 'Jost', sans-serif;">({{ $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 1) : 0 }}%)</span>
                            </div>
                        </div>
                        <div style="width: 1px; background: rgba(212, 174, 109, 0.2);"></div>
                        <div>
                            <div style="font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.6); margin-bottom: 12px;">Classement</div>
                            <div style="font-size: 3rem; font-family: 'Cormorant Garamond', serif; color: white;">#{{ $candidate->sort_order }}</div>
                        </div>
                    </div>

                    @if($campaign->isActive())
                        <div class="spotlight-btn-group" style="display: flex; gap: 25px; align-items: center;">
                            <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
                            <button type="button" class="spotlight-btn" 
                                onclick="buyVotes({{ $candidate->id }}, '{{ addslashes($candidate->name) }}', {{ $campaign->vote_price }})"
                                style="width: 100%; padding: 22px 60px; background: var(--accent); color: white; border: none; font-weight: 700; font-size: 0.8rem; letter-spacing: 0.2em; text-transform: uppercase; cursor: pointer; transition: all 0.4s; box-shadow: 0 15px 40px rgba(0,0,0,0.3);" onmouseover="this.style.background='#b59069'; this.style.transform='translateY(-5px)'" onmouseout="this.style.background='var(--accent)'; this.style.transform='translateY(0)'">
                                VOTER
                            </button>
                            <div @click="open()" style="display: flex; align-items: center; gap: 12px; color: white; cursor: pointer; opacity: 0.7; transition: all 0.3s; flex-shrink: 0;" onmouseover="this.style.opacity='1'; this.style.color='var(--accent)'" onmouseout="this.style.opacity='0.7'; this.style.color='white'">
                                <div style="width: 50px; height: 50px; border: 1px solid currentColor; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                </div>
                                <span class="desktop-only" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3em;">Galerie</span>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="spotlight-right" style="flex: 0.8; min-width: 300px; display: flex; justify-content: center; position: relative;">
                    <div @click="open()" style="padding: 25px; background: white; box-shadow: 0 60px 120px rgba(0,0,0,0.4); border-radius: 2px; z-index: 30; transform: rotate(-1.5deg); cursor: pointer; transition: 0.6s;" onmouseover="this.style.transform='rotate(0deg) scale(1.02)'" onmouseout="this.style.transform='rotate(-1.5deg) scale(1)'">
                         @if($candidate->image_path)
                            <img src="{{ Str::startsWith($candidate->image_path, 'http') ? $candidate->image_path : asset('storage/' . $candidate->image_path) }}" 
                                 style="width: 100%; max-width: 450px;">
                         @else
                            <div style="width: 350px; aspect-ratio: 4/5; background: var(--primary); display: flex; align-items: center; justify-content: center;">
                                <span style="font-family: 'Cormorant Garamond', serif; font-size: 10rem; color: white; opacity: 0.1;">{{ substr($candidate->name, 0, 1) }}</span>
                            </div>
                         @endif
                         <div style="position: absolute; inset: 25px; background: rgba(0,0,0,0.2); opacity: 0; transition: 0.4s; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.6rem; letter-spacing: 0.3em; text-transform: uppercase; font-weight: 700;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">Cliquer pour la galerie</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Message Section -->
        <div style="margin-bottom: 150px;">
            <div style="max-width: 900px; margin: 0 auto; text-align: center;">
                 <span style="font-size: 0.8rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.5em; display: block; margin-bottom: 30px;">Vision & Héritage</span>
                 <h2 style="font-size: clamp(2rem, 5vw, 3.5rem); color: var(--primary); font-family: 'Cormorant Garamond', serif; font-weight: 300; margin-bottom: 60px;">Les Mots de <span style="font-style: italic;">{{ $candidate->name }}.</span></h2>
                 <div class="quote-box" style="background: #F9F6F0; padding: 100px 80px; position: relative; font-family: 'Cormorant Garamond', serif; font-style: italic; font-size: 1.8rem; line-height: 1.9; color: var(--text-dim);">
                    <div style="position: absolute; top: 0; left: 0; padding: 40px; font-size: clamp(5rem, 15vw, 12rem); color: var(--accent); opacity: 0.08;">&ldquo;</div>
                    <p style="position: relative; z-index: 10;">{{ $candidate->description }}</p>
                    <div style="position: absolute; bottom: 0; right: 0; padding: 40px; font-size: clamp(5rem, 15vw, 12rem); color: var(--accent); opacity: 0.08;">&rdquo;</div>
                 </div>
            </div>
        </div>

        <!-- 3. FULLSCREEN GALLERY OVERLAY -->
        <div x-show="showGallery" @mousemove="wake()" @click="wake()" style="position: fixed; inset: 0; z-index: 99999;" x-transition.opacity.duration.300ms>
            <div style="width: 100%; height: 100%; background: #000; display: flex; flex-direction: column;">
                <button x-show="globalUi" x-transition.opacity @click="close()" style="position: absolute; top: 40px; right: 40px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; width: 60px; height: 60px; border-radius: 50%; z-index: 100; cursor: pointer;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>

                <!-- Media Showcase -->
                <div style="flex: 1; position: relative; width: 100%; display: flex; align-items: center; justify-content: center;">
                    <template x-if="items[activeIdx] && items[activeIdx].type === 'image'">
                        <img :src="items[activeIdx].path" style="max-width: 90vw; max-height: 85vh; object-fit: contain; border-radius: 4px;">
                    </template>

                    <template x-if="items[activeIdx] && items[activeIdx].type === 'video'">
                        <div style="position: relative; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #000;">
                            <video controls playsinline autoplay
                                   :src="items[activeIdx].path"
                                   style="max-width: 90vw; max-height: 85vh; object-fit: contain; border-radius: 4px; display: block;">
                            </video>
                        </div>
                    </template>

                    <!-- Nav Arrows -->
                    <button @click="prev()" x-show="items.length > 1 && globalUi" x-transition.opacity class="gal-arrow" style="position: absolute; left: 30px; top: 50%; background: none; border: none; color: white; cursor: pointer; z-index: 1000; opacity: 0.5;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="15 18 9 12 15 6"/></svg>
                    </button>
                    <button @click="next()" x-show="items.length > 1 && globalUi" x-transition.opacity class="gal-arrow gal-arrow-next" style="position: absolute; right: 30px; top: 50%; background: none; border: none; color: white; cursor: pointer; z-index: 1000; opacity: 0.5;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>

                <div x-show="globalUi" x-transition.opacity style="height: 100px; padding: 0 60px; display: flex; justify-content: center; align-items: center; gap: 40px;">
                    <div style="font-size: 0.75rem; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.3em; font-weight: 700;">
                        Collection &bull; <span x-text="activeIdx + 1"></span> Sur <span x-text="items.length"></span>
                    </div>
                </div>
            </div>
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
