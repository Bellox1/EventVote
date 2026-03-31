@extends('layouts.app')

@section('title', 'Direction Plateforme')

@section('content')
    <style>
        .tab-btn {
            background: none; border: none; padding-bottom: 10px; color: var(--text-dim);
            cursor: pointer; font-weight: 700; text-transform: uppercase; transition: 0.3s;
            border-bottom: 2px solid transparent; letter-spacing: 0.1em;
            white-space: nowrap;
        }
        .active-tab { color: var(--primary); border-bottom: 2px solid var(--primary); }
        .btn-admin-primary { background: var(--primary); color: white; border: none; font-weight: 700; letter-spacing: 0.2em; transition: 0.3s; padding: 12px 25px; border-radius: 4px; }
        .btn-admin-primary:hover { background: var(--accent); transform: translateY(-2px); }
        .btn-admin-outline { background: none; border: 1px solid #ef4444; color: #ef4444; font-weight: 700; letter-spacing: 0.2em; transition: 0.3s; padding: 12px 25px; border-radius: 4px; }
        .btn-admin-outline:hover { background: #ef4444; color: white; transform: translateY(-2px); }
        
        .card-admin { background: white; border-radius: 4px; box-shadow: var(--shadow-soft); overflow: hidden; border-bottom: 4px solid var(--accent); margin-bottom: clamp(40px, 10vw, 80px); }
        
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 250px), 1fr)); gap: 20px; margin-top: 40px; }
        .tab-container { display: flex; justify-content: flex-start; gap: clamp(20px, 4vw, 60px); border-bottom: 1px solid var(--border); margin-bottom: 40px; overflow-x: auto; padding-bottom: 5px; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
        .tab-container::-webkit-scrollbar { display: none; }

        .campaign-layout { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 500px), 1fr)); background: white; }

        @media (max-width: 768px) {
            .campaign-layout { grid-template-columns: 1fr; }
            .tab-container { justify-content: flex-start; }
        }

        [x-cloak] { display: none !important; }
    </style>

    <div x-data="{ tab: 'pending' }" style="max-width: 1400px; margin: 40px auto; padding: 0 15px;">

        <!-- EN-TETE -->
        <div style="text-align: center; margin-bottom: clamp(40px, 10vw, 80px);">
            <div style="font-family: 'Cormorant Garamond', serif; font-size: clamp(2rem, 8vw, 3.5rem); color: var(--primary); margin-bottom: 10px; line-height: 1.2;">Tableau de Bord</div>
            <div class="ornament" style="margin: 0 auto 20px;"></div>
            <p style="color: var(--accent); font-weight: 600; text-transform: uppercase; letter-spacing: 0.3em; font-size: clamp(0.6rem, 2vw, 0.8rem);">Supervision & Analyses de Performance</p>
            
            <div class="stats-grid">
                <div style="background: white; padding: 20px; border-radius: 4px; box-shadow: var(--shadow-soft); text-align: center; border-bottom: 3px solid var(--text-dim);">
                    <div style="font-size: 0.6rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 8px;">Flux Global</div>
                    <div style="font-size: 1.8rem; font-family: 'Cormorant Garamond', serif; color: var(--primary);">{{ number_format($globalStats['total_revenue'] ?? 0, 0, ',', ' ') }} <span style="font-size: 0.7rem;">XOF</span></div>
                </div>
                <div style="background: white; padding: 20px; border-radius: 4px; box-shadow: var(--shadow-soft); text-align: center; border-bottom: 3px solid var(--accent);">
                    <div style="font-size: 0.6rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 8px;">Réservé (2%)</div>
                    <div style="font-size: 1.8rem; font-family: 'Cormorant Garamond', serif; color: var(--primary);">{{ number_format($globalStats['total_reserved'] ?? 0, 0, ',', ' ') }} <span style="font-size: 0.7rem;">XOF</span></div>
                </div>
                <div style="background: white; padding: 20px; border-radius: 4px; box-shadow: var(--shadow-soft); text-align: center; border-bottom: 3px solid #ef4444;">
                    <div style="font-size: 0.6rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 8px;">Frais Agrégateur</div>
                    <div style="font-size: 1.8rem; font-family: 'Cormorant Garamond', serif; color: #ef4444;">{{ number_format($globalStats['total_aggregator'] ?? 0, 0, ',', ' ') }} <span style="font-size: 0.7rem;">XOF</span></div>
                </div>
                <div style="background: var(--primary); padding: 20px; border-radius: 4px; box-shadow: var(--shadow-soft); text-align: center; border-bottom: 3px solid #10b981;">
                    <div style="font-size: 0.6rem; font-weight: 700; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 8px;">Revenu Net Admin</div>
                    <div style="font-size: 1.8rem; font-family: 'Cormorant Garamond', serif; color: white;">{{ number_format($globalStats['total_net_admin'] ?? 0, 0, ',', ' ') }} <span style="font-size: 0.7rem; color: var(--accent);">XOF</span></div>
                </div>
            </div>

            <!-- Aggregator Fee Reference (For Admin) -->
            <div style="margin-top: 40px; background: #f9f6f0; padding: 25px; border-radius: 4px; border: 1px dashed var(--accent); display: inline-block;">
                <div style="font-size: 0.6rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 12px; border-bottom: 1px solid rgba(0,0,0,0.05); padding-bottom: 5px;">Barème Frais Agrégateur (Prélevé sur les 2%)</div>
                <div style="font-size: 0.75rem; color: var(--primary); display: flex; gap: 25px; flex-wrap: wrap; justify-content: center; font-weight: 600;">
                    <span>0 – 10k : <strong style="color:var(--accent);">150 XOF</strong></span>
                    <span>10k – 50k : <strong style="color:var(--accent);">300 XOF</strong></span>
                    <span>50k – 150k : <strong style="color:var(--accent);">800 XOF</strong></span>
                    <span>150k – 500k : <strong style="color:var(--accent);">2000 XOF</strong></span>
                    <span>500k+ : <strong style="color:var(--accent);">2500 XOF</strong></span>
                </div>
            </div>
        </div>

        <!-- TABS -->
        <div class="tab-container">
            <button @click="tab = 'pending'" :class="{ 'active-tab': tab === 'pending' }" class="tab-btn">Attente</button>
            <button @click="tab = 'accepted'" :class="{ 'active-tab': tab === 'accepted' }" class="tab-btn">Acceptées</button>
            <button @click="tab = 'rejected'" :class="{ 'active-tab': tab === 'rejected' }" class="tab-btn">Rejetées</button>
            <button @click="tab = 'users'" :class="{ 'active-tab': tab === 'users' }" class="tab-btn">Utilisateurs</button>
            <button @click="tab = 'banned'" :class="{ 'active-tab': tab === 'banned' }" class="tab-btn">Bannis</button>
            <button @click="tab = 'candidates'" :class="{ 'active-tab': tab === 'candidates' }" class="tab-btn">Candidats</button>
            <button @click="tab = 'sessions'" :class="{ 'active-tab': tab === 'sessions' }" class="tab-btn">Analyses</button>
        </div>

        <!-- ONGLET DEMANDES EN ATTENTE -->
        <div x-show="tab === 'pending'" x-transition x-cloak>
            @forelse ($pendingCampaigns as $camp)
                <div class="card-admin">
                    @if($camp->video_path)
                        <div style="width: 100%; background: #051a16;">
                            <video controls style="width: 100%; max-height: 400px; display: block; object-fit: contain;">
                                <source src="{{ \Illuminate\Support\Str::startsWith($camp->video_path, 'http') ? $camp->video_path : asset('storage/' . $camp->video_path) }}" type="video/mp4">
                            </video>
                        </div>
                    @endif

                    <div class="campaign-layout">
                        <div style="background: #051a16; overflow: hidden; display: flex; align-items: center; justify-content: center; position: relative;">
                            @if($camp->image_path)
                                <img src="{{ \Illuminate\Support\Str::startsWith($camp->image_path, 'http') ? $camp->image_path : asset('storage/' . $camp->image_path) }}" 
                                     style="width: 100%; height: 100%; object-fit: cover; min-height: 300px;">
                            @else
                                <div style="height: 300px; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.2);">SANS IMAGE</div>
                            @endif
                            <div style="position: absolute; top: 15px; left: 15px;">
                                <span style="background: var(--accent); color: white; padding: 4px 10px; font-size: 0.55rem; font-weight: 700; letter-spacing: 0.1em;">NOUVELLE DEMANDE</span>
                            </div>
                        </div>
                        <div style="padding: clamp(20px, 5vw, 40px);">
                            @include('admin.partials.campaign-info', ['camp' => $camp])
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 60px 20px; background: white; border: 1px dashed var(--border);">
                    <p style="color: var(--text-dim);">Aucune demande en attente.</p>
                </div>
            @endforelse
        </div>

        <!-- ONGLET DEMANDES REJETEES -->
        <div x-show="tab === 'rejected'" x-transition x-cloak>
            @forelse ($rejectedCampaigns as $camp)
                <div class="card-admin" style="opacity: 0.9;">
                    <div class="campaign-layout">
                        <div style="background: #051a16; overflow: hidden; display: flex; align-items: center; justify-content: center; position: relative;">
                            @if($camp->image_path)
                                <img src="{{ \Illuminate\Support\Str::startsWith($camp->image_path, 'http') ? $camp->image_path : asset('storage/' . $camp->image_path) }}" style="width: 100%; height: 100%; object-fit: cover; min-height: 300px;">
                            @endif
                            <div style="position: absolute; top: 15px; left: 15px;">
                                <span style="background: #ef4444; color: white; padding: 4px 10px; font-size: 0.55rem; font-weight: 700; letter-spacing: 0.1em;">DEMANDE REJETÉE</span>
                            </div>
                        </div>
                        <div style="padding: clamp(20px, 5vw, 40px);">
                            @include('admin.partials.campaign-info', ['camp' => $camp])
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 60px 20px; background: white; border: 1px dashed var(--border);">
                    <p style="color: var(--text-dim);">Aucun historique de rejet.</p>
                </div>
            @endforelse
        </div>

        <!-- ONGLET DEMANDES ACCEPTEES -->
        <div x-show="tab === 'accepted'" x-transition x-cloak>
            @forelse ($acceptedCampaigns as $camp)
                <div class="card-admin">
                    <div class="campaign-layout">
                        <div style="background: #051a16; overflow: hidden; display: flex; align-items: center; justify-content: center; position: relative;">
                            @if($camp->image_path)
                                <img src="{{ \Illuminate\Support\Str::startsWith($camp->image_path, 'http') ? $camp->image_path : asset('storage/' . $camp->image_path) }}" style="width: 100%; height: 100%; object-fit: cover; min-height: 300px;">
                            @endif
                            <div style="position: absolute; top: 15px; left: 15px;">
                                <span style="background: #10b981; color: white; padding: 4px 10px; font-size: 0.55rem; font-weight: 700; letter-spacing: 0.1em;">{{ strtoupper($camp->status) }}</span>
                            </div>
                        </div>
                        <div style="padding: clamp(20px, 5vw, 40px);">
                            @include('admin.partials.campaign-info', ['camp' => $camp])
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 60px 20px; background: white; border: 1px dashed var(--border);">
                    <p style="color: var(--text-dim);">Aucune demande acceptée.</p>
                </div>
            @endforelse
        </div>

        <!-- ONGLET UTILISATEURS -->
        <div x-show="tab === 'users'" x-transition x-cloak>
            <div class="card" style="padding: 0; overflow-x: auto; background: white;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 700px;">
                    <thead style="background: var(--primary); color: white; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.1em;">
                        <tr>
                            <th style="padding: 15px 25px;">Utilisateur</th>
                            <th style="padding: 15px 25px;">Email & Contact</th>
                            <th style="padding: 15px 25px; text-align: center;">Actifs</th>
                            <th style="padding: 15px 25px; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 20px 25px;">
                                    <div style="font-weight: 700; color: var(--primary); font-size: 0.9rem;">{{ $u->name }}</div>
                                    <div style="font-size: 0.65rem; color: var(--accent);">{{ $u->isAdmin() ? 'Super Admin' : 'Hôte' }}</div>
                                </td>
                                <td style="padding: 20px 25px;">
                                    <div style="font-size: 0.85rem;">{{ $u->email }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-dim);">{{ $u->phone ?? '---' }}</div>
                                </td>
                                <td style="padding: 20px 25px; text-align: center; font-weight: 700; color: var(--primary);">{{ $u->active_campaigns_count }}</td>
                                <td style="padding: 20px 25px; text-align: right;">
                                    @if(!$u->isAdmin())
                                        <form action="{{ route('admin.users.ban', $u->id) }}" method="POST" 
                                              onsubmit="event.preventDefault(); Swal.fire({
                                                  title: 'Bannir {{ $u->name }} ?',
                                                  text: 'L\'utilisateur ne pourra plus accéder à son compte.',
                                                  icon: 'warning',
                                                  showCancelButton: true,
                                                  confirmButtonColor: '#ef4444',
                                                  cancelButtonColor: 'var(--text-dim)',
                                                  confirmButtonText: 'OUI, BANNIR',
                                                  cancelButtonText: 'ANNULER'
                                              }).then((result) => { if (result.isConfirmed) { this.submit(); } });">
                                            @csrf
                                            <button type="submit" style="background: none; border: none; color: #ef4444; font-size: 0.65rem; font-weight: 700; cursor: pointer;">BANNIR</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ONGLET BANNIS -->
        <div x-show="tab === 'banned'" x-transition x-cloak>
            <div class="card" style="padding: 0; overflow-x: auto; background: white;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 700px;">
                    <thead style="background: #ef4444; color: white; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.1em;">
                        <tr>
                            <th style="padding: 15px 25px;">Utilisateur</th>
                            <th style="padding: 15px 25px;">Email & Contact</th>
                            <th style="padding: 15px 25px; text-align: center;">Actifs</th>
                            <th style="padding: 15px 25px; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bannedUsers as $u)
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 20px 25px;">
                                    <div style="font-weight: 700; color: #ef4444; font-size: 0.9rem;">{{ $u->name }}</div>
                                    <div style="font-size: 0.65rem; color: var(--accent);">{{ $u->isAdmin() ? 'Super Admin' : 'Hôte' }}</div>
                                </td>
                                <td style="padding: 20px 25px;">
                                    <div style="font-size: 0.85rem;">{{ $u->email }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-dim);">{{ $u->phone ?? '---' }}</div>
                                </td>
                                <td style="padding: 20px 25px; text-align: center; font-weight: 700; color: #ef4444;">{{ $u->active_campaigns_count }}</td>
                                <td style="padding: 20px 25px; text-align: right;">
                                    <form action="{{ route('admin.users.unban', $u->id) }}" method="POST" 
                                          onsubmit="event.preventDefault(); Swal.fire({
                                              title: 'Restaurer {{ $u->name }} ?',
                                              text: 'L\'utilisateur pourra de nouveau accéder à son compte.',
                                              icon: 'question',
                                              showCancelButton: true,
                                              confirmButtonColor: '#10b981',
                                              cancelButtonColor: 'var(--text-dim)',
                                              confirmButtonText: 'OUI, RESTAURER',
                                              cancelButtonText: 'ANNULER'
                                          }).then((result) => { if (result.isConfirmed) { this.submit(); } });">
                                        @csrf
                                        <button type="submit" style="background: none; border: none; color: #10b981; font-size: 0.65rem; font-weight: 700; cursor: pointer;">RESTAURER</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 60px 20px; color: var(--text-dim);">
                                    Aucun compte banni pour le moment.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ONGLET CANDIDATS -->
        <div x-show="tab === 'candidates'" x-transition x-cloak>
            <div class="card" style="padding: 0; overflow-x: auto; background: white;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 900px;">
                    <thead style="background: var(--primary); color: white; text-transform: uppercase; font-size: 0.65rem;">
                        <tr>
                            <th style="padding: 15px 25px;">Candidat</th>
                            <th style="padding: 15px 25px;">Scrutin Associé</th>
                            <th style="padding: 15px 25px; text-align: center;">Statut</th>
                            <th style="padding: 15px 25px; text-align: right;">Inscription</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allCandidates as $candidate)
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 20px 25px;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; background: #f0f0f0;">
                                            @if($candidate->image_path)
                                                <img src="{{ \Illuminate\Support\Str::startsWith($candidate->image_path, 'http') ? $candidate->image_path : asset('storage/' . $candidate->image_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:0.8rem; color:var(--primary);">{{ substr($candidate->name, 0, 1) }}</div>
                                            @endif
                                        </div>
                                        <div style="font-weight: 700; color: var(--primary); font-size: 0.9rem;">{{ $candidate->name }}</div>
                                    </div>
                                </td>
                                <td style="padding: 20px 25px;">
                                    <div style="font-size: 0.85rem; font-weight: 600;">{{ $candidate->campaign->name }}</div>
                                    <div style="font-size: 0.65rem; color: var(--accent);">CODE: {{ $candidate->campaign->code }}</div>
                                </td>
                                <td style="padding: 20px 25px; text-align: center;">
                                    <span style="padding: 4px 10px; border-radius: 4px; font-size: 0.6rem; font-weight: 700; text-transform: uppercase; 
                                        @if($candidate->status === 'accepted') background: rgba(16, 185, 129, 0.1); color: #10b981; 
                                        @elseif($candidate->status === 'pending') background: rgba(184, 134, 11, 0.1); color: #B8860B;
                                        @else background: rgba(239, 68, 68, 0.1); color: #ef4444; @endif">
                                        {{ $candidate->status }}
                                    </span>
                                </td>
                                <td style="padding: 20px 25px; text-align: right; font-size: 0.75rem; color: var(--text-dim);">
                                    {{ $candidate->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ONGLET ANALYSES -->
        <div x-show="tab === 'sessions'" x-transition x-cloak>
            <div class="stats-grid" style="margin-bottom: 60px;">
                <div style="background: white; padding: 25px; border-radius: 4px; border-left: 4px solid var(--accent); box-shadow: var(--shadow-soft);">
                    <div style="font-size: 0.6rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase;">Total Campagnes</div>
                    <div style="font-size: 1.8rem; font-family: 'Cormorant Garamond', serif; color: var(--primary);">{{ $campaignsStats->count() }}</div>
                </div>
                <div style="background: white; padding: 25px; border-radius: 4px; border-left: 4px solid var(--accent); box-shadow: var(--shadow-soft);">
                    <div style="font-size: 0.6rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase;">Visiteurs Uniques</div>
                    <div style="font-size: 1.8rem; font-family: 'Cormorant Garamond', serif; color: var(--primary);">{{ $campaignsStats->sum('unique_views_count') }}</div>
                </div>
                <div style="background: white; padding: 25px; border-radius: 4px; border-left: 4px solid var(--accent); box-shadow: var(--shadow-soft);">
                    <div style="font-size: 0.6rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase;">Total Votes</div>
                    <div style="font-size: 1.8rem; font-family: 'Cormorant Garamond', serif; color: var(--primary);">{{ $campaignsStats->sum('votes_count') }}</div>
                </div>
            </div>

            <div class="card" style="padding: 0; overflow-x: auto; background: white;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 800px;">
                    <thead style="background: var(--primary); color: white; font-size: 0.65rem; text-transform: uppercase;">
                        <tr>
                            <th style="padding: 15px 25px;">Scrutin</th>
                            <th style="padding: 15px 25px; text-align: center;">Total</th>
                            <th style="padding: 15px 25px; text-align: center;">Réservé (2%)</th>
                            <th style="padding: 15px 25px; text-align: center;">Agrégateur</th>
                            <th style="padding: 15px 25px; text-align: center;">Bénéfice Net</th>
                            <th style="padding: 15px 25px; text-align: right;">Net Créateur</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campaignsStats as $s)
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 15px 25px;">
                                    <div style="font-weight: 700; color: var(--primary); font-size: 0.85rem;">{{ $s->name }}</div>
                                    <div style="font-size: 0.6rem; color: var(--accent);">Hôte: {{ $s->creator->name ?? '---' }}</div>
                                </td>
                                <td style="padding: 15px 25px; text-align: center; font-weight: 700;">{{ number_format($s->total_amount ?? 0, 0, ',', ' ') }}</td>
                                <td style="padding: 15px 25px; text-align: center; color: var(--accent);">{{ number_format($s->site_fee, 0, ',', ' ') }}</td>
                                <td style="padding: 15px 25px; text-align: center; color: #ef4444;">{{ number_format($s->aggregator_fee, 0, ',', ' ') }}</td>
                                <td style="padding: 15px 25px; text-align: center; font-weight: 700; color: #10b981;">{{ number_format($s->net_admin, 0, ',', ' ') }}</td>
                                <td style="padding: 15px 25px; text-align: right; color: var(--primary); font-weight: 700;">
                                    {{ number_format($s->creator_net, 0, ',', ' ') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- TRANSACTIONS -->
            <div style="margin-top: 60px;">
                <div style="font-family: 'Cormorant Garamond', serif; font-size: 2rem; color: var(--primary); margin-bottom: 30px; text-align: center;">Transactions Récentes</div>
                <div class="card" style="padding: 0; overflow-x: auto; background: white;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 800px;">
                        <thead style="background: var(--accent); color: white; text-transform: uppercase; font-size: 0.6rem;">
                            <tr>
                                <th style="padding: 15px 25px;">Client</th>
                                <th style="padding: 15px 25px;">Campagne / Choix</th>
                                <th style="padding: 15px 25px; text-align: right;">Montant</th>
                                <th style="padding: 15px 25px; text-align: center;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions ?? [] as $tx)
                                <tr style="border-bottom: 1px solid var(--border); font-size: 0.85rem;">
                                    <td style="padding: 15px 25px;">
                                        <div style="font-weight: 700;">{{ $tx->user->name ?? 'Anonyme' }}</div>
                                        <div style="font-size: 0.65rem; color: var(--text-dim);">{{ $tx->user->email ?? '' }}</div>
                                    </td>
                                    <td style="padding: 15px 25px;">
                                        <div style="font-weight: 600;">{{ $tx->campaign->name }}</div>
                                        <div style="font-size: 0.7rem; color: var(--accent);">{{ $tx->votes_count }} voix pour {{ $tx->candidate->name }}</div>
                                    </td>
                                    <td style="padding: 15px 25px; text-align: right; font-weight: 800; color: #10b981;">{{ number_format($tx->amount, 0, ',', ' ') }} XOF</td>
                                    <td style="padding: 15px 25px; text-align: center; color: var(--text-dim); font-size: 0.75rem;">{{ $tx->created_at->format('d/m H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" style="padding: 40px; text-align: center; color: var(--text-dim);">Aucun flux financier.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
