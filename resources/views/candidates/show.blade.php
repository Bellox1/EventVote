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
        <div style="background: var(--primary); padding: 120px 80px; margin: 0 -100px 100px; position: relative; overflow: visible;">
            <div style="position: absolute; left: 0; top: 0; width: 100%; height: 100%; overflow: hidden; pointer-events: none; opacity: 0.05;">
                <div style="position: absolute; top: -50px; right: -50px; width: 400px; height: 400px; border: 1px solid var(--accent); border-radius: 50%;"></div>
            </div>

            <div style="max-width: 1300px; margin: 0 auto; display: flex; gap: 80px; align-items: center; flex-wrap: wrap;">
                <div style="flex: 1.2; min-width: 450px; position: relative; z-index: 10;">
                    <span style="font-size: 0.8rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.6em; display: block; margin-bottom: 30px;">Candidat Officiel — #{{ $candidate->sort_order }}</span>
                    <h1 style="font-size: clamp(3.5rem, 8vw, 5.5rem); color: white; margin: 0 0 45px; line-height: 1; font-weight: 300; font-family: 'Cormorant Garamond', serif;">
                        {{ $candidate->name }}. <br> <span style="font-style: italic; font-weight: 400; color: var(--accent);">{{ $campaign->name }}</span>
                    </h1>

                    <div style="display: flex; gap: 80px; margin-bottom: 70px;">
                        <div>
                            <div style="font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3em; color: rgba(255,255,255,0.6); margin-bottom: 12px;">Total de Voix</div>
                            <div style="font-size: 3.5rem; font-family: 'Cormorant Garamond', serif; color: white;">
                                {{ $candidate->votes_count }}
                                <span style="font-size: 1.5rem; color: var(--accent); font-weight: 600; font-family: 'Jost', sans-serif;">({{ $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 1) : 0 }}%)</span>
                            </div>
                        </div>
                        <div style="width: 1px; background: rgba(212, 174, 109, 0.2);"></div>
                        <div>
                            <div style="font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3em; color: rgba(255,255,255,0.6); margin-bottom: 12px;">Classement</div>
                            <div style="font-size: 3.5rem; font-family: 'Cormorant Garamond', serif; color: white;">#{{ $candidate->sort_order }}</div>
                        </div>
                    </div>

                    @if($campaign->isActive())
                        <div style="display: flex; gap: 25px; align-items: center;">
                            <form action="{{ route('vote.cast', $campaign->slug) }}" method="POST">
                                @csrf
                                <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
                                <button type="submit" style="padding: 24px 70px; background: var(--accent); color: white; border: none; font-weight: 700; font-size: 0.85rem; letter-spacing: 0.3em; text-transform: uppercase; cursor: pointer; transition: all 0.4s; box-shadow: 0 15px 40px rgba(0,0,0,0.3);" onmouseover="this.style.background='#b59069'; this.style.transform='translateY(-5px)'" onmouseout="this.style.background='var(--accent)'; this.style.transform='translateY(0)'">
                                    SOUTENIR LE CANDIDAT
                                </button>
                            </form>
                            <div @click="open()" style="display: flex; align-items: center; gap: 15px; color: white; cursor: pointer; opacity: 0.7; transition: all 0.3s;" onmouseover="this.style.opacity='1'; this.style.color='var(--accent)'" onmouseout="this.style.opacity='0.7'; this.style.color='white'">
                                <div style="width: 55px; height: 55px; border: 1px solid currentColor; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                </div>
                                <span style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3em;">Voir Médias</span>
                            </div>
                        </div>
                    @endif
                </div>

                <div style="flex: 0.8; min-width: 400px; display: flex; justify-content: center; position: relative;">
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
                 <h2 style="font-size: 3.5rem; color: var(--primary); font-family: 'Cormorant Garamond', serif; font-weight: 300; margin-bottom: 60px;">Le Mot de <span style="font-style: italic;">{{ $candidate->name }}.</span></h2>
                 <div style="background: #F9F6F0; padding: 100px 80px; position: relative; font-family: 'Cormorant Garamond', serif; font-style: italic; font-size: 1.8rem; line-height: 1.9; color: var(--text-dim);">
                    <div style="position: absolute; top: 0; left: 0; padding: 40px; font-size: 12rem; color: var(--accent); opacity: 0.08;">&ldquo;</div>
                    <p style="position: relative; z-index: 10;">{{ $candidate->description }}</p>
                    <div style="position: absolute; bottom: 0; right: 0; padding: 40px; font-size: 12rem; color: var(--accent); opacity: 0.08;">&rdquo;</div>
                 </div>
            </div>
        </div>

        <!-- 3. FULLSCREEN GALLERY OVERLAY -->
        <div x-show="showGallery" style="position: fixed; inset: 0; z-index: 99999;" x-transition.opacity.duration.300ms>
            <div style="width: 100%; height: 100%; background: #000; display: flex; flex-direction: column;">
                <button @click="close()" style="position: absolute; top: 40px; right: 40px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; width: 60px; height: 60px; border-radius: 50%; z-index: 100; cursor: pointer;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>

                <!-- Media Showcase -->
                <div style="flex: 1; position: relative; width: 100%; display: flex; align-items: center; justify-content: center;">
                    <template x-if="items[activeIdx] && items[activeIdx].type === 'image'">
                        <img :src="items[activeIdx].path" style="max-width: 90vw; max-height: 85vh; object-fit: contain; border-radius: 4px;">
                    </template>

                    <template x-if="items[activeIdx] && items[activeIdx].type === 'video'">
                        <div x-data="{ 
                            ui: false, timeout: null,
                            show() { this.ui = true; clearTimeout(this.timeout); this.timeout = setTimeout(() => this.ui = false, 2000); },
                            skip(s) { 
                                $refs.galVid.currentTime += s;
                                this.flash(s > 0 ? 'right' : 'left');
                                this.show();
                            },
                            fL: false, fR: false,
                            flash(side) { side === 'left' ? (this.fL = true, setTimeout(()=>this.fL=false, 500)) : (this.fR = true, setTimeout(()=>this.fR=false, 500)); }
                        }" @mousemove="show()" @mouseleave="ui = false"
                        style="position: relative; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                            
                            <video x-ref="galVid" controls playsinline :src="items[activeIdx].path" @ended="next()"
                                   style="max-width: 90vw; max-height: 85vh; object-fit: contain; border-radius: 4px; pointer-events: auto; cursor: pointer;"
                                   @dblclick="($event.offsetX < $el.clientWidth / 2) ? skip(-10) : skip(10)">
                            </video>

                            <!-- YouTube Overlays -->
                            <div x-show="ui" x-transition.opacity style="position: absolute; inset: 0; pointer-events: none; z-index: 10;">
                                <button @click="skip(-10)" style="pointer-events: auto; position: absolute; left: 40px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); color: white; border: none; width: 70px; height: 70px; border-radius: 50%; cursor: pointer;">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 17L6 12L11 7"/><path d="M18 17L13 12L18 7"/></svg>
                                </button>
                                <button @click="skip(10)" style="pointer-events: auto; position: absolute; right: 40px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); color: white; border: none; width: 70px; height: 70px; border-radius: 50%; cursor: pointer;">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 17L18 12L13 7"/><path d="M6 17L11 12L6 7"/></svg>
                                </button>
                            </div>

                            <div x-show="fL" x-transition style="position: absolute; left: 15%; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.2); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">-10s</div>
                            <div x-show="fR" x-transition style="position: absolute; right: 15%; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.2); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">+10s</div>
                        </div>
                    </template>

                    <!-- Nav Arrows -->
                    <button @click="prev()" x-show="items.length > 1" style="position: absolute; left: 30px; top: 50%; background: none; border: none; color: white; cursor: pointer; z-index: 1000; opacity: 0.5;">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="15 18 9 12 15 6"/></svg>
                    </button>
                    <button @click="next()" x-show="items.length > 1" style="position: absolute; right: 30px; top: 50%; background: none; border: none; color: white; cursor: pointer; z-index: 1000; opacity: 0.5;">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>

                <div style="height: 100px; padding: 0 60px; display: flex; justify-content: center; align-items: center; gap: 40px;">
                    <div style="font-size: 0.75rem; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 0.3em; font-weight: 700;">
                        Collection &bull; <span x-text="activeIdx + 1"></span> Sur <span x-text="items.length"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
