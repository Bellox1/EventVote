@extends('layouts.app')

@section('title', 'Supervision ÉVÉNEMENTIELLE')
@section('content')
    <div style="text-align: center; margin-bottom: 80px;">
        <h1 style="font-size: 4rem; color: var(--primary); margin-bottom: 16px;">Supervision Globale</h1>
        <div class="ornament" style="margin: 0 auto 32px;"></div>
        <p style="color: var(--text-dim); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Panel d'excellence pour la
            modération et la transparence électorale.</p>
    </div>

    <div style="display: flex; justify-content: center; gap: 40px; margin-bottom: 100px;">
        <div class="card"
            style="padding: 40px; text-align: center; border-bottom: 4px solid var(--accent); min-width: 200px;">
            <div
                style="font-size: 0.7rem; color: var(--accent); font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 12px;">
                Membres</div>
            <div style="font-size: 3rem; font-family: 'Playfair Display', serif; color: var(--primary);">{{ $usersCount }}
            </div>
        </div>
        <div class="card"
            style="padding: 40px; text-align: center; border-bottom: 4px solid var(--accent); min-width: 200px;">
            <div
                style="font-size: 0.7rem; color: var(--accent); font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 12px;">
                Scrutins</div>
            <div style="font-size: 3rem; font-family: 'Playfair Display', serif; color: var(--primary);">
                {{ $allCampaigns->count() }}</div>
        </div>
    </div>

    <div style="margin-bottom: 100px;">
        <h2 style="font-size: 2.5rem; color: var(--primary); margin-bottom: 40px; text-align: center;">Demandes en Attente
        </h2>

        <div style="display: flex; flex-direction: column; gap: 32px; max-width: 900px; margin: 0 auto;">
            @forelse($pendingCampaigns as $campaign)
                <div class="card"
                    style="display: flex; flex-direction: column; gap: 32px; border-bottom: 4px solid var(--accent);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <div
                                style="font-size: 0.7rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 12px;">
                                NOUVELLE SOUMISSION</div>
                            <h3 style="font-size: 2rem; color: var(--primary); margin: 0 0 16px;">{{ $campaign->name }}</h3>
                            <p style="color: var(--text-dim); line-height: 1.8; margin: 0 0 24px; font-size: 1rem;">
                                {{ $campaign->description }}</p>
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <div
                                    style="background: var(--bg); color: var(--primary); width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                    {{ substr($campaign->creator->name, 0, 1) }}</div>
                                <span style="font-weight: 600; color: var(--primary);">{{ $campaign->creator->name }}</span>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 20px; padding-top: 32px; border-top: 1px solid var(--border);">
                        <form action="{{ route('admin.campaigns.manage', $campaign->id) }}" method="POST" style="flex: 1;">
                            @csrf
                            <input type="hidden" name="status" value="active">
                            <button type="submit" class="btn btn-primary" style="width: 100%;">APPROUVER</button>
                        </form>
                        <form action="{{ route('admin.campaigns.manage', $campaign->id) }}" method="POST" style="flex: 1;">
                            @csrf
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-outline" style="width: 100%;">DÉCLINER</button>
                        </form>
                    </div>
                </div>
            @empty
                <div
                    style="padding: 80px; text-align: center; border: 1px solid var(--border); background: var(--surface);">
                    <p style="color: var(--text-dim); font-size: 1.1rem; font-style: italic;">Aucune demande en attente
                        d'approbation.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div style="margin-top: 120px;">
        <h2 style="font-size: 2.5rem; color: var(--primary); margin-bottom: 40px; text-align: center;">Archive des Sessions
        </h2>
        <div class="card" style="padding: 0; overflow: hidden; border: 1px solid var(--border);">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead style="background: var(--primary); color: var(--white);">
                    <tr>
                        <th
                            style="padding: 24px 40px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em;">
                            Désignation</th>
                        <th
                            style="padding: 24px 40px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em;">
                            Statut</th>
                        <th
                            style="padding: 24px 40px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em;">
                            Auteur</th>
                        <th
                            style="padding: 24px 40px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em;">
                            Participation</th>
                        <th
                            style="padding: 24px 40px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em;">
                            Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allCampaigns as $campaign)
                        <tr style="border-top: 1px solid var(--border);">
                            <td
                                style="padding: 24px 40px; color: var(--primary); font-family: 'Playfair Display', serif; font-size: 1.25rem;">
                                {{ $campaign->name }}</td>
                            <td style="padding: 24px 40px;">
                                <span
                                    class="badge {{ $campaign->status === 'active' ? 'badge-active' : 'badge-pending' }}">{{ $campaign->status }}</span>
                            </td>
                            <td style="padding: 24px 40px; color: var(--text-dim); font-weight: 500;">
                                {{ $campaign->creator->name }}</td>
                            <td style="padding: 24px 40px; font-weight: 600; color: var(--accent);">
                                {{ $campaign->votes()->count() }} Voix</td>
                            <td style="padding: 24px 40px;">
                                <a href="{{ route('campaigns.show', $campaign->slug) }}" class="btn btn-outline"
                                    style="padding: 10px 20px; font-size: 0.7rem;">DÉTAILS</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@endsection
