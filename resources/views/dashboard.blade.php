@extends('layouts.app')

@section('title', 'Mon Espace')

@section('content')
<style>
    .card-admin { background: white; border-radius: 4px; box-shadow: var(--shadow-soft); overflow: hidden; border-bottom: 4px solid var(--accent); margin-bottom: 60px; }
    .btn-action { padding: 12px 25px; font-weight: 700; font-size: 0.75rem; letter-spacing: 0.1em; text-transform: uppercase; cursor: pointer; transition: 0.3s; text-decoration: none; display: inline-block; text-align: center; }
    .btn-primary-admin { background: var(--primary); color: white; border: none; }
    .btn-primary-admin:hover { background: var(--accent); transform: translateY(-2px); }
    .btn-outline-admin { background: none; border: 1px solid var(--accent); color: var(--accent); }
    .btn-outline-admin:hover { background: var(--accent); color: white; transform: translateY(-2px); }
    .btn-danger-admin { background: none; border: 1px solid #ef4444; color: #ef4444; }
    .btn-danger-admin:hover { background: #ef4444; color: white; transform: translateY(-2px); }
    
    .tab-btn { background: none; border: none; padding-bottom: 10px; color: var(--text-dim); cursor: pointer; font-weight: 700; text-transform: uppercase; transition: 0.3s; border-bottom: 2px solid transparent; letter-spacing: 0.1em; }
    .tab-btn.active-tab { color: var(--primary) !important; border-bottom: 2px solid var(--primary) !important; }
    [x-cloak] { display: none !important; }
    .live-blink { animation: blink-animation 1.5s steps(5, start) infinite; -webkit-animation: blink-animation 1.5s steps(5, start) infinite; }
    @keyframes blink-animation { to { visibility: hidden; } }
    @-webkit-keyframes blink-animation { to { visibility: hidden; } }
</style>

<div style="text-align: center; margin-bottom: 60px; padding: 0 15px;">
    <div style="font-family: 'Cormorant Garamond', serif; font-size: clamp(2rem, 8vw, 4rem); color: var(--primary); margin-bottom: 10px; line-height: 1.1;">Bonjour, {{ Auth::user()->name }}</div>
    <div class="ornament" style="margin: 0 auto 20px;"></div>
    <p style="color: var(--accent); font-weight: 600; text-transform: uppercase; letter-spacing: 0.3em; font-size: clamp(0.6rem, 3vw, 0.8rem);">Gestion de vos sessions & participations</p>
</div>

<div style="display: flex; flex-direction: column; align-items: center; gap: 30px; margin-bottom: 80px; padding: 0 20px;">
    <a href="{{ route('campaigns.create') }}" class="btn-action btn-primary-admin" style="padding: 18px 50px; font-size: 0.85rem; letter-spacing: 0.3em; color: white;">
        + Créer un nouveau scrutin
    </a>

    <div style="font-size: 0.7rem; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.3em; margin-bottom: -15px;">OU PARTICIPER À UN SCRUTIN EXISTANT</div>

    <!-- Rejoindre un scrutin (Devenir Candidat) -->
    <form x-data="{ code: '' }" @submit.prevent="
        let val = code.trim();
        if (!val) return;
        if (val.includes('campaigns/')) {
            let split = val.split('campaigns/');
            let slug = split[1].split('/')[0];
            window.location.href = '/campaigns/' + slug + '/apply';
        } else {
            window.location.href = '/campaigns/' + encodeURIComponent(val) + '/apply';
        }" 
        style="display: flex; align-items: center; justify-content: space-between; gap: 10px; background: white; padding: 8px; border-radius: 50px; box-shadow: var(--shadow-soft); max-width: 500px; width: 100%; border: 1px solid rgba(212, 174, 109, 0.3);">
        
        <input type="text" x-model="code" placeholder="Lien ou code de la campagne..." required
               style="flex: 1; border: none; padding: 12px 15px; outline: none; border-radius: 40px; font-family: 'Jost', sans-serif; font-size: 0.9rem; color: var(--primary); background: transparent; min-width: 0;">
        
        <button type="submit" class="btn-action btn-outline-admin" style="padding: 12px 20px; border-radius: 40px; font-size: 0.75rem; white-space: nowrap;">
            Rejoindre
        </button>
    </form>
</div>

<div x-data="{ tab: '{{ $myPending->isNotEmpty() ? 'pending' : 'active' }}' }" style="max-width: 1100px; margin: 0 auto; padding-bottom: 100px;">

    <!-- TABS -->
    <div style="display: flex; justify-content: center; gap: clamp(20px, 5vw, 60px); border-bottom: 1px solid var(--border); margin-bottom: 60px; flex-wrap: wrap; padding: 0 10px;">
        @if($myPending->isNotEmpty())
            <button @click="tab = 'pending'" :class="{ 'active-tab': tab === 'pending' }" class="tab-btn">Demandes en Attente ({{ $myPending->count() }})</button>
        @endif
        <button @click="tab = 'active'" :class="{ 'active-tab': tab === 'active' }" class="tab-btn">{{ $contextLabel }}</button>
        @if($myCandidacies->isNotEmpty())
            <button @click="tab = 'candidacies'" :class="{ 'active-tab': tab === 'candidacies' }" class="tab-btn">Mes Candidatures ({{ $myCandidacies->count() }})</button>
        @endif
        <button @click="tab = 'votes'" :class="{ 'active-tab': tab === 'votes' }" class="tab-btn">Mes Votes</button>
    </div>

    {{-- 1. SECTION : MES DEMANDES EN ATTENTE --}}
    @if($myPending->isNotEmpty())
        <div x-show="tab === 'pending'" x-transition x-cloak style="margin-bottom: 80px;">
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.3em; margin-bottom: 30px; text-align: center;">Demandes en Validation</div>
            
            @foreach($myPending as $camp)
                <div class="card-admin">
                    <!-- VIDEO HAUT -->
                    @if($camp->video_path)
                        <div style="width: 100%; background: #051a16;">
                            <video controls style="width: 100%; max-height: 400px; display: block; object-fit: contain;">
                                <source src="{{ \Illuminate\Support\Str::startsWith($camp->video_path, 'http') ? $camp->video_path : asset('storage/' . $camp->video_path) }}" type="video/mp4">
                            </video>
                        </div>
                    @endif

                    <!-- IMAGE ET INFOS -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 400px), 1fr)); background: white;">
                        <div style="background: #051a16; overflow: hidden; display: flex; align-items: center; justify-content: center; border-right: 1px solid var(--border);">
                            @if($camp->image_path)
                                <img src="{{ \Illuminate\Support\Str::startsWith($camp->image_path, 'http') ? $camp->image_path : asset('storage/' . $camp->image_path) }}" style="width: 100%; height: 100%; object-fit: cover; min-height: 350px;">
                            @else
                                <div style="height: 350px; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.1); font-family: 'Cormorant Garamond', serif;">SANS IMAGE</div>
                            @endif
                        </div>
                        
                        <div style="padding: 40px; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                @if(Auth::id() === $camp->user_id || Auth::user()->isAdmin())
                                <div style="font-size: 0.7rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.2em;">RÉFÉRENCE #{{ $camp->code }}</div>
                                @endif
                                <h3 style="font-size: clamp(1.4rem, 6vw, 1.8rem); color: var(--primary); font-family: 'Cormorant Garamond', serif; margin-top: 10px; line-height: 1.2;">{{ $camp->name }}</h3>
                                <div style="margin-top: 15px;">
                                    @php
                                        $badges = [
                                            'pending' => ['#f59e0b', 'EN ATTENTE D\'EXAMEN'],
                                            'rejected' => ['#ef4444', 'DEMANDE REJETÉE'],
                                            'paused' => ['#6b7a77', 'SESSION EN PAUSE'],
                                            'ended' => ['var(--text-dim)', 'SESSION CLÔTURÉE'],
                                            'active' => ['var(--primary)', 'SESSION ACTIVE']
                                        ];
                                        $badge = $badges[$camp->status] ?? ['var(--border)', $camp->status];
                                    @endphp
                                    <span style="font-size: 0.65rem; background: {{ $badge[0] }}; color: white; padding: 4px 12px; border-radius: 20px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em;">{{ $badge[1] }}</span>
                                </div>
                                <div style="display: flex; gap: 20px; margin-top: 20px;">
                                    <span style="font-size: 0.65rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.1em;">PRIX : <span style="color: var(--primary);">{{ $camp->vote_price == 0 ? 'GRATUIT' : number_format($camp->vote_price, 0, ',', ' ') . ' FCFA' }}</span></span>
                                    @if($camp->bank_account)
                                        <span style="font-size: 0.65rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.1em;">COMPTE : <span style="color: var(--primary);">{{ $camp->bank_account }}</span></span>
                                    @endif
                                </div>
                                @if($camp->status === 'rejected' && $camp->rejection_reason)
                                    <div style="background-color: #fef2f2; padding: 15px; border-left: 3px solid #ef4444; margin-top: 20px;">
                                        <div style="font-size: 0.6rem; text-transform: uppercase; color: #991b1b; font-weight: 700; letter-spacing: 0.1em; margin-bottom: 5px;">Motif de rejet (Administrateur)</div>
                                        <div style="font-size: 0.85rem; color: #991b1b; font-style: italic;">"{{ $camp->rejection_reason }}"</div>
                                    </div>
                                @endif
                                <div style="display: flex; gap: 15px; margin-top: 25px;">
                                    <div style="padding: 10px 20px; background: #f9f6f0; border-radius: 4px; display: flex; gap: 20px;">
                                        <div style="font-size: 0.7rem; font-weight: 700; color: #10b981;">{{ $camp->candidates->count() }} APPROUVÉS</div>
                                        <div style="width: 1px; background: var(--border);"></div>
                                        <div style="font-size: 0.7rem; font-weight: 700; color: #f59e0b;">{{ $camp->allCandidates->where('status', 'pending')->count() }} EN ATTENTE</div>
                                    </div>
                                </div>
                                <p style="color: var(--text-dim); font-size: 0.9rem; line-height: 1.7; margin-top: 20px;">{{ $camp->description }}</p>
                            </div>

                            <div style="display: flex; gap: 12px; margin-top: 30px; align-items: stretch; flex-wrap: wrap;">
                                <a href="{{ route('campaigns.manage', $camp->slug) }}" class="btn-action btn-outline-admin" style="flex: 1; display: flex; align-items: center; justify-content: center; border-color: var(--primary); color: var(--primary);">Gérer</a>
                                <a href="{{ route('campaigns.edit', $camp->slug) }}" class="btn-action btn-outline-admin" style="flex: 1; display: flex; align-items: center; justify-content: center;">Modifier</a>
                                <form id="del-{{ $camp->id }}" action="{{ route('campaigns.destroy', $camp->slug) }}" method="POST" style="flex: 1; display: flex; margin: 0;">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="Swal.fire({ title: 'Annuler la demande ?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444' }).then((r) => { if (r.isConfirmed) document.getElementById('del-{{ $camp->id }}').submit(); })" class="btn-action btn-danger-admin" style="width: 100%; display: flex; align-items: center; justify-content: center;">Annuler</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- 2. SECTION : SESSIONS ACTIVES / PARTICIPATIONS --}}
    <div x-show="tab === 'active'" x-transition x-cloak x-data="{
        campaigns: {{ $myActive->map(fn($c) => ['name' => $c->name, 'slug' => $c->slug])->values() }},
        selectedSlug: '{{ $myActive->first() ? $myActive->first()->slug : '' }}',
        stats: { labels: [], datasets: [], total_amount: 0, total_votes: 0, recent_votes: [], top_3: [] },
        chart: null,
        loading: false,
        
        async fetchStats() {
            if (!this.selectedSlug) return;
            this.loading = true;
            try {
                const response = await fetch(`/api/campaigns/${this.selectedSlug}/stats`);
                this.stats = await response.json();
                this.updateChart();
            } catch (e) {
                console.error('Erreur stats:', e);
            } finally {
                this.loading = false;
            }
        },

        initChart() {
            const ctx = document.getElementById('evolutionChart').getContext('2d');
            this.chart = new Chart(ctx, {
                type: 'line',
                data: { labels: this.stats.labels, datasets: this.stats.datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: true, position: 'bottom', labels: { font: { family: 'Jost' }, color: '#003229' } } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0, 50, 41, 0.05)' }, ticks: { font: { family: 'Jost' } } },
                        x: { grid: { display: false }, ticks: { font: { family: 'Jost' } } }
                    }
                }
            });
        },

        updateChart() {
            if (!this.chart) return;
            this.chart.data.labels = this.stats.labels;
            this.chart.data.datasets = this.stats.datasets;
            this.chart.update('none');
        },

        init() {
            if (this.selectedSlug) {
                this.fetchStats().then(() => this.initChart());
                setInterval(() => {
                    if (this.tab === 'active') this.fetchStats();
                }, 5000);
            }
        }
    }">
        @php $displayCampaigns = $myActive->isNotEmpty() ? $myActive : ($participations ?? collect()); @endphp

        {{-- ANALYTIQUES INTEGRÉS (Seulement si sessions actives existent) --}}
        @if($myActive->isNotEmpty())
        <div style="display: flex; flex-direction: column; gap: 40px; margin-bottom: 80px;">
            <!-- Selector and Header -->
            <div style="display: flex; justify-content: space-between; align-items: center; background: white; padding: 30px; border-radius: 4px; box-shadow: var(--shadow-soft); border-left: 5px solid var(--accent); flex-wrap: wrap; gap: 20px;">
                <div>
                    <h2 style="font-family: 'Cormorant Garamond', serif; font-size: clamp(1.5rem, 6vw, 2.2rem); color: var(--primary); margin: 0;">Performances Live</h2>
                    <p style="font-size: 0.75rem; color: var(--accent); text-transform: uppercase; letter-spacing: 0.2em; margin-top: 5px;">Mise à jour toutes les 5 secondes</p>
                </div>
                <select x-model="selectedSlug" @change="fetchStats()" style="padding: 12px 25px; border: 1px solid var(--border); border-radius: 40px; font-family: 'Jost', sans-serif; font-weight: 600; color: var(--primary); cursor: pointer; outline: none;">
                    <template x-for="c in campaigns" :key="c.slug">
                        <option :value="c.slug" x-text="c.name"></option>
                    </template>
                </select>
            </div>

            <!-- KPI Cards -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div style="background: white; padding: 30px; border-radius: 4px; box-shadow: var(--shadow-soft); border-bottom: 3px solid #10b981;">
                    <div style="font-size: 0.65rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 10px;">Revenus Totaux</div>
                    <div style="display: flex; align-items: baseline; gap: 10px;">
                        <span x-text="stats.total_amount" style="font-size: 2.2rem; font-weight: 700; color: var(--primary); font-family: 'Cormorant Garamond', serif;">0</span>
                        <span style="font-size: 0.9rem; font-weight: 600; color: var(--accent);">FCFA</span>
                    </div>
                </div>
                <div style="background: white; padding: 30px; border-radius: 4px; box-shadow: var(--shadow-soft); border-bottom: 3px solid var(--accent);">
                    <div style="font-size: 0.65rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 10px;">Total des Voix</div>
                    <div x-text="stats.total_votes" style="font-size: 2.2rem; font-weight: 700; color: var(--primary); font-family: 'Cormorant Garamond', serif;">0</div>
                </div>
                <template x-if="stats.top_3 && stats.top_3[0]">
                    <div style="background: var(--primary); padding: 30px; border-radius: 4px; box-shadow: var(--shadow-soft); border-bottom: 3px solid #d4ae6d;">
                        <div style="font-size: 0.65rem; font-weight: 700; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 15px;">Leader Actuel</div>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <img :src="stats.top_3[0].image" x-show="stats.top_3[0].image" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid #d4ae6d;">
                            <div>
                                <div x-text="stats.top_3[0].name" style="color: white; font-weight: 600; font-size: 1.1rem;"></div>
                                <div style="color: #d4ae6d; font-size: 0.8rem; font-weight: 700;"><span x-text="stats.top_3[0].votes"></span> VOIX</div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Breakdown Électeurs : Système vs Anonymes -->
            <template x-if="stats.voter_breakdown">
                <div style="background: white; border-radius: 4px; box-shadow: var(--shadow-soft); padding: 25px 30px; margin-top: 20px;">
                    <div style="font-size: 0.65rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 18px;">Origine des Votes</div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                        <!-- Comptes Système -->
                        <div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <span style="font-size: 0.8rem; font-weight: 600; color: var(--primary);">Comptes Système</span>
                                <span style="font-weight: 800; color: var(--primary);" x-text="stats.voter_breakdown.system_pct + '%'"></span>
                            </div>
                            <div style="height: 6px; background: #e5e7eb; border-radius: 10px; overflow: hidden;">
                                <div :style="'width:'+stats.voter_breakdown.system_pct+'%;background:var(--primary);height:100%;border-radius:10px;transition:width 0.8s;'"></div>
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-dim); margin-top: 5px;" x-text="stats.voter_breakdown.system + ' voix'"></div>
                        </div>
                        <!-- Anonymes -->
                        <div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-dim); font-style: italic;">Anonymes</span>
                                <span style="font-weight: 800; color: var(--text-dim);" x-text="stats.voter_breakdown.anonymous_pct + '%'"></span>
                            </div>
                            <div style="height: 6px; background: #e5e7eb; border-radius: 10px; overflow: hidden;">
                                <div :style="'width:'+stats.voter_breakdown.anonymous_pct+'%;background:var(--accent);height:100%;border-radius:10px;transition:width 0.8s;'"></div>
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-dim); margin-top: 5px;" x-text="stats.voter_breakdown.anonymous + ' voix'"></div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Tableau Vues & Visites par Candidat (Live) -->
            <div style="background: white; border-radius: 4px; box-shadow: var(--shadow-soft); overflow: hidden; margin-top: 20px;">
                <div style="padding: 20px 30px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <span style="font-size: 0.7rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.15em;">👁 Vues &amp; Visites par Candidat</span>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <div class="live-blink" style="width: 8px; height: 8px; background: #10b981; border-radius: 50%;"></div>
                        <span style="font-size: 0.6rem; color: #10b981; font-weight: 700; text-transform: uppercase;">Live</span>
                    </div>
                </div>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; min-width: 600px;">
                    <thead style="background: #f9f6f0; font-size: 0.6rem; text-transform: uppercase; color: var(--text-dim); letter-spacing: 0.1em;">
                        <tr>
                            <th style="padding: 14px 25px; text-align: left;">Candidat</th>
                            <th style="padding: 14px 25px; text-align: center;">⚡ Voix</th>
                            <th style="padding: 14px 25px; text-align: center;">👤 Vues</th>
                            <th style="padding: 14px 25px; text-align: center;">🔁 Visites</th>
                            <th style="padding: 14px 25px; text-align: center; color: var(--primary);">🛡️ Système</th>
                            <th style="padding: 14px 25px; text-align: center; color: var(--accent);">🕵️ Anonyme</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="ds in stats.datasets" :key="ds.candidate_id">
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 14px 25px; font-weight: 600; color: var(--primary);">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div :style="'width:10px;height:10px;border-radius:50%;background:'+ds.borderColor"></div>
                                        <span x-text="ds.label"></span>
                                    </div>
                                </td>
                                <td style="padding: 14px 25px; text-align: center; font-weight: 700; font-family: 'Cormorant Garamond', serif; font-size: 1.1rem;" x-text="ds.data.reduce((a,b)=>a+b, 0)"></td>
                                <td style="padding: 14px 25px; text-align: center; font-weight: 700; color: #3b82f6;" x-text="ds.views ?? 0"></td>
                                <td style="padding: 14px 25px; text-align: center; font-weight: 700; color: #8b5cf6;" x-text="ds.hits ?? 0"></td>
                                <td style="padding: 14px 25px; text-align: center; font-weight: 8s00; color: var(--primary);" x-text="(ds.system_pct ?? 0) + '%'"></td>
                                <td style="padding: 14px 25px; text-align: center; font-weight: 800; color: var(--accent);" x-text="(ds.anonymous_pct ?? 0) + '%'"></td>
                            </tr>
                        </template>
                        <template x-if="!stats.datasets || stats.datasets.length === 0">
                            <tr><td colspan="6" style="padding: 30px; text-align: center; color: var(--text-dim); font-style: italic;">Aucun candidat validé pour le moment.</td></tr>
                        </template>
                    </tbody>
                </table>
                </div>
            </div>

            <!-- Graph Section -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; align-items: start; margin-top: 40px;">
                <!-- Recent Payments Sidebar -->
                <div style="background: white; border-radius: 4px; box-shadow: var(--shadow-soft); overflow: hidden;">
                    <div style="padding: 20px 25px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.7rem; font-weight: 700; color: var(--primary); text-transform: uppercase;">Derniers Flux</span>
                        <div class="live-blink" style="width: 8px; height: 8px; background: #10b981; border-radius: 50%;"></div>
                    </div>
                    <div style="max-height: 400px; overflow-y: auto; font-size: 0.85rem;">
                        <template x-for="vote in stats.recent_votes" :key="vote.time">
                            <div style="padding: 15px 25px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <div style="font-weight: 700; color: var(--primary); font-size: 0.8rem;" x-text="vote.is_anonymous ? 'Anonyme' : 'Compte Système'"></div>
                                    <div style="font-size: 0.7rem; color: var(--text-dim);"><span x-text="vote.count"></span> voix pour <span x-text="vote.candidate"></span></div>
                                </div>
                                <div style="font-weight: 800; color: var(--primary); text-align: right;">
                                    <span x-text="vote.amount"></span>
                                    <div style="font-size: 0.6rem; color: var(--accent);">XOF</div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Evolution Graph -->
                <div style="background: white; padding: 40px; border-radius: 4px; box-shadow: var(--shadow-soft); height: 468px;">
                    <div style="font-size: 0.75rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 30px;">Évolution des Votes</div>
                    <div style="height: 330px; position: relative;">
                        <canvas id="evolutionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div style="font-size: 0.75rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.4em; margin-bottom: 40px; text-align: center;">Détails de vos Sessions</div>

        @forelse($displayCampaigns as $camp)
        <div class="card-admin">
            @if($camp->video_path)
                <div style="width: 100%; background: #051a16;">
                    <video controls style="width: 100%; max-height: 400px; display: block; object-fit: contain;">
                        <source src="{{ \Illuminate\Support\Str::startsWith($camp->video_path, 'http') ? $camp->video_path : asset('storage/' . $camp->video_path) }}" type="video/mp4">
                    </video>
                </div>
            @endif

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 400px), 1fr)); background: white;">
                <div style="background: #051a16; overflow: hidden; display: flex; align-items: center; justify-content: center; border-right: 1px solid var(--border);">
                    @if($camp->image_path)
                        <img src="{{ \Illuminate\Support\Str::startsWith($camp->image_path, 'http') ? $camp->image_path : asset('storage/' . $camp->image_path) }}" style="width: 100%; height: 100%; object-fit: cover; min-height: 350px;">
                    @else
                        <div style="height: 350px; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.1); font-family: 'Cormorant Garamond', serif;">SANS IMAGE</div>
                    @endif
                </div>
                
                <div style="padding: 40px; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        @if(Auth::id() === $camp->user_id || Auth::user()->isAdmin())
                        <div style="font-size: 0.7rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.2em;">RÉFÉRENCE #{{ $camp->code }}</div>
                        @endif
                        <h3 style="font-size: clamp(1.5rem, 6vw, 2.2rem); color: var(--primary); font-family: 'Cormorant Garamond', serif; margin-top: 10px; margin-bottom: 15px; line-height: 1.1;">{{ $camp->name }}</h3>
                        <div>
                            @php
                                $badges = [
                                    'pending' => ['#f59e0b', 'EN ATTENTE D\'EXAMEN'],
                                    'rejected' => ['#ef4444', 'DEMANDE REJETÉE'],
                                    'paused' => ['#6b7a77', 'SESSION EN PAUSE'],
                                    'ended' => ['var(--text-dim)', 'SESSION CLÔTURÉE'],
                                    'active' => ['var(--primary)', 'SESSION ACTIVE']
                                ];
                                $badge = $badges[$camp->status] ?? ['var(--border)', $camp->status];
                            @endphp
                            <span style="font-size: 0.65rem; background: {{ $badge[0] }}; color: white; padding: 4px 12px; border-radius: 20px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em;">{{ $badge[1] }}</span>
                        </div>
                        <div style="display: flex; gap: 20px; margin-top: 20px;">
                            <span style="font-size: 0.65rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.1em;">PRIX : <span style="color: var(--primary);">{{ $camp->vote_price == 0 ? 'GRATUIT' : number_format($camp->vote_price, 0, ',', ' ') . ' FCFA' }}</span></span>
                                    @if($camp->bank_account)
                                <span style="font-size: 0.65rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.1em;">COMPTE : <span style="color: var(--primary);">{{ $camp->bank_account }}</span></span>
                            @endif
                        </div>
                        @if($camp->status === 'rejected' && $camp->rejection_reason)
                            <div style="background-color: #fef2f2; padding: 15px; border-left: 3px solid #ef4444; margin-top: 20px;">
                                <div style="font-size: 0.6rem; text-transform: uppercase; color: #991b1b; font-weight: 700; letter-spacing: 0.1em; margin-bottom: 5px;">Motif de rejet (Administrateur)</div>
                                <div style="font-size: 0.85rem; color: #991b1b; font-style: italic;">"{{ $camp->rejection_reason }}"</div>
                            </div>
                        @endif
                        <div style="display: flex; gap: 15px; margin-top: 25px;">
                            <div style="padding: 10px 20px; background: #f9f6f0; border-radius: 4px; display: flex; gap: 20px;">
                                <div style="font-size: 0.7rem; font-weight: 700; color: #10b981;">{{ $camp->candidates->count() }} APPROUVÉS</div>
                                <div style="width: 1px; background: var(--border);"></div>
                                <div style="font-size: 0.7rem; font-weight: 700; color: #f59e0b;">{{ $camp->allCandidates->where('status', 'pending')->count() }} EN ATTENTE</div>
                            </div>
                        </div>
                        <p style="color: var(--text-dim); font-size: 0.95rem; line-height: 1.8; margin-top: 20px;">{{ $camp->description }}</p>
                    </div>

                    <div style="display: flex; gap: 12px; margin-top: 40px; align-items: stretch; flex-wrap: wrap;">
                        <a href="{{ route('campaigns.show', $camp->slug) }}" class="btn-action btn-primary-admin" style="flex: 2; min-width: 150px; display: flex; align-items: center; justify-content: center; padding: 15px 10px; color: white;">Voter / Voir le scrutin</a>
                        
                        @if($myActive->contains($camp))
                            <a href="{{ route('campaigns.manage', $camp->slug) }}" class="btn-action btn-outline-admin" style="flex: 1; min-width: 100px; display: flex; align-items: center; justify-content: center; padding: 15px 10px; border-color: var(--primary); color: var(--primary);">Gérer</a>
                            <a href="{{ route('campaigns.edit', $camp->slug) }}" class="btn-action btn-outline-admin" style="flex: 1; min-width: 100px; display: flex; align-items: center; justify-content: center; padding: 15px 10px;">Modifier</a>
                            
                            <form id="del-active-{{ $camp->id }}" action="{{ route('campaigns.destroy', $camp->slug) }}" method="POST" style="flex: 1; min-width: 100px; display: flex; margin: 0;">
                                @csrf @method('DELETE')
                                <button type="button" @click="Swal.fire({ title: 'Supprimer la session ?', text: 'Toutes les données et les votes seront perdus.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444' }).then((r) => { if (r.isConfirmed) document.getElementById('del-active-{{ $camp->id }}').submit(); })" class="btn-action btn-danger-admin" style="width: 100%; display: flex; align-items: center; justify-content: center; padding: 15px 10px;">Supprimer</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 100px; background: white; border: 1px dashed var(--border);">
            <p style="color: var(--text-dim); font-family: 'Cormorant Garamond', serif; font-size: 1.2rem;">Aucun événement actif à afficher pour le moment.</p>
        </div>
    @endforelse
    </div>


    {{-- SECTION CANDIDATURES --}}
    @if($myCandidacies->isNotEmpty())
        <div x-show="tab === 'candidacies'" x-transition x-cloak>
            <div style="text-align: center; margin-bottom: 50px;">
                <div style="font-family: 'Cormorant Garamond', serif; font-size: 2.8rem; color: var(--primary); margin-bottom: 5px;">Mes Dossiers</div>
                <div class="ornament" style="margin: 0 auto;"></div>
            </div>
            
            @foreach($myCandidacies as $candidacy)
                @php
                    $camp = $candidacy->campaign;
                    $statuses = [
                        'pending' => ['#f59e0b', 'EN COURS D\'EXAMEN'],
                        'accepted' => ['#10b981', 'CANDIDATURE ACCEPTÉE'],
                        'rejected' => ['#ef4444', 'CANDIDATURE REJETÉE']
                    ];
                    $st = $statuses[$candidacy->status] ?? ['#6b7a77', $candidacy->status];
                @endphp

                <div class="card-admin" style="display: flex; flex-direction: column; background: white; margin-bottom: 80px;">
                    <!-- 1. VIDEO (SI EXISTE) -->
                    @if($candidacy->video_path)
                        <div style="width: 100%; background: #051a16;">
                            <video controls style="width: 100%; max-height: 450px; display: block; object-fit: contain;">
                                <source src="{{ asset('storage/' . $candidacy->video_path) }}" type="video/mp4">
                            </video>
                        </div>
                    @endif

                    <!-- 2. IMAGE ET INFOS -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 400px), 1fr)); background: white;">
                        <!-- Zone Image -->
                        <div style="background: #051a16; overflow: hidden; display: flex; align-items: center; justify-content: center; position: relative; border-right: 1px solid var(--border);">
                            @if($candidacy->image_path)
                                <img src="{{ asset('storage/' . $candidacy->image_path) }}" style="width: 100%; height: 100%; object-fit: cover; min-height: 400px;">
                            @else
                                <div style="height: 400px; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.1); font-family: 'Cormorant Garamond', serif; font-size: 5rem;">{{ substr($candidacy->name, 0, 1) }}</div>
                            @endif
                            
                            <div style="position: absolute; top: 30px; left: 30px;">
                                <span style="background: {{ $st[0] }}; color: white; padding: 6px 15px; font-size: 0.65rem; font-weight: 700; letter-spacing: 0.2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">{{ $st[1] }}</span>
                            </div>
                        </div>

                        <!-- Zone Infos -->
                        <div style="padding: 50px; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                                    <div>
                                        <div style="font-size: 0.7rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 10px;">Votre Candidature pour</div>
                                        <h3 style="font-size: clamp(1.5rem, 6vw, 2.22rem); color: var(--primary); font-family: 'Cormorant Garamond', serif; line-height: 1.1; margin: 0;">{{ $camp->name }}</h3>
                                    </div>
                                    <div style="text-align: right;">
                                        <div style="font-size: 0.6rem; color: var(--text-dim); text-transform: uppercase; font-weight: 700;">DÉPOSÉ LE</div>
                                        <div style="font-size: 1rem; color: var(--primary); font-family: 'Cormorant Garamond', serif;">{{ $candidacy->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>

                                <div style="margin: 35px 0;">
                                    <div style="font-size: 0.65rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">Votre Profil Candidat</div>
                                    <div style="font-size: 1.1rem; color: var(--primary); font-weight: 600;">{{ $candidacy->name }}</div>
                                    <p style="color: var(--text-dim); font-size: 1rem; line-height: 1.8; margin-top: 15px;">{{ $candidacy->description }}</p>
                                </div>

                                @if($candidacy->status === 'accepted')
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 25px; background: #f9f6f0; border-radius: 4px; border-left: 4px solid #10b981;">
                                        <div style="text-align: center;">
                                            <div style="font-size: 0.6rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase;">Vos Voix</div>
                                            <div style="font-size: 2rem; font-family: 'Cormorant Garamond', serif; color: var(--primary); font-weight: 700;">{{ $candidacy->votes_count }}</div>
                                        </div>
                                        <div style="text-align: center;">
                                            <div style="font-size: 0.6rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase;">Total Scrutin</div>
                                            <div style="font-size: 2rem; font-family: 'Cormorant Garamond', serif; color: var(--primary); font-weight: 700;">{{ $camp->votes_count }}</div>
                                        </div>
                                    </div>
                                @endif

                                @if($candidacy->status === 'rejected' && $candidacy->rejection_reason)
                                    <div style="background: #fef2f2; padding: 20px; border-left: 4px solid #ef4444; margin-top: 20px;">
                                        <div style="font-size: 0.6rem; text-transform: uppercase; color: #991b1b; font-weight: 700; letter-spacing: 0.1em; margin-bottom: 5px;">Motif de rejet (Administrateur)</div>
                                        <div style="font-size: 0.95rem; color: #991b1b; font-style: italic;">"{{ $candidacy->rejection_reason }}"</div>
                                    </div>
                                @endif
                            </div>

                            <div style="display: flex; gap: 15px; margin-top: 45px;">
                                @if($candidacy->status === 'accepted')
                                    <a href="{{ route('candidates.stats', $candidacy->id) }}" class="btn-action" style="flex: 3; padding: 20px; font-size: 0.8rem; border: 1px solid var(--accent); color: var(--accent); background: transparent; transition: 0.3s;" onmouseover="this.style.background='var(--accent)';this.style.color='white';" onmouseout="this.style.background='transparent';this.style.color='var(--accent)';">Gérer mon classement &amp; mes stats</a>
                                @elseif($candidacy->status === 'pending')
                                    <a href="{{ route('candidates.edit-apply', $candidacy->id) }}" class="btn-action btn-outline-admin" style="flex: 2; padding: 20px; border-color: var(--primary); color: var(--primary); font-size: 0.8rem;">Modifier mon dossier</a>
                                    <form id="cancel-app-{{ $candidacy->id }}" action="{{ route('candidates.destroy-apply', $candidacy->id) }}" method="POST" style="flex: 1; margin: 0;">
                                        @csrf @method('DELETE')
                                        <button type="button" @click="Swal.fire({
                                            title: 'Retirer votre candidature ?',
                                            text: 'Cette action est définitive et votre dossier sera supprimé.',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#ef4444',
                                            cancelButtonColor: '#003229',
                                            confirmButtonText: 'Oui, retirer',
                                            cancelButtonText: 'Conserver dossier'
                                        }).then((r) => { if (r.isConfirmed) document.getElementById('cancel-app-{{ $candidacy->id }}').submit(); })" 
                                        class="btn-action btn-danger-admin" style="width: 100%; padding: 20px; font-size: 0.8rem;">Annuler</button>
                                    </form>
                                @endif
                                <a href="{{ route('campaigns.show', $camp->slug) }}" class="btn-action btn-outline-admin" style="width: 60px; display: flex; align-items: center; justify-content: center;" title="Voir la page publique">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6M15 3h6v6M10 14L21 3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- SECTION VOTES --}}
    <div x-show="tab === 'votes'" x-transition x-cloak>
        <div style="background: white; border-radius: 4px; box-shadow: var(--shadow-soft); overflow: hidden;">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 700px;">
                    <thead style="background: var(--primary); color: white; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.2em;">
                        <tr>
                            <th style="padding: 25px;">Scrutin</th>
                            <th style="padding: 25px;">Votre Choix</th>
                            <th style="padding: 25px; text-align: center;">Voix</th>
                            <th style="padding: 25px; text-align: right;">Total Payé</th>
                            <th style="padding: 25px; text-align: right;">Date du Vote</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($myVotes as $vote)
                            <tr style="border-bottom: 1px solid var(--border); transition: 0.3s;" onmouseover="this.style.background='#F9F6F0'" onmouseout="this.style.background='white'">
                                <td style="padding: 25px;">
                                    <div style="font-weight: 700; color: var(--primary);">{{ $vote->campaign->name }}</div>
                                </td>
                                <td style="padding: 25px;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 8px; height: 8px; background: var(--accent); border-radius: 50%;"></div>
                                        <div style="font-weight: 600;">{{ $vote->candidate->name }}</div>
                                    </div>
                                </td>
                                <td style="padding: 25px; text-align: center; font-weight: 700;">{{ $vote->votes_count }}</td>
                                <td style="padding: 25px; text-align: right; font-weight: 700; color: #10b981;">{{ number_format($vote->amount, 0, ',', ' ') }} XOF</td>
                                <td style="padding: 25px; text-align: right; color: var(--text-dim); font-size: 0.85rem;">
                                    {{ $vote->created_at->format('d/m/Y à H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 60px; text-align: center; color: var(--text-dim); font-style: italic;">Vous n'avez pas encore émis de vote.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
@endsection
