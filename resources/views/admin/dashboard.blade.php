@extends('layouts.app')

@section('title', 'Direction Plateforme')

@section('content')
<div x-data="{ tab: 'pending' }" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
    
    <!-- EN-TETE -->
    <div style="text-align: center; margin-bottom: 80px;">
        <div style="font-family: 'Cormorant Garamond', serif; font-size: 3.5rem; color: var(--primary); margin-bottom: 10px;">Tableau de Bord</div>
        <div class="ornament" style="margin: 0 auto 20px;"></div>
        <p style="color: var(--accent); font-weight: 600; text-transform: uppercase; letter-spacing: 0.3em; font-size: 0.8rem;">Supervision & Analyses de Performance</p>
    </div>

    <!-- TABS -->
    <div style="display: flex; justify-content: center; gap: 60px; border-bottom: 1px solid var(--border); margin-bottom: 60px;">
        <button @click="tab = 'pending'" :class="{ 'active-tab': tab === 'pending' }" class="tab-btn">Demandes en Attente</button>
        <button @click="tab = 'users'" :class="{ 'active-tab': tab === 'users' }" class="tab-btn">Utilisateurs</button>
        <button @click="tab = 'sessions'" :class="{ 'active-tab': tab === 'sessions' }" class="tab-btn">Analyses</button>
    </div>

    <div x-show="tab === 'pending'" x-transition>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 30px;">
            @foreach($pendingCampaigns as $camp)
            <div class="card" x-data="{ showDetails: false }" style="border-bottom: 4px solid var(--accent);">
                <div style="display: flex; justify-content: space-between;">
                    <span class="badge badge-pending">NOUVEAU SCRUTIN</span>
                    <button @click="showDetails = !showDetails" class="btn btn-outline" style="font-size: 0.65rem;">DÉTAILS</button>
                </div>
                <h3 style="font-size: 1.8rem; margin: 15px 0;">{{ $camp->name }}</h3>
                
                <div x-show="showDetails" x-collapse style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border);">
                    @if($camp->video_path)
                    <div style="margin-bottom: 25px;" 
                         x-data="{ 
                            ui: false, timeout: null,
                            show() { this.ui = true; clearTimeout(this.timeout); this.timeout = setTimeout(() => this.ui = false, 2000); },
                            skip(s) { $refs.adVid.currentTime += s; this.f(s > 0 ? 'r' : 'l'); this.show(); },
                            fL: false, fR: false,
                            f(side) { side === 'l' ? (this.fL = true, setTimeout(()=>this.fL=false, 500)) : (this.fR = true, setTimeout(()=>this.fR=false, 500)); }
                         }" @mousemove="show()" @mouseleave="ui = false">
                        
                        <div style="font-size: 0.7rem; font-weight: 700; color: var(--accent); margin-bottom: 10px;">Vidéo reçue</div>
                        <!-- Fixed area container -->
                        <div style="position: relative; border-radius: 4px; overflow: hidden; background: black; display: flex; align-items: center; justify-content: center;">
                            <video x-ref="adVid" controls style="width: 100%; max-height: 300px; display: block; cursor: pointer;"
                                   @dblclick="($event.offsetX < $el.clientWidth / 2) ? skip(-10) : skip(10)">
                                <source src="{{ str_starts_with($camp->video_path, 'http') ? $camp->video_path : asset('storage/' . $camp->video_path) }}" type="video/mp4">
                            </video>

                            <!-- Arrows fixed INSIDE the video box -->
                            <div x-show="ui" x-transition.opacity style="position: absolute; inset: 0; pointer-events: none; z-index: 10; display: flex; align-items: center; justify-content: space-between; padding: 0 20px;">
                                <button @click="skip(-10)" style="pointer-events: auto; background: rgba(0,0,0,0.5); color: white; border: none; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 17L6 12L11 7"/><path d="M18 17L13 12L18 7"/></svg>
                                </button>
                                <button @click="skip(10)" style="pointer-events: auto; background: rgba(0,0,0,0.5); color: white; border: none; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M13 17L18 12L13 7"/><path d="M6 17L11 12L6 7"/></svg>
                                </button>
                            </div>
                            <!-- Flash indicators -->
                            <div x-show="fL" x-transition style="position: absolute; left: 10%; background: rgba(255,255,255,0.2); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">-10s</div>
                            <div x-show="fR" x-transition style="position: absolute; right: 10%; background: rgba(255,255,255,0.2); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">+10s</div>
                        </div>
                    </div>
                    @endif
                </div>

                <div style="display: flex; gap: 15px; margin-top: 25px;">
                    <form id="app-{{ $camp->id }}" action="{{ route('admin.campaigns.manage', $camp->id) }}" method="POST" style="flex: 1;">
                        @csrf <input type="hidden" name="status" value="active">
                        <button type="button" @click="Swal.fire({ title: 'Approuver ?', icon: 'question', showCancelButton: true, confirmButtonColor: '#003229' }).then((r) => { if (r.isConfirmed) document.getElementById('app-{{ $camp->id }}').submit(); })" class="btn btn-primary" style="width: 100%; height: 50px;">APPROUVER</button>
                    </form>
                    <form id="ref-{{ $camp->id }}" action="{{ route('admin.campaigns.manage', $camp->id) }}" method="POST" style="flex: 1;">
                        @csrf <input type="hidden" name="status" value="rejected">
                        <button type="button" @click="Swal.fire({ title: 'Refuser ?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ff4444' }).then((r) => { if (r.isConfirmed) document.getElementById('ref-{{ $camp->id }}').submit(); })" class="btn btn-outline" style="width: 100%; height: 50px; color:#ff4444;">REFUSER</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .tab-btn { background: none; border: none; padding-bottom: 10px; color: var(--text-dim); cursor: pointer; font-weight: 700; text-transform: uppercase; transition: 0.3s; border-bottom: 2px solid transparent; }
    .active-tab { color: var(--primary); border-bottom: 2px solid var(--primary); }
    .card:hover { transform: none; }
</style>
@endsection
