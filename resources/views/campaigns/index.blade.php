@extends('layouts.app')

@section('title', 'Explorer les programmes')

@section('content')
    <div style="text-align: center; margin-bottom: 100px;">
        <div
            style="font-size: 0.8rem; font-weight: 600; color: var(--accent); text-transform: uppercase; letter-spacing: 0.4em; margin-bottom: 24px; opacity: 0.9;">
            Excellence & Intégrité</div>
        <h1 style="font-size: 4rem; color: var(--primary); margin-bottom: 20px; font-weight: 300;">Sessions <span
                style="font-style: italic; font-weight: 400;">Ouvertes.</span></h1>
        <div class="ornament" style="margin: 0 auto 32px; width: 60px;"></div>
        <p
            style="color: var(--text-dim); font-size: 1.15rem; max-width: 650px; margin: 0 auto; line-height: 1.8; font-family: 'Jost', sans-serif;">
            Parcourez les scrutins actuellement en cours sur notre plateforme exclusive. Chaque session est un engagement
            vers la transparence et la distinction.</p>
    </div>

    <div class="grid"
        style="margin-bottom: 120px; display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 60px;">
        @forelse($campaigns as $campaign)
            <div class="card"
                style="text-decoration: none; display: flex; flex-direction: column; border: none; background: white; padding: 0; overflow: hidden; transition: all 0.5s;">
                <div style="aspect-ratio: 4/5; overflow: hidden; background: var(--primary);">
                    @if ($campaign->image_path)
                        <img src="{{ Str::startsWith($campaign->image_path, 'http') ? $campaign->image_path : asset('storage/' . $campaign->image_path) }}"
                            style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.8s cubic-bezier(0.19, 1, 0.22, 1);"
                            onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                    @else
                        <div
                            style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                            <span
                                style="font-family: 'Cormorant Garamond', serif; font-size: 5rem; color: white; opacity: 0.1;">#{{ substr($campaign->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>

                <div style="padding: 40px;">
                    <div
                        style="font-size: 0.7rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.3em; margin-bottom: 20px;">
                        REF: #{{ $campaign->code }}</div>
                    <h3
                        style="color: var(--primary); font-size: 2rem; margin: 0 0 24px; font-weight: 400; line-height: 1.1;">
                        {{ $campaign->name }}</h3>

                    <p
                        style="color: var(--text-dim); font-size: 1rem; line-height: 1.8; margin-bottom: 40px; flex-grow: 1;">
                        {{ Str::limit($campaign->description, 130) }}
                    </p>

                    <div
                        style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: 24px;">
                        <div style="font-size: 0.75rem; font-weight: 600; color: var(--primary); opacity: 0.6;">
                            {{ $campaign->candidates()->count() }} candidat(e)s
                        </div>
                        <a href="{{ route('campaigns.show', $campaign->slug) }}" class="btn btn-outline"
                            style="padding: 10px 20px; font-size: 0.65rem; border-width: 1px;">DÉCOUVRIR</a>
                    </div>
                </div>
            </div>
        @empty
            <div
                style="grid-column: 1 / -1; text-align: center; padding: 120px; background: white; border-radius: var(--radius); border: 1px solid var(--border);">
                <div style="font-size: 3rem; color: var(--accent); margin-bottom: 20px;">✧</div>
                <p
                    style="color: var(--text-dim); font-size: 1.25rem; font-style: italic; font-family: 'Cormorant Garamond', serif;">
                    Aucun scrutin n'est disponible pour le moment dans cette section.</p>
            </div>
        @endforelse
    </div>

    <div style="margin-top: 80px; display: flex; justify-content: center;">
        {{ $campaigns->links() }}
    </div>
@endsection
