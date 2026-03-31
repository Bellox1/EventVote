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
            
            <div style="display: flex; justify-content: center; gap: 40px; margin-top: 40px;">
                <div style="background: white; padding: 20px 40px; border-radius: 4px; box-shadow: var(--shadow-soft); text-align: center; border-bottom: 3px solid var(--text-dim);">
                    <div style="font-size: 0.7rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 8px;">Total Demandes</div>
                    <div style="font-size: 2rem; font-family: 'Cormorant Garamond', serif; color: var(--primary);">{{ $totalDemandes }}</div>
                </div>
                <div style="background: white; padding: 20px 40px; border-radius: 4px; box-shadow: var(--shadow-soft); text-align: center; border-bottom: 3px solid #10b981;">
                    <div style="font-size: 0.7rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 8px;">Acceptées</div>
                    <div style="font-size: 2rem; font-family: 'Cormorant Garamond', serif; color: var(--primary);">{{ $acceptedDemandes }}</div>
                </div>
                <div style="background: white; padding: 20px 40px; border-radius: 4px; box-shadow: var(--shadow-soft); text-align: center; border-bottom: 3px solid #ef4444;">
                    <div style="font-size: 0.7rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 8px;">Rejetées</div>
                    <div style="font-size: 2rem; font-family: 'Cormorant Garamond', serif; color: var(--primary);">{{ $rejectedDemandes }}</div>
                </div>
                <div style="background: var(--primary); padding: 20px 40px; border-radius: 4px; box-shadow: var(--shadow-soft); text-align: center; border-bottom: 3px solid var(--accent);">
                    <div style="font-size: 0.7rem; font-weight: 700; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 8px;">Revenus Plateforme</div>
                    <div style="font-size: 2rem; font-family: 'Cormorant Garamond', serif; color: white;">{{ number_format($totalRevenue ?? 0, 0, ',', ' ') }} <span style="font-size: 0.9rem; color: var(--accent);">XOF</span></div>
                </div>
            </div>
        </div>

        <!-- TABS -->
        <div style="display: flex; justify-content: center; gap: 60px; border-bottom: 1px solid var(--border); margin-bottom: 60px;">
            <button @click="tab = 'pending'" :class="{ 'active-tab': tab === 'pending' }" class="tab-btn">Demandes en Attente</button>
            <button @click="tab = 'accepted'" :class="{ 'active-tab': tab === 'accepted' }" class="tab-btn">Demandes Acceptées</button>
            <button @click="tab = 'rejected'" :class="{ 'active-tab': tab === 'rejected' }" class="tab-btn">Demandes Rejetées</button>
            <button @click="tab = 'users'" :class="{ 'active-tab': tab === 'users' }" class="tab-btn">Utilisateurs</button>
            <button @click="tab = 'candidates'" :class="{ 'active-tab': tab === 'candidates' }" class="tab-btn">Candidats</button>
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
        </div>

        <!-- ONGLET DEMANDES REJETEES -->
        <div x-show="tab === 'rejected'" x-transition x-cloak>
            @forelse ($rejectedCampaigns as $camp)
                
                <div class="card-admin" style="display: flex; flex-direction: column; opacity: 0.8; filter: grayscale(50%);">
                    
                    @if($camp->video_path)
                        <div style="width: 100%; background: #051a16;">
                            <video controls style="width: 100%; max-height: 400px; display: block; object-fit: contain;">
                                <source src="{{ \Illuminate\Support\Str::startsWith($camp->video_path, 'http') ? $camp->video_path : asset('storage/' . $camp->video_path) }}" type="video/mp4">
                            </video>
                        </div>
                    @endif

                    <div style="display: grid; grid-template-columns: 1fr 1fr; background: white;">
                        <div style="background: #051a16; overflow: hidden; display: flex; align-items: center; justify-content: center; position: relative; border-right: 1px solid var(--border);">
                            @if($camp->image_path)
                                <img src="{{ \Illuminate\Support\Str::startsWith($camp->image_path, 'http') ? $camp->image_path : asset('storage/' . $camp->image_path) }}" style="width: 100%; height: 100%; object-fit: cover; min-height: 350px;">
                            @else
                                <div style="height: 350px; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.2); font-family: 'Cormorant Garamond', serif;">SANS IMAGE</div>
                            @endif

                            <div style="position: absolute; top: 20px; left: 20px;">
                                <span style="background: #ef4444; color: white; padding: 5px 12px; font-size: 0.6rem; font-weight: 700; letter-spacing: 0.1em;">DEMANDE REJETÉE</span>
                            </div>
                        </div>

                        <div style="padding: 40px; border-top: {{ $camp->video_path ? '1px solid var(--border)' : 'none' }}">
                            @include('admin.partials.campaign-info', ['camp' => $camp])
                        </div>
                    </div>
                </div>

            @empty
                <div style="text-align: center; padding: 100px; background: white; border: 1px dashed var(--border);">
                    <p style="color: var(--text-dim);">Aucune demande rejetée dans l'historique.</p>
                </div>
            @endforelse
        </div>

        <!-- ONGLET DEMANDES ACCEPTEES -->
        <div x-show="tab === 'accepted'" x-transition x-cloak>
            @forelse ($acceptedCampaigns as $camp)
                
                <div class="card-admin" style="display: flex; flex-direction: column;">
                    
                    @if($camp->video_path)
                        <div style="width: 100%; background: #051a16;">
                            <video controls style="width: 100%; max-height: 400px; display: block; object-fit: contain;">
                                <source src="{{ \Illuminate\Support\Str::startsWith($camp->video_path, 'http') ? $camp->video_path : asset('storage/' . $camp->video_path) }}" type="video/mp4">
                            </video>
                        </div>
                    @endif

                    <div style="display: grid; grid-template-columns: 1fr 1fr; background: white;">
                        <div style="background: #051a16; overflow: hidden; display: flex; align-items: center; justify-content: center; position: relative; border-right: 1px solid var(--border);">
                            @if($camp->image_path)
                                <img src="{{ \Illuminate\Support\Str::startsWith($camp->image_path, 'http') ? $camp->image_path : asset('storage/' . $camp->image_path) }}" style="width: 100%; height: 100%; object-fit: cover; min-height: 350px;">
                            @else
                                <div style="height: 350px; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.2); font-family: 'Cormorant Garamond', serif;">SANS IMAGE</div>
                            @endif

                            <div style="position: absolute; top: 20px; left: 20px;">
                                <span style="background: #10b981; color: white; padding: 5px 12px; font-size: 0.6rem; font-weight: 700; letter-spacing: 0.1em;">EN COURS ({{ strtoupper($camp->status) }})</span>
                            </div>
                        </div>

                        <div style="padding: 40px; border-top: {{ $camp->video_path ? '1px solid var(--border)' : 'none' }}">
                            @include('admin.partials.campaign-info', ['camp' => $camp])
                        </div>
                    </div>
                </div>

            @empty
                <div style="text-align: center; padding: 100px; background: white; border: 1px dashed var(--border);">
                    <p style="color: var(--text-dim);">Aucune demande acceptée pour le moment.</p>
                </div>
            @endforelse
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

        <!-- ONGLET CANDIDATS -->
        <div x-show="tab === 'candidates'" x-transition x-cloak>
            <div style="background: white; border-radius: 4px; box-shadow: var(--shadow-soft); overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead style="background: var(--primary); color: white; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.2em;">
                        <tr>
                            <th style="padding: 25px;">Candidat</th>
                            <th style="padding: 25px;">Scrutin Associé</th>
                            <th style="padding: 25px;">Propriétaire</th>
                            <th style="padding: 25px; text-align: center;">Statut</th>
                            <th style="padding: 25px; text-align: right;">Inscription</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allCandidates as $candidate)
                            <tr style="border-bottom: 1px solid var(--border); transition: 0.3s;" onmouseover="this.style.background='#F9F6F0'" onmouseout="this.style.background='white'">
                                <td style="padding: 25px;">
                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        @if($candidate->image_path)
                                            <img src="{{ asset('storage/' . $candidate->image_path) }}" style="width: 45px; height: 45px; border-radius: 4px; object-fit: cover;">
                                        @else
                                            <div style="width: 45px; height: 45px; border-radius: 40px; background: var(--border); display: flex; align-items: center; justify-content: center; font-size: 0.9rem; color: var(--primary); font-weight: 700;">{{ substr($candidate->name, 0, 1) }}</div>
                                        @endif
                                        <div>
                                            <div style="font-weight: 700; color: var(--primary); font-size: 0.95rem;">{{ $candidate->name }}</div>
                                            <div style="font-size: 0.7rem; color: var(--text-dim);">{{ $candidate->user->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 25px;">
                                    <div style="font-weight: 600; color: var(--primary); font-size: 0.85rem;">{{ $candidate->campaign->name }}</div>
                                    <div style="font-size: 0.65rem; color: var(--accent); letter-spacing: 0.05em; text-transform: uppercase;">CODE: {{ $candidate->campaign->code }}</div>
                                </td>
                                <td style="padding: 25px;">
                                    <div style="font-size: 0.85rem; color: var(--primary);">{{ $candidate->campaign->creator->name }}</div>
                                </td>
                                <td style="padding: 25px; text-align: center;">
                                    @if($candidate->status === 'pending')
                                        <span style="background: rgba(184, 134, 11, 0.1); color: #B8860B; padding: 5px 12px; font-size: 0.65rem; font-weight: 700; text-transform: uppercase;">En attente</span>
                                    @elseif($candidate->status === 'accepted')
                                        <span style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 5px 12px; font-size: 0.65rem; font-weight: 700; text-transform: uppercase;">Approuvé</span>
                                    @else
                                        <span style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 5px 12px; font-size: 0.65rem; font-weight: 700; text-transform: uppercase;">Rejeté</span>
                                    @endif
                                </td>
                                <td style="padding: 25px; text-align: right; font-size: 0.8rem; color: var(--text-dim);">
                                    {{ $candidate->created_at->format('d M Y') }}
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
                            <th style="padding: 20px; text-align: center;">Total Voix</th>
                            <th style="padding: 20px; text-align: center;">Revenus Brut</th>
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
                                <td style="padding: 20px; text-align: center; font-weight: 800; color: var(--primary);">{{ $s->votes_sum_count ?? 0 }}</td>
                                <td style="padding: 20px; text-align: center; font-weight: 800; color: #10b981;">{{ number_format($s->revenue ?? 0, 0, ',', ' ') }} XOF</td>
                                <td style="padding: 20px; text-align: right; color: var(--accent); font-weight: 700;">
                                    {{ $s->unique_views_count > 0 ? round(($s->votes_count / $s->unique_views_count) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- DERNIERS FLUX FINANCIERS -->
            <div style="margin-top: 60px;">
                <div style="font-family: 'Cormorant Garamond', serif; font-size: 2.2rem; color: var(--primary); margin-bottom: 30px; text-align: center;">Dernières Transactions Confirmées</div>
                <div style="background: white; border-radius: 4px; box-shadow: var(--shadow-soft); overflow: hidden;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead style="background: var(--accent); color: white; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.1em;">
                            <tr>
                                <th style="padding: 20px;">Client</th>
                                <th style="padding: 20px;">Scrutin / Choix</th>
                                <th style="padding: 20px; text-align: right;">Montant</th>
                                <th style="padding: 20px; text-align: center;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions ?? [] as $tx)
                                <tr style="border-bottom: 1px solid var(--border); font-size: 0.9rem;">
                                    <td style="padding: 20px;">
                                        @if($tx->user)
                                            <div style="font-weight: 700; color: var(--primary);">{{ $tx->user->name }}</div>
                                            <div style="font-size: 0.7rem; color: var(--text-dim);">{{ $tx->user->email }}</div>
                                        @else
                                            <div style="font-weight: 700; color: var(--text-dim); font-style: italic;">Anonyme</div>
                                        @endif
                                    </td>
                                    <td style="padding: 20px;">
                                        <div style="font-weight: 600;">{{ $tx->campaign->name }}</div>
                                        <div style="font-size: 0.75rem; color: var(--accent);"><span style="font-weight: 800;">{{ $tx->votes_count }}</span> voix pour {{ $tx->candidate->name }}</div>
                                    </td>
                                    <td style="padding: 20px; text-align: right;">
                                        <div style="font-weight: 800; color: #10b981;">{{ number_format($tx->amount, 0, ',', ' ') }} XOF</div>
                                    </td>
                                    <td style="padding: 20px; text-align: center; color: var(--text-dim); font-size: 0.8rem;">
                                        {{ $tx->created_at->format('d/m à H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr style="border-bottom: 1px solid var(--border);">
                                    <td colspan="4" style="padding: 40px; text-align: center; color: var(--text-dim); font-style: italic;">Aucune transaction bancaire confirmée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
