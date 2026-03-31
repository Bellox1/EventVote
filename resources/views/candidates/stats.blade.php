@extends('layouts.app')

@section('title', 'Tableau de Bord Candidat')

@section('content')
<div style="max-width: 1400px; margin: 40px auto; padding: 0 15px;" x-data="{
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
    <a href="{{ route('dashboard') }}" style="display: flex; align-items: center; gap: 10px; color: var(--text-dim); text-decoration: none; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 40px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> RETOUR
    </a>

    <!-- Header & Score -->
    <div style="text-align: center; margin-bottom: 60px;">
        <div style="font-family: 'Cormorant Garamond', serif; font-size: clamp(2rem, 8vw, 3.5rem); color: var(--primary); margin-bottom: 10px; line-height: 1;">Performance Live</div>
        <div class="ornament" style="margin: 0 auto 30px;"></div>
        
        <div style="display: inline-flex; flex-direction: column; align-items: center; background: white; padding: clamp(20px, 5vw, 40px) clamp(15px, 8vw, 60px); border-radius: 4px; box-shadow: var(--shadow-soft); border-bottom: 6px solid #d4ae6d; width: min(100%, 500px);">
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.3em; margin-bottom: 15px;">Votre Score Actuel</div>
            <div style="font-size: clamp(3.5rem, 15vw, 5.5rem); font-family: 'Cormorant Garamond', serif; color: var(--primary); line-height: 1; font-weight: 400;" x-text="myVotes">{{ $candidate->votes_count }}</div>
            <div style="font-size: 0.9rem; color: var(--accent); font-weight: 600; margin-top: 15px; text-transform: uppercase; letter-spacing: 0.1em;">Voix Enregistrées</div>
            <div style="margin-top: 25px; display: flex; gap: 10px; align-items: center;" class="live-blink">
                <div style="width: 8px; height: 8px; background: #10b981; border-radius: 50%;"></div>
                <span style="font-size: 0.6rem; font-weight: 700; color: #10b981; text-transform: uppercase;">Live</span>
            </div>
        </div>
    </div>

    <!-- Stats & Graphique -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 450px), 1fr)); gap: 30px; margin-bottom: 40px;">
        <!-- Profil & Audience -->
        <div class="card" style="padding: clamp(20px, 5vw, 40px); text-align: center;">
            <div style="width: 100px; height: 100px; border-radius: 50%; border: 3px solid var(--accent); margin: 0 auto 20px; overflow: hidden;">
                @if($candidate->image_path)
                    <img src="{{ asset('storage/' . $candidate->image_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <div style="width: 100%; height: 100%; background: var(--border); display: flex; align-items: center; justify-content: center; font-size: 2.5rem;">{{ substr($candidate->name, 0, 1) }}</div>
                @endif
            </div>
            <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: var(--primary); margin: 0;">{{ $candidate->name }}</h3>
            
            <div style="margin-top: 30px; text-align: left;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="font-size: 0.7rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase;">Part d'Audience</span>
                    <span style="font-size: 0.7rem; font-weight: 800; color: var(--primary);" x-text="((myVotes / (stats.total_votes || 1)) * 100).toFixed(1) + '%'">--%</span>
                </div>
                <div style="height: 6px; background: #f0f0f0; border-radius: 10px; overflow: hidden;">
                    <div :style="'width: ' + ((myVotes / (stats.total_votes || 1)) * 100) + '%'" style="height: 100%; background: var(--accent); transition: width 1s;"></div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 25px;">
                <div style="background: #f9f6f0; padding: 15px; border-radius: 4px;">
                    <div style="font-size: 0.55rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase;">Total Scrutin</div>
                    <div style="font-size: 1.2rem; font-weight: 700; color: var(--primary);" x-text="stats.total_votes || '{{ $competitors->sum('votes_count') }}'">0</div>
                </div>
                <div style="background: #f9f6f0; padding: 15px; border-radius: 4px;">
                    <div style="font-size: 0.55rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase;">Revenus</div>
                    <div style="font-size: 1.2rem; font-weight: 700; color: var(--primary);" x-text="stats.total_amount || '0'">0</div>
                </div>
            </div>
        </div>

        <!-- Graphique -->
        <div class="card" style="padding: clamp(20px, 5vw, 40px);">
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 25px;">Évolution du Scrutin</div>
            <div style="height: 300px; position: relative;">
                <canvas id="candidateEvolutionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Origine des Votes -->
    <template x-if="stats.voter_breakdown">
        <div class="card" style="padding: 25px 30px; margin-bottom: 30px;">
            <div style="font-size: 0.65rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 18px;">Origine des Votes</div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 25px;">
                <div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 0.8rem; font-weight: 600; color: var(--primary);">Système</span>
                        <span style="font-weight: 800; color: var(--primary);" x-text="stats.voter_breakdown.system_pct + '%'"></span>
                    </div>
                    <div style="height: 6px; background: #e5e7eb; border-radius: 10px; overflow: hidden;">
                        <div :style="'width:'+stats.voter_breakdown.system_pct+'%;background:var(--primary);height:100%;'"></div>
                    </div>
                </div>
                <div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="font-size: 0.8rem; font-weight: 600; color: var(--text-dim);">Anonymes</span>
                        <span style="font-weight: 800; color: var(--accent);" x-text="stats.voter_breakdown.anonymous_pct + '%'"></span>
                    </div>
                    <div style="height: 6px; background: #e5e7eb; border-radius: 10px; overflow: hidden;">
                        <div :style="'width:'+stats.voter_breakdown.anonymous_pct+'%;background:var(--accent);height:100%;'"></div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Tableaux de données -->
    <div style="display: flex; flex-direction: column; gap: 30px; margin-bottom: 80px;">
        <!-- Vues & Visites -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.3rem; color: var(--primary); margin: 0;">👁 Vues &amp; Visites</h3>
                <span style="font-size: 0.6rem; color: #10b981; font-weight: 700; text-transform: uppercase;" class="live-blink">Live</span>
            </div>
            <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.8rem; min-width: 500px;">
                    <thead style="background: #f9f6f0; font-size: 0.6rem; text-transform: uppercase; color: var(--text-dim);">
                        <tr>
                            <th style="padding: 12px 15px; text-align: left;">Candidat</th>
                            <th style="padding: 12px 15px; text-align: center;">⚡ Voix</th>
                            <th style="padding: 12px 15px; text-align: center;">👤 Vues</th>
                            <th style="padding: 12px 15px; text-align: center;">🔁 Visites</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="ds in stats.datasets" :key="ds.candidate_id">
                            <tr :style="ds.candidate_id === {{ $candidate->id }} ? 'background:#fffdf7;font-weight:700;' : 'background:white;'" style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 12px 15px; color: var(--primary);">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div :style="'width:8px;height:8px;border-radius:50%;background:'+ds.borderColor"></div>
                                        <span x-text="ds.label"></span>
                                    </div>
                                </td>
                                <td style="padding: 12px 15px; text-align: center; font-family: 'Cormorant Garamond', serif; font-size: 1rem;" x-text="ds.data.reduce((a,b)=>a+b,0)"></td>
                                <td style="padding: 12px 15px; text-align: center; color: #3b82f6;" x-text="ds.views ?? 0"></td>
                                <td style="padding: 12px 15px; text-align: center; color: #8b5cf6;" x-text="ds.hits ?? 0"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Classement -->
        <div class="card" style="padding: 0; overflow: hidden;">
            <div style="padding: 20px; border-bottom: 1px solid var(--border);">
                <h3 style="font-family: 'Cormorant Garamond', serif; font-size: 1.3rem; color: var(--primary); margin: 0;">Classement</h3>
            </div>
            <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table style="width: 100%; border-collapse: collapse; min-width: 400px;">
                    <thead style="background: #f9f6f0; font-size: 0.6rem; text-transform: uppercase; color: var(--text-dim);">
                        <tr>
                            <th style="padding: 12px 15px;">#</th>
                            <th style="padding: 12px 15px;">Candidat</th>
                            <th style="padding: 12px 15px; text-align: right;">Voix</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($competitors as $index => $c)
                            <tr style="border-bottom: 1px solid #f2f2f2; background: {{ $c->id == $candidate->id ? '#fffdf7' : 'white' }};">
                                <td style="padding: 12px 15px; font-family: 'Cormorant Garamond', serif; font-size: 1.2rem; font-weight: 700; color: {{ $index == 0 ? '#d4ae6d' : 'var(--text-dim)' }};">#{{ $index + 1 }}</td>
                                <td style="padding: 12px 15px;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 30px; height: 30px; border-radius: 50%; overflow: hidden; border: 1px solid {{ $index == 0 ? '#d4ae6d' : '#eee' }};">
                                            @if($c->image_path)
                                                <img src="{{ asset('storage/' . $c->image_path) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <div style="width:100%; height:100%; background:#f0f0f0; display:flex; align-items:center; justify-content:center; font-size:0.6rem;">{{ substr($c->name, 0, 1) }}</div>
                                            @endif
                                        </div>
                                        <span style="font-size: 0.85rem; font-weight: 600; color: var(--primary);">{{ $c->name }}</span>
                                    </div>
                                </td>
                                <td style="padding: 12px 15px; text-align: right; font-family: 'Cormorant Garamond', serif; font-size: 1.2rem; font-weight: 700;">{{ $c->votes_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .live-blink { animation: blink-animation 1.5s steps(5, start) infinite; -webkit-animation: blink-animation 1.5s steps(5, start) infinite; }
    @keyframes blink-animation { to { visibility: hidden; } }
    .card { background: white; border-radius: 4px; box-shadow: var(--shadow-soft); margin-bottom: 30px; overflow: hidden; border-bottom: 4px solid var(--accent); }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
