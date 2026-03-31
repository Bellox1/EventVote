@extends('layouts.app')

@section('title', 'Modifier le Scrutin')

@section('content')
<div style="max-width: 900px; margin: 60px auto; padding: 0 20px;">
    <div class="card" style="border-bottom: 6px solid var(--accent); padding: clamp(40px, 8vw, 80px) clamp(15px, 5vw, 60px); box-shadow: var(--shadow-hard);">
        
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 60px;">
            <div style="font-family: 'Cormorant Garamond', serif; font-size: clamp(1.8rem, 7vw, 3rem); color: var(--primary); letter-spacing: 0.05em; margin-bottom: 16px; font-weight: 300; line-height: 1.2;">
                MODIFICATION <span style="font-weight: 500; color: var(--accent);">•</span> SESSION
            </div>
            <div class="ornament" style="margin: 0 auto 32px;"></div>
            <h1 style="font-size: clamp(0.7rem, 4vw, 1.2rem); color: var(--primary); text-transform: uppercase; letter-spacing: 0.4em; font-weight: 400; font-family: 'Jost', sans-serif;">
                Ajustement des Paramètres</h1>
        </div>
        
        <form action="{{ route('campaigns.update', $campaign->slug) }}" method="POST" enctype="multipart/form-data" 
              x-data="{ imageSelected: false, videoSelected: false }"
              style="display: flex; flex-direction: column; gap: 50px;">
            @csrf
            @method('PUT')
            
            @if ($errors->any())
                <div style="padding: 24px; background: rgba(220, 38, 38, 0.05); border-left: 4px solid #dc2626; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px; color: #dc2626; font-size: 0.9rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Section 1: Informations Générales -->
            <div style="display: flex; flex-direction: column; gap: 35px;">
                <div style="font-size: 0.7rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.3em; margin-bottom: -15px;">Détails de la Session</div>
                
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Nom de la Campagne</label>
                    <input type="text" name="name" value="{{ $campaign->name }}" required placeholder="EX: VOTE PRÉSIDENT CLUB 2026"
                        style="height: 65px; font-size: 1.1rem; border-radius: 4px; border: 1px solid var(--border); width: 100%; padding: 0 25px;">
                </div>

                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Description / Objectifs</label>
                    <textarea name="description" rows="6" placeholder="Veuillez décrire l'enjeu de ce scrutin..."
                        x-data="{ 
                            resize() { 
                                $el.style.height = '200px'; 
                                $el.style.height = $el.scrollHeight + 'px' 
                            } 
                        }"
                        x-init="resize()"
                        @input="resize()"
                        style="width: 100%; border: 1px solid var(--border); padding: 25px; border-radius: 4px; font-size: 1.1rem; font-family: 'Jost', sans-serif; resize: none; min-height: 200px; overflow: hidden; transition: height 0.1s ease; -webkit-appearance: none; background: white; display: block;">{{ $campaign->description }}</textarea>
                </div>
            </div>

            <!-- Section 2: Finance & Monétisation -->
            <div style="display: flex; flex-direction: column; gap: 35px;">
                <div style="font-size: 0.7rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.3em; margin-bottom: -15px;">Finance & Monétisation</div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 350px), 1fr)); gap: 30px;">
                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Prix d'un Vote (En Devise)</label>
                        <input type="number" name="vote_price" value="{{ old('vote_price', $campaign->vote_price) }}" required min="0" placeholder="Ex: 100"
                            style="height: 65px; font-size: 1.1rem; border-radius: 4px; border: 1px solid var(--border); width: 100%; padding: 0 25px;">
                        <div style="font-size: 0.65rem; color: var(--text-dim); margin-top: 8px; font-style: italic;">Montant unitaire du vote (0 pour gratuit).</div>
                    </div>

                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Compte de Reversement</label>
                        <input type="text" name="bank_account" value="{{ old('bank_account', $campaign->bank_account) }}" placeholder="Ex: TMoney 90000000 / Flooz..."
                            style="height: 65px; font-size: 1.1rem; border-radius: 4px; border: 1px solid var(--border); width: 100%; padding: 0 25px;">
                        <div style="font-size: 0.65rem; color: var(--text-dim); margin-top: 8px; font-style: italic;">Ce champ restera confidentiel (entreprise).</div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Médias avec Masquage Dynamique -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                <div style="grid-column: span 2; font-size: 0.7rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 0.3em; margin-bottom: -15px;">Mise à jour de l'Identité Visuelle</div>
                
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Affiche (Photo)</label>
                    <input type="file" name="image" accept="image/*" @change="imageSelected = true"
                           style="width: 100%; border: 1px solid var(--border); padding: 15px; border-radius: 4px; font-size: 0.8rem; margin-bottom: 15px;">
                    
                    @if($campaign->image_path)
                        <div x-show="!imageSelected" x-transition style="background: rgba(0,0,0,0.02); padding: 15px; border-radius: 4px;">
                            <div style="font-size: 0.6rem; text-transform: uppercase; color: var(--accent); margin-bottom: 10px;">Aperçu Actuel :</div>
                            <img src="{{ str_starts_with($campaign->image_path, 'http') ? $campaign->image_path : asset('storage/' . $campaign->image_path) }}" 
                                 style="width: 100%; max-height: 200px; object-fit: contain; border-radius: 2px;">
                        </div>
                    @endif
                    <div x-show="imageSelected" style="font-size: 0.7rem; color: #10b981; font-weight: 600;">Nouvelle affiche sélectionnée ✓</div>
                </div>

                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.15em;">Vidéo (Optionnel)</label>
                    <input type="file" name="video" accept="video/*" @change="videoSelected = true"
                           style="width: 100%; border: 1px solid var(--border); padding: 15px; border-radius: 4px; font-size: 0.8rem; margin-bottom: 15px;">
                    
                    @if($campaign->video_path)
                        <div x-show="!videoSelected" x-data="{ skip(s) { $refs.prevVid.currentTime += s } }" x-transition style="background: black; padding: 15px; border-radius: 4px;">
                            <div style="font-size: 0.6rem; text-transform: uppercase; color: var(--accent); margin-bottom: 10px;">Vidéo Actuelle :</div>
                            <video x-ref="prevVid" controls style="width: 100%; max-height: 150px;">
                                <source src="{{ str_starts_with($campaign->video_path, 'http') ? $campaign->video_path : asset('storage/' . $campaign->video_path) }}" type="video/mp4">
                            </video>
                            <div style="display: flex; gap: 10px; margin-top: 10px; justify-content: center;">
                                <button type="button" @click="skip(-10)" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; padding: 4px 10px; font-size: 0.6rem; cursor: pointer;">⟲ -10s</button>
                                <button type="button" @click="skip(10)" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; padding: 4px 10px; font-size: 0.6rem; cursor: pointer;">+10s ⟳</button>
                            </div>
                        </div>
                    @else

                         <div x-show="!videoSelected" style="padding: 30px; border: 1px dashed var(--border); text-align: center; color: var(--text-dim); font-size: 0.8rem;">
                            Aucune vidéo existante.
                        </div>
                    @endif
                    <div x-show="videoSelected" style="font-size: 0.7rem; color: #10b981; font-weight: 600;">Nouvelle vidéo sélectionnée ✓</div>
                </div>
            </div>

            <div style="padding: 24px; background: rgba(0, 50, 41, 0.05); border-left: 4px solid var(--primary); border-radius: 0 8px 8px 0;">
                <p style="margin: 0; color: var(--primary); font-size: 0.9rem;">
                    <strong>Note</strong> : Si vous ne sélectionnez pas de nouveau fichier, les médias actuels seront conservés.
                </p>
            </div>

            <div style="display: flex; gap: 20px; margin-top: 30px; flex-wrap: wrap;">
                <a href="{{ route('dashboard') }}" class="btn btn-outline" style="flex: 1; min-width: 150px; height: 75px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">ANNULER</a>
                <button type="submit" class="btn" style="flex: 2; min-width: 250px; height: 75px; font-size: 1rem; border-radius: 4px; background: var(--primary); color: white; border: none; font-weight: 600; cursor: pointer;">ENREGISTRER LES MODIFICATIONS</button>
            </div>
        </form>
    </div>
</div>
@endsection
