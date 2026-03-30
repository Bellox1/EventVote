@extends('layouts.app')

@section('title', 'Direction Plateforme')

@section('content')
    <style>
        .tab-btn {
            background: none; border: none; padding-bottom: 10px; color: var(--text-dim);
            cursor: pointer; font-weight: 700; text-transform: uppercase; transition: 0.3s;
            border-bottom: 2px solid transparent; letter-spacing: 0.1em;
        }
        .active-tab { color: var(--primary); border-bottom: 2px solid var(--primary); }
        .btn-admin-primary { background: var(--primary); color: white; border: none; font-weight: 700; letter-spacing: 0.2em; transition: 0.3s; }
        .btn-admin-primary:hover { background: var(--accent); transform: translateY(-2px); }
        .btn-admin-outline { background: none; border: 1px solid #ef4444; color: #ef4444; font-weight: 700; letter-spacing: 0.2em; transition: 0.3s; }
        .btn-admin-outline:hover { background: #ef4444; color: white; transform: translateY(-2px); }
        
        .card-admin { background: white; border-radius: 4px; box-shadow: var(--shadow-soft); overflow: hidden; border-bottom: 4px solid var(--accent); margin-bottom: 80px; }
        
        [x-cloak] { display: none !important; }
    </style>

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

        <!-- ONGLET DEMANDES EN ATTENTE -->
        <div x-show="tab === 'pending'" x-transition x-cloak>
            @forelse ($pendingCampaigns as $camp)
                
                <div class="card-admin" style="display: flex; flex-direction: column;">
                    
                    <!-- 1. SECTION VIDEO (PLEINE LARGEUR, TOUT EN HAUT) -->
                    @if($camp->video_path)
                        <div style="width: 100%; background: #051a16;">
                            <video controls style="width: 100%; max-height: 400px; display: block; object-fit: contain;">
                                <source src="{{ \Illuminate\Support\Str::startsWith($camp->video_path, 'http') ? $camp->video_path : asset('storage/' . $camp->video_path) }}" type="video/mp4">
                            </video>
                        </div>
                    @endif

                    <!-- 2. SECTION IMAGE (GAUCHE) ET TEXTE (DROITE) -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; background: white;">
                        
                        <!-- Zone Image (Gauche) -->
                        <div style="background: #051a16; overflow: hidden; display: flex; align-items: center; justify-content: center; position: relative; border-right: 1px solid var(--border);">
                            @if($camp->image_path)
                                <img src="{{ \Illuminate\Support\Str::startsWith($camp->image_path, 'http') ? $camp->image_path : asset('storage/' . $camp->image_path) }}" 
                                     style="width: 100%; height: 100%; object-fit: cover; min-height: 350px;">
                            @else
                                <div style="height: 350px; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.2); font-family: 'Cormorant Garamond', serif;">SANS IMAGE</div>
                            @endif

                            @if(!$camp->video_path)
                                <div style="position: absolute; top: 20px; left: 20px;">
                                    <span style="background: var(--accent); color: white; padding: 5px 12px; font-size: 0.6rem; font-weight: 700; letter-spacing: 0.1em;">NOUVELLE DEMANDE</span>
                                </div>
                            @endif
                        </div>

                        <!-- Zone Texte (Droite) -->
                        <div style="padding: 40px; border-top: {{ $camp->video_path ? '1px solid var(--border)' : 'none' }}">
                            @include('admin.partials.campaign-info', ['camp' => $camp])
                        </div>
                    </div>
                </div>

            @empty
                <div style="text-align: center; padding: 100px; background: white; border: 1px dashed var(--border);">
                    <p style="color: var(--text-dim);">Aucune demande en attente.</p>
                </div>
            @endforelse

            <!-- CANDIDATS EN ATTENTE -->
            @if($pendingCandidates->count() > 0)
                <div style="margin-top: 80px; text-align: center; margin-bottom: 40px;">
                    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; color: var(--primary);">Profils de Candidats en <span style="font-style: italic;">Attente.</span></h2>
                    <div class="ornament" style="margin: 20px auto;"></div>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px;">
                    @foreach($pendingCandidates as $candidate)
                        <div class="card-admin" style="margin-bottom: 0;">
                            <div style="aspect-ratio: 1; overflow: hidden; background: #eee;">
                                @if($candidate->image_path)
                                    <img src="{{ \Illuminate\Support\Str::startsWith($candidate->image_path, 'http') ? $candidate->image_path : asset('storage/' . $candidate->image_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @endif
                            </div>
                            <div style="padding: 25px;">
                                <h4 style="font-size: 1.4rem; color: var(--primary); font-family: 'Cormorant Garamond', serif; margin-bottom: 5px;">{{ $candidate->name }}</h4>
                                <div style="font-size: 0.75rem; color: var(--accent); font-weight: 700; text-transform: uppercase; margin-bottom: 15px;">{{ $candidate->campaign->name }}</div>
                                <div style="display: flex; gap: 10px;">
                                    <form action="{{ route('candidate-applications.manage', $candidate->id) }}" method="POST" style="flex: 1;">
                                        @csrf <input type="hidden" name="status" value="accepted">
                                        <button type="submit" class="btn-admin-primary" style="width: 100%; font-size: 0.65rem; padding: 10px;">ACCEPTER</button>
                                    </form>
                                    <form action="{{ route('candidate-applications.manage', $candidate->id) }}" method="POST" style="flex: 1;">
                                        @csrf <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn-admin-outline" style="width: 100%; font-size: 0.65rem; padding: 10px;">REJETER</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- ONGLET UTILISATEURS -->
        <div x-show="tab === 'users'" x-transition x-cloak>
            <div style="background: white; border-radius: 4px; box-shadow: var(--shadow-soft); overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead style="background: var(--primary); color: white; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.2em;">
                        <tr>
                            <th style="padding: 25px;">Utilisateur</th>
                            <th style="padding: 25px;">Email & Téléphone</th>
                            <th style="padding: 25px; text-align: center;">Scrutins Actifs</th>
                            <th style="padding: 25px; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                            <tr style="border-bottom: 1px solid var(--border); transition: 0.3s;" onmouseover="this.style.background='#F9F6F0'" onmouseout="this.style.background='white'">
                                <td style="padding: 25px;">
                                    <div style="font-weight: 700; color: var(--primary);">{{ $u->name }}</div>
                                    <div style="font-size: 0.7rem; color: var(--accent);">{{ $u->isAdmin() ? 'Super Admin' : 'Utilisateur' }}</div>
                                </td>
                                <td style="padding: 25px;">
                                    <div>{{ $u->email }}</div>
                                    <div style="font-size: 0.8rem; color: var(--accent); font-weight: 600;">{{ $u->phone ?? 'Pas de numéro' }}</div>
                                </td>
                                <td style="padding: 25px; text-align: center; font-weight: 700; color: var(--primary);">{{ $u->active_campaigns_count }}</td>
                                <td style="padding: 25px; text-align: right;">
                                    @if(!$u->isAdmin())
                                        <form action="{{ route('admin.users.ban', $u->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" style="background: none; border: none; color: #ef4444; font-size: 0.7rem; font-weight: 700; cursor: pointer;">BANNIR</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ONGLET ANALYSES -->
        <div x-show="tab === 'sessions'" x-transition x-cloak>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-bottom: 60px;">
                <div style="background: white; padding: 40px; border-radius: 4px; border-left: 4px solid var(--accent); box-shadow: var(--shadow-soft);">
                    <div style="font-size: 0.7rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 10px;">Total Campagnes</div>
                    <div style="font-size: 2.2rem; font-family: 'Cormorant Garamond', serif; color: var(--primary);">{{ $campaignsStats->count() }}</div>
                </div>
                <div style="background: white; padding: 40px; border-radius: 4px; border-left: 4px solid var(--accent); box-shadow: var(--shadow-soft);">
                    <div style="font-size: 0.7rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 10px;">Participants Uniques</div>
                    <div style="font-size: 2.2rem; font-family: 'Cormorant Garamond', serif; color: var(--primary);">{{ $campaignsStats->sum('unique_views_count') }}</div>
                </div>
                <div style="background: white; padding: 40px; border-radius: 4px; border-left: 4px solid var(--accent); box-shadow: var(--shadow-soft);">
                    <div style="font-size: 0.7rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 10px;">Total Votes</div>
                    <div style="font-size: 2.2rem; font-family: 'Cormorant Garamond', serif; color: var(--primary);">{{ $campaignsStats->sum('votes_count') }}</div>
                </div>
            </div>

            <div style="background: white; border-radius: 4px; box-shadow: var(--shadow-soft); overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead style="background: var(--primary); color: white; font-size: 0.7rem; text-transform: uppercase;">
                        <tr>
                            <th style="padding: 20px;">Scrutin</th>
                            <th style="padding: 20px; text-align: center;">Vues</th>
                            <th style="padding: 20px; text-align: center;">Votes</th>
                            <th style="padding: 20px; text-align: right;">Conversion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campaignsStats as $s)
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 20px;">
                                    <div style="font-weight: 700; color: var(--primary);">{{ $s->name }}</div>
                                    <div style="font-size: 0.7rem; color: var(--accent);">Créateur : {{ $s->creator->name ?? 'Anon' }}</div>
                                </td>
                                <td style="padding: 20px; text-align: center; font-weight: 700;">{{ $s->unique_views_count }}</td>
                                <td style="padding: 20px; text-align: center; font-weight: 700;">{{ $s->votes_count }}</td>
                                <td style="padding: 20px; text-align: right; color: var(--accent); font-weight: 700;">
                                    {{ $s->unique_views_count > 0 ? round(($s->votes_count / $s->unique_views_count) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
