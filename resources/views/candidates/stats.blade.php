@extends('layouts.app')

@section('title', 'Tableau de Bord Candidat')

@section('content')
<div style="max-width: 1200px; margin: 40px auto; padding: 0 20px;" x-data="{
    stats: { labels: [], datasets: [], total_amount: 0, total_votes: 0, recent_votes: [], top_3: [] },
    myVotes: {{ $candidate->votes_count }},
    myRank: {{ $myRank }},
    totalCampaignVotes: {{ $competitors->sum('votes_count') }},
    chart: null,

    async fetchStats() {
        try {
            const response = await fetch('{{ route('api.campaigns.stats', $campaign->slug) }}');
            const data = await response.json();
            this.stats = data;
            
            // Update my local stats
            const me = data.datasets.find(d => d.label === '{{ $candidate->name }}');
            if (me) {
                // Approximate total from chart or we could add it to API
                // Let's use the total_votes from API and compute my share
            }
            this.updateChart();
        } catch (e) {
            console.error('Stats error:', e);
        }
    },

    initChart() {
        const ctx = document.getElementById('candidateEvolutionChart').getContext('2d');
        this.chart = new Chart(ctx, {
            type: 'line',
            data: { labels: this.stats.labels, datasets: this.stats.datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: true, position: 'bottom' }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                    x: { grid: { display: false } }
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
        this.fetchStats().then(() => this.initChart());
        setInterval(() => this.fetchStats(), 5000);
    }
}">
    
    <!-- Bouton Retour -->
    <a href="{{ route('dashboard') }}" style="display: flex; align-items: center; gap: 10px; color: var(--text-dim); text-decoration: none; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 40px; transition: 0.3s;" onmouseover="this.style.color='var(--primary)'">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> RETOUR AU TABLEAU DE BORD
    </a>

    <!-- Header & Rang -->
    <div style="text-align: center; margin-bottom: 60px;">
        <div style="font-family: 'Cormorant Garamond', serif; font-size: 3.5rem; color: var(--primary); margin-bottom: 10px; line-height: 1;">Performance Live</div>
        <div class="ornament" style="margin: 0 auto 30px;"></div>
        
        <div style="display: inline-flex; flex-direction: column; align-items: center; background: white; padding: 40px 60px; border-radius: 4px; box-shadow: var(--shadow-soft); border-bottom: 6px solid #d4ae6d; position: relative; overflow: hidden;">
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.3em; margin-bottom: 15px;">Votre Score Actuel</div>
            <div style="font-size: 5rem; font-family: 'Cormorant Garamond', serif; color: var(--primary); line-height: 1; font-weight: 400;" x-text="myVotes">{{ $candidate->votes_count }}</div>
            <div style="font-size: 0.9rem; color: var(--accent); font-weight: 600; margin-top: 15px; text-transform: uppercase; letter-spacing: 0.1em;">Voix Enregistrées</div>
            
            <div style="margin-top: 25px; display: flex; gap: 10px; align-items: center;" class="live-blink">
                <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%;"></div>
                <span style="font-size: 0.6rem; font-weight: 700; color: #10b981; text-transform: uppercase; letter-spacing: 0.1em;">Données en direct</span>
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 40px; margin-bottom: 60px;">
        <!-- Left: Stats Panel -->
        <div style="display: flex; flex-direction: column; gap: 30px;">
            <div class="card" style="padding: 40px; text-align: center;">
                <div style="width: 120px; height: 120px; border-radius: 50%; border: 3px solid var(--accent); margin: 0 auto 20px; overflow: hidden;">
                    @if($candidate->image_path)
                        <img src="{{ asset('storage/' . $candidate->image_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div style="width: 100%; height: 100%; background: var(--border); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: var(--primary); font-family: 'Cormorant Garamond', serif;">{{ substr($candidate->name, 0, 1) }}</div>
                    @endif
                </div>
                <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--primary); margin: 0;">{{ $candidate->name }}</h3>
                
                <div style="margin-top: 35px; text-align: left;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 0.7rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase;">Part d'Audience</span>
                        <span style="font-size: 0.7rem; font-weight: 800; color: var(--primary);" x-text="((myVotes / (stats.total_votes || 1)) * 100).toFixed(1) + '%'">--%</span>
                    </div>
                    <div style="height: 6px; background: #f0f0f0; border-radius: 10px; overflow: hidden;">
                        <div :style="'width: ' + ((myVotes / (stats.total_votes || 1)) * 100) + '%'" style="height: 100%; background: var(--accent); transition: width 1s;"></div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 30px;">
                    <div style="background: #f9f6f0; padding: 15px; border-radius: 4px;">
                        <div style="font-size: 0.55rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase;">Total Scrutin</div>
                        <div style="font-size: 1.2rem; font-weight: 700; color: var(--primary);" x-text="stats.total_votes || '{{ $competitors->sum('votes_count') }}'">0</div>
                    </div>
                    <div style="background: #f9f6f0; padding: 15px; border-radius: 4px;">
                        <div style="font-size: 0.55rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase;">Revenus Campagne</div>
                        <div style="font-size: 1.2rem; font-weight: 700; color: var(--primary);" x-text="stats.total_amount || '0'">0</div>
                    </div>
                </div>
            </div>

            <div style="background: var(--primary); padding: 30px; border-radius: 4px; color: white;">
                <div style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: var(--accent); margin-bottom: 10px;">Partagez votre lien</div>
                <p style="font-size: 0.8rem; margin-bottom: 15px; opacity: 0.8;">Chaque vote compte pour votre victoire finale.</p>
                <div style="background: rgba(255,255,255,0.1); padding: 12px; border-radius: 4px; font-size: 0.7rem; text-align: center; border: 1px dashed rgba(212, 174, 109, 0.4); color: var(--accent); font-weight: 600; cursor: pointer;" onclick="navigator.clipboard.writeText('{{ route('campaigns.show', $campaign->slug) }}#candidate-{{ $candidate->id }}'); Swal.fire({title: 'Lien copié !', icon: 'success', timer: 1500, showConfirmButton: false});">
                    COPIER MON LIEN DE VOTE
                </div>
            </div>
        </div>

        <!-- Right: Real-time Evolution -->
        <div class="card" style="padding: 40px;">
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 30px;">Évolution du Scrutin (Dernières 12h)</div>
            <div style="height: 450px; position: relative;">
                <canvas id="candidateEvolutionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Breakdown Électeurs : Système vs Anonymes -->
    <template x-if="stats.voter_breakdown">
        <div class="card" style="padding: 25px 30px; margin-bottom: 30px;">
            <div style="font-size: 0.65rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 18px;">Origine des Votes (campagne entière)</div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
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
                <div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-dim); font-style: italic;">Anonymes</span>
                        <span style="font-weight: 800; color: var(--accent);" x-text="stats.voter_breakdown.anonymous_pct + '%'"></span>
                    </div>
                    <div style="height: 6px; background: #e5e7eb; border-radius: 10px; overflow: hidden;">
                        <div :style="'width:'+stats.voter_breakdown.anonymous_pct+'%;background:var(--accent);height:100%;border-radius:10px;transition:width 0.8s;'"></div>
                    </div>
                    <div style="font-size: 0.75rem; color: var(--text-dim); margin-top: 5px;" x-text="stats.voter_breakdown.anonymous + ' voix'"></div>
                </div>
            </div>
        </div>
    </template>

    <!-- Vues & Visites par Candidat (Live) -->
    <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 40px;">
        <div style="padding: 20px 30px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; color: var(--primary); margin: 0;">👁 Vues &amp; Visites par Candidat</h3>
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%;" class="live-blink"></div>
                <span style="font-size: 0.6rem; color: #10b981; font-weight: 700; text-transform: uppercase;">Live</span>
            </div>
        </div>
        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
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
                    <tr :style="ds.candidate_id === {{ $candidate->id }} ? 'background:#fffdf7;font-weight:700;' : 'background:white;'" style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 14px 25px; color: var(--primary);">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div :style="'width:10px;height:10px;border-radius:50%;background:'+ds.borderColor"></div>
                                <span x-text="ds.label"></span>
                                <span x-show="ds.candidate_id === {{ $candidate->id }}" style="font-size: 0.55rem; background: var(--accent); color: white; padding: 2px 6px; border-radius: 4px;">Moi</span>
                            </div>
                        </td>
                        <td style="padding: 14px 25px; text-align: center; font-family: 'Cormorant Garamond', serif; font-size: 1.1rem;" x-text="ds.data.reduce((a,b)=>a+b,0)"></td>
                        <td style="padding: 14px 25px; text-align: center; font-weight: 700; color: #3b82f6;" x-text="ds.views ?? 0"></td>
                        <td style="padding: 14px 25px; text-align: center; font-weight: 700; color: #8b5cf6;" x-text="ds.hits ?? 0"></td>
                        <td style="padding: 14px 25px; text-align: center; font-weight: 800; color: var(--primary);" x-text="ds.system_pct + '%'"></td>
                        <td style="padding: 14px 25px; text-align: center; font-weight: 800; color: var(--accent);" x-text="ds.anonymous_pct + '%'"></td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Ranking Table -->
    <div class="card" style="padding: 0; overflow: hidden; margin-bottom: 80px;">
        <div style="padding: 30px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.8rem; color: var(--primary); margin: 0;">Classement en Direct</h3>
            <div style="display: flex; gap: 15px;">
                <template x-for="(win, idx) in stats.top_3" :key="idx">
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 0.7rem; font-weight: 700;">
                        <div :style="'background:' + (idx === 0 ? '#d4ae6d' : (idx === 1 ? '#c0c0c0' : '#cd7f32'))" style="width: 10px; height: 10px; border-radius: 50%;"></div>
                        <span x-text="win.name" style="color: var(--text-dim);"></span>
                    </div>
                </template>
            </div>
        </div>
        
        <div style="max-height: 500px; overflow-y: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead style="background: #f9f6f0; font-size: 0.65rem; text-transform: uppercase; color: var(--text-dim);">
                    <tr>
                        <th style="padding: 20px 30px;">Rang</th>
                        <th style="padding: 20px 30px;">Candidat</th>
                        <th style="padding: 20px 30px; text-align: right;">Total Voix</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($competitors as $index => $c)
                        <tr style="border-bottom: 1px solid #f2f2f2; background: {{ $c->id == $candidate->id ? '#fffdf7' : 'white' }};">
                            <td style="padding: 20px 30px; font-family: 'Cormorant Garamond', serif; font-size: 1.4rem; font-weight: 700; color: {{ $index == 0 ? '#d4ae6d' : 'var(--text-dim)' }};">
                                #{{ $index + 1 }}
                            </td>
                            <td style="padding: 20px 30px;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; border: 2px solid {{ $index == 0 ? '#d4ae6d' : '#eee' }};">
                                        @if($c->image_path)
                                            <img src="{{ asset('storage/' . $c->image_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <div style="width: 100%; height: 100%; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">{{ substr($c->name, 0, 1) }}</div>
                                        @endif
                                    </div>
                                    <div style="font-weight: 600; color: var(--primary);">
                                        {{ $c->name }}
                                        @if($c->id == $candidate->id) <span style="font-size: 0.6rem; background: var(--accent); color: white; padding: 2px 6px; border-radius: 4px; margin-left: 5px;">Moi</span> @endif
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 20px 30px; text-align: right;">
                                <div style="font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; color: var(--primary); font-weight: 700;">{{ $c->votes_count }}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .live-blink { animation: blink-animation 1.5s steps(5, start) infinite; -webkit-animation: blink-animation 1.5s steps(5, start) infinite; }
    @keyframes blink-animation { to { visibility: hidden; } }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
