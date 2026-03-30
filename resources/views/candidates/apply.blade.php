@extends('layouts.app')

@section('title', 'Candidature – ' . $campaign->name)

@section('content')
@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Erreur de saisie',
                html: '<ul style="text-align:left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                icon: 'error',
                confirmButtonColor: '#003229',
                background: '#fff8e7',
                color: '#003229'
            });
        });
    </script>
@endif
<div style="max-width: 800px; margin: 60px auto; padding: 0 20px;">
    <div class="card" style="border-bottom: 6px solid var(--accent); padding: 80px 60px; box-shadow: var(--shadow-hard);">
        
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 60px;">
            <div style="font-family: 'Cormorant Garamond', serif; font-size: 3rem; color: var(--primary); letter-spacing: 0.05em; margin-bottom: 16px; font-weight: 300;">
                DÉPÔT <span style="font-weight: 500; color: var(--accent);">•</span> CANDIDATURE
            </div>
            <div class="ornament" style="margin: 0 auto 32px;"></div>
            <h1 style="font-size: 1.1rem; color: var(--primary); text-transform: uppercase; letter-spacing: 0.3em; font-weight: 400; font-family: 'Jost', sans-serif;">
                {{ $campaign->name }}</h1>
        </div>
        
        <form action="{{ route('candidates.store-apply', $campaign->slug) }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 40px;">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Nom Complet du Candidat</label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required autofocus placeholder="EX: JEAN DUPONT"
                        style="height: 65px; font-size: 1.1rem; border-radius: 4px; border: 1px solid var(--border); width: 100%; padding: 0 25px; background: #f9f9f9;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Numéro de Téléphone</label>
                    <input type="text" value="{{ Auth::user()->phone ?? 'Non renseigné' }}" disabled
                        style="height: 65px; font-size: 1.1rem; border-radius: 4px; border: 1px solid var(--border); width: 100%; padding: 0 25px; background: #eee; color: #666; cursor: not-allowed;">
                    <div style="font-size: 0.6rem; color: var(--accent); margin-top: 5px; text-transform: uppercase;">Prélevé sur votre profil</div>
                </div>
            </div>

            <div>
                <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Votre Programme / Vision</label>
                <textarea name="description" rows="6" placeholder="Décrivez vos motivations et votre projet pour ce scrutin..."
                    style="width: 100%; border: 1px solid var(--border); padding: 20px 25px; border-radius: 4px; font-size: 1.1rem; font-family: 'Jost', sans-serif; resize: vertical; min-height: 180px;">{{ old('description') }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Portrait Officiel (Image)</label>
                    <input type="file" name="image_path" accept="image/*"
                           style="width: 100%; border: 1px solid var(--border); padding: 15px; border-radius: 4px; font-size: 0.8rem;">
                </div>

                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Vidéo de Présentation</label>
                    <input type="file" name="video" accept="video/*"
                           style="width: 100%; border: 1px solid var(--border); padding: 15px; border-radius: 4px; font-size: 0.8rem;">
                </div>
            </div>

            <div style="padding: 24px; background: rgba(212, 174, 109, 0.05); border-left: 4px solid var(--accent); border-radius: 0 8px 8px 0; margin: 10px 0;">
                <p style="margin: 0; color: var(--primary); font-size: 0.85rem; line-height: 1.6; font-style: italic;">
                    Votre candidature sera examinée par l'administrateur de la session avant d'être rendue publique et éligible aux votes.
                </p>
            </div>

            <div style="display: flex; gap: 20px; margin-top: 20px;">
                <a href="{{ route('campaigns.show', $campaign->slug) }}" class="btn btn-outline" style="flex: 1; height: 75px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">RETOUR</a>
                <button type="submit" class="btn btn-primary" style="flex: 2; height: 75px; font-size: 1rem; border-radius: 4px;">DÉPOSER MA CANDIDATURE</button>
            </div>
        </form>
    </div>
</div>
@endsection
