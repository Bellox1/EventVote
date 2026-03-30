@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div style="text-align: center; margin-bottom: 80px;">
    <h1 style="font-size: 4rem; color: var(--primary); margin-bottom: 16px;">Bonjour, {{ Auth::user()->name }}</h1>
    <div class="ornament" style="margin: 0 auto 32px;"></div>
    <p style="color: var(--text-dim); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Supervisez vos demandes de scrutins.</p>
</div>

<div style="display: flex; justify-content: center; margin-bottom: 100px;">
    <a href="{{ route('campaigns.create') }}" class="btn btn-primary" 
        style="padding: 25px 60px; font-size: 0.9rem; letter-spacing: 0.2em; border: 1px solid var(--accent); border-radius: 4px; box-shadow: 0 10px 30px rgba(0, 51, 43, 0.1); background-color: var(--primary); color: white; text-decoration: none;">
        CRÉER UN SCRUTIN D'EXCEPTION
    </a>
</div>

<div style="max-width: 1000px; margin: 0 auto;">

    @if($myPending->isNotEmpty())
        <div style="margin-bottom: 100px;">
            <h2 style="font-size: 1.5rem; color: var(--accent); margin-bottom: 30px; text-transform: uppercase; letter-spacing: 0.3em; text-align: center;">Demandes en validation</h2>
            <div style="display: flex; flex-direction: column; gap: 24px;">
                @foreach($myPending as $campaign)
                    <div class="card" x-data="{ open: false }" style="border-left: 6px solid var(--accent); padding: 30px 40px; background: rgba(212, 174, 109, 0.05); position: relative;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <div style="font-size: 0.65rem; font-weight: 700; color: var(--accent); text-transform: uppercase;">RÉFÉRENCE #{{ $campaign->code }}</div>
                                <h3 style="margin: 0; font-size: 1.6rem; color: var(--primary);">{{ $campaign->name }}</h3>
                                <div style="display: flex; align-items: center; gap: 12px; margin-top: 5px;">
                                    <span style="font-size: 0.75rem; background: var(--border); color: var(--text-dim); padding: 4px 12px; border-radius: 20px; font-weight: 600;">{{ $campaign->status }}</span>
                                </div>
                            </div>
                            <div style="display: flex; gap: 10px;">
                                <a href="{{ route('campaigns.edit', $campaign->slug) }}" class="btn btn-outline" style="font-size: 0.65rem; padding: 5px 12px;">MODIFIER</a>
                                <form id="del-{{ $campaign->id }}" action="{{ route('campaigns.destroy', $campaign->slug) }}" method="POST" style="margin: 0;">
                                    @csrf @method('DELETE')
                                    <button type="button" @click="Swal.fire({ title: 'Annuler ?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ff4444' }).then((r) => { if (r.isConfirmed) document.getElementById('del-{{ $campaign->id }}').submit(); })" class="btn btn-outline" style="font-size: 0.65rem; padding: 5px 12px; color: #ff4444; border-color: #ff4444;">ANNULER</button>
                                </form>
                                <button @click="open = !open" class="btn btn-outline" style="font-size: 0.65rem; padding: 5px 12px;">DÉTAILS</button>
                            </div>
                        </div>

                        <div x-show="open" x-collapse style="margin-top: 30px; padding-top: 25px; border-top: 1px solid var(--border);">
                            <div style="grid-template-columns: 1fr 1fr; display: grid; gap: 40px;">
                                <div>
                                    <div style="font-size: 0.7rem; font-weight: 700; color: var(--accent); text-transform: uppercase;">Description</div>
                                    <p style="font-size: 0.9rem; line-height: 1.6; color: var(--primary);">{{ $campaign->description ?: 'Aucune description' }}</p>
                                </div>
                                <div x-data="{ 
                                    ui: false, timeout: null,
                                    showUI() { this.ui = true; clearTimeout(this.timeout); this.timeout = setTimeout(() => this.ui = false, 2000); },
                                    skip(s) { $refs.uVid.currentTime += s; this.f(s > 0 ? 'r' : 'l'); this.showUI(); },
                                    fL: false, fR: false,
                                    f(side) { side === 'l' ? (this.fL = true, setTimeout(()=>this.fL=false, 500)) : (this.fR = true, setTimeout(()=>this.fR=false, 500)); }
                                }" @mousemove="showUI()" @mouseleave="ui = false" style="position: relative;">
                                    <div style="font-size: 0.7rem; font-weight: 700; color: var(--accent); text-transform: uppercase; margin-bottom: 10px;">Vidéo transmise</div>
                                    @if($campaign->video_path)
                                        <div style="position: relative; border-radius: 4px; overflow: hidden; background: black; display: flex; align-items: center; justify-content: center;">
                                            <video x-ref="uVid" controls style="width: 100%; max-height: 250px; display: block; cursor: pointer;"
                                                   @dblclick="($event.offsetX < $el.clientWidth / 2) ? skip(-10) : skip(10)">
                                                <source src="{{ str_starts_with($campaign->video_path, 'http') ? $campaign->video_path : asset('storage/' . $campaign->video_path) }}" type="video/mp4">
                                            </video>
                                            
                                            <!-- Fixed skip UI -->
                                            <div x-show="ui" x-transition.opacity style="position: absolute; inset: 0; pointer-events: none; z-index: 10; display: flex; align-items: center; justify-content: space-between; padding: 0 20px;">
                                                <button @click="skip(-10)" style="pointer-events: auto; background: rgba(0,0,0,0.5); color: white; border: none; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 17L6 12L11 7"/><path d="M18 17L13 12L18 7"/></svg>
                                                </button>
                                                <button @click="skip(10)" style="pointer-events: auto; background: rgba(0,0,0,0.5); color: white; border: none; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M13 17L18 12L13 7"/><path d="M6 17L11 12L6 7"/></svg>
                                                </button>
                                            </div>

                                            <div x-show="fL" x-transition style="position: absolute; left: 10%; background: rgba(255,255,255,0.2); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem; pointer-events: none;">-10s</div>
                                            <div x-show="fR" x-transition style="position: absolute; right: 10%; background: rgba(255,255,255,0.2); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.8rem; pointer-events: none;">+10s</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <h2 style="font-size: 2.5rem; color: var(--primary); margin-bottom: 40px; text-align: center;">{{ $contextLabel }}</h2>
    <div style="display: flex; flex-direction: column; gap: 32px;">
        @php $displayCampaigns = $myActive->isNotEmpty() ? $myActive : $participations; @endphp
        @forelse($displayCampaigns as $campaign)
            <div class="card" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 4px solid var(--accent); padding: 40px;">
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="font-size: 0.7rem; font-weight: 700; color: var(--accent); text-transform: uppercase;">RÉFÉRENCE #{{ $campaign->code }}</div>
                    <h3 style="margin: 0; font-size: 1.8rem; color: var(--primary);">{{ $campaign->name }}</h3>
                </div>
                <a href="{{ route('campaigns.show', $campaign->slug) }}" class="btn btn-outline" style="padding: 12px 24px; text-decoration: none;">VOIR LE SCRUTIN</a>
            </div>
        @empty
            <div style="padding: 80px; text-align: center; border: 1px solid var(--border);"><p style="color: var(--text-dim); font-style: italic;">Aucun événement actif.</p></div>
        @endforelse
    </div>
</div>
@endsection
