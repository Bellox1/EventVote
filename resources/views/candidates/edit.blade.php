@extends('layouts.app')

@section('title', 'Modifier ma Candidature')

@section('content')
<div style="max-width: 800px; margin: 60px auto; padding: 0 20px;">
    <div class="card" style="border-bottom: 6px solid var(--accent); padding: 80px 60px; box-shadow: var(--shadow-hard);">
        
        <div style="text-align: center; margin-bottom: 60px;">
            <div style="font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; color: var(--primary); letter-spacing: 0.05em; margin-bottom: 12px; font-weight: 300;">
                RECTIFIER <span style="font-weight: 500; color: var(--accent);">•</span> DOSSIER
            </div>
            <div class="ornament" style="margin: 0 auto 24px;"></div>
            <h1 style="font-size: 1.1rem; color: var(--primary); text-transform: uppercase; letter-spacing: 0.3em; font-weight: 400; font-family: 'Jost', sans-serif;">
                {{ $campaign->name }}</h1>
        </div>

        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Erreur',
                        html: '<ul style="text-align:left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                        icon: 'error',
                        confirmButtonColor: '#003229'
                    });
                });
            </script>
        @endif
        
        <form action="{{ route('candidates.update-apply', $candidate->id) }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 40px;">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Nom Complet</label>
                    <input type="text" name="name" value="{{ old('name', $candidate->name) }}" required
                        style="height: 65px; font-size: 1.1rem; border-radius: 4px; border: 1px solid var(--border); width: 100%; padding: 0 25px; background: #f9f9f9;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Numéro de Téléphone</label>
                    <input type="text" value="{{ Auth::user()->phone ?? 'Pas encore renseigné' }}" disabled
                        style="height: 65px; font-size: 1.1rem; border-radius: 4px; border: 1px solid var(--border); width: 100%; padding: 0 25px; background: #eee; color: #666; cursor: not-allowed;">
                    <div style="font-size: 0.6rem; color: var(--accent); margin-top: 5px; text-transform: uppercase;">Récupéré depuis votre compte</div>
                </div>
            </div>

            <div>
                <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Présentation / Motivations</label>
                <textarea name="description" rows="6" required style="width: 100%; border: 1px solid var(--border); padding: 20px 25px; border-radius: 4px; font-size: 1.1rem; font-family: 'Jost', sans-serif; min-height: 180px;">{{ old('description', $candidate->description) }}</textarea>
            </div>

            <!-- Prévisualisation des médias actuels -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; padding: 20px; background: #f9f6f0; border-radius: 4px; border: 1px dashed var(--border);">
                <div>
                    <div style="font-size: 0.65rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; margin-bottom: 15px;">Image Actuelle</div>
                    @if($candidate->image_path)
                        <img src="{{ asset('storage/' . $candidate->image_path) }}" style="width: 100%; height: 180px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border);">
                    @else
                        <div style="height: 180px; background: var(--border); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: var(--text-dim);">AUCUNE IMAGE</div>
                    @endif
                </div>
                <div>
                    <div style="font-size: 0.65rem; font-weight: 700; color: var(--text-dim); text-transform: uppercase; margin-bottom: 15px;">Vidéo Actuelle</div>
                    @if($candidate->video_path)
                        <video controls style="width: 100%; height: 180px; border-radius: 4px; border: 1px solid var(--border); background: #000;">
                            <source src="{{ asset('storage/' . $candidate->video_path) }}" type="video/mp4">
                        </video>
                    @else
                        <div style="height: 180px; background: var(--border); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: var(--text-dim);">AUCUNE VIDÉO</div>
                    @endif
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Changer la Photo (Optionnel)</label>
                    <input type="file" name="image_path" accept="image/*" style="width: 100%; border: 1px solid var(--border); padding: 15px; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Changer la Vidéo (Optionnel)</label>
                    <input type="file" name="video" accept="video/*" style="width: 100%; border: 1px solid var(--border); padding: 15px; border-radius: 4px;">
                </div>
            </div>

            <div style="display: flex; gap: 20px; margin-top: 20px;">
                <a href="{{ route('dashboard') }}" class="btn btn-outline" style="flex: 1; height: 70px; display: flex; align-items: center; justify-content: center;">ANNULER</a>
                <button type="submit" class="btn btn-primary" style="flex: 2; height: 70px;">ENREGISTRER LES MODIFICATIONS</button>
            </div>
        </form>

        <form id="delete-candidacy-form" action="{{ route('candidates.destroy-apply', $candidate->id) }}" method="POST" style="margin-top: 40px; text-align: center;">
            @csrf
            @method('DELETE')
            <button type="button" onclick="Swal.fire({
                title: 'Retirer votre candidature ?',
                text: 'Votre dossier sera définitivement supprimé de ce scrutin.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#003229',
                confirmButtonText: 'Oui, retirer',
                cancelButtonText: 'Conserver dossier'
            }).then((result) => { if (result.isConfirmed) document.getElementById('delete-candidacy-form').submit(); })" 
            style="background: none; border: none; color: #ef4444; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; cursor: pointer; text-decoration: underline;">Retirer définitivement ma candidature</button>
        </form>
    </div>
</div>
@endsection
