@extends('layouts.app')

@section('title', 'Gestion de Compte')

@section('content')
    <div style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">

        <!-- En-tête -->
        <div style="text-align: center; margin-bottom: 60px;">
            <div
                style="font-family: 'Cormorant Garamond', serif; font-size: clamp(2.5rem, 8vw, 3.5rem); color: var(--primary); margin-bottom: 10px;">
                Mon Profil</div>
            <div class="ornament" style="margin: 0 auto 30px;"></div>
        </div>

        <style>
            @media (max-width: 768px) {
                .profile-grid {
                    grid-template-columns: 1fr !important;
                    gap: 30px !important;
                }

                .profile-card {
                    padding: 40px 25px !important;
                }

                .danger-zone {
                    padding: 40px 20px !important;
                    margin-top: 60px !important;
                }

                .otp-box {
                    width: 38px !important;
                    height: 48px !important;
                    font-size: 18px !important;
                }
            }
        </style>

        <div class="profile-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px;">

            <!-- Informations Personnelles -->
            <div class="card profile-card" style="padding: 60px 50px; border-bottom: 4px solid var(--primary);">
                <h3
                    style="font-family: 'Cormorant Garamond', serif; font-size: 1.8rem; color: var(--primary); margin-bottom: 40px; border-bottom: 1px solid var(--border); padding-bottom: 15px; font-weight: 300;">
                    Identité</h3>

                <form action="{{ route('profile.update') }}" method="POST"
                    style="display: flex; flex-direction: column; gap: 40px;">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label
                            style="display: block; font-weight: 600; font-size: 0.7rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.25em; opacity: 0.8;">Nom
                            Complet</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            style="width: 100%; height: 50px; padding: 0 0; background: transparent; border: none; border-bottom: 1px solid var(--border); font-family: 'Jost', sans-serif; font-size: 1.1rem; color: var(--primary); letter-spacing: 0.05em; transition: all 0.4s; outline: none;"
                            onfocus="this.style.borderBottomColor='var(--primary)'; this.style.paddingLeft='10px';"
                            onblur="this.style.borderBottomColor='var(--border)'; this.style.paddingLeft='0';">
                    </div>

                    <div class="form-group">
                        <label
                            style="display: block; font-weight: 600; font-size: 0.7rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.25em; opacity: 0.8;">Adresse
                            Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            style="width: 100%; height: 50px; padding: 0 0; background: transparent; border: none; border-bottom: 1px solid var(--border); font-family: 'Jost', sans-serif; font-size: 1.1rem; color: var(--primary); letter-spacing: 0.05em; transition: all 0.4s; outline: none;"
                            onfocus="this.style.borderBottomColor='var(--primary)'; this.style.paddingLeft='10px';"
                            onblur="this.style.borderBottomColor='var(--border)'; this.style.paddingLeft='0';">
                    </div>

                    <div class="form-group">
                        <label
                            style="display: block; font-weight: 600; font-size: 0.7rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.25em; opacity: 0.8;">Téléphone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                            placeholder="+228 XX XX XX XX"
                            style="width: 100%; height: 50px; padding: 0 0; background: transparent; border: none; border-bottom: 1px solid var(--border); font-family: 'Jost', sans-serif; font-size: 1.1rem; color: var(--primary); letter-spacing: 0.05em; transition: all 0.4s; outline: none;"
                            onfocus="this.style.borderBottomColor='var(--primary)'; this.style.paddingLeft='10px';"
                            onblur="this.style.borderBottomColor='var(--border)'; this.style.paddingLeft='0';">
                    </div>

                    <button type="submit" class="btn"
                        style="width: 100%; height: 65px; background: var(--primary); color: white; border: none; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.3em; text-transform: uppercase; cursor: pointer; transition: all 0.4s; border-radius: 4px; margin-top: 10px;"
                        onmouseover="this.style.background='var(--primary-light)'; this.style.transform='translateY(-2px)';"
                        onmouseout="this.style.background='var(--primary)'; this.style.transform='translateY(0)';">ENREGISTRER</button>
                </form>
            </div>

            <!-- Sécurité / Mot de passe -->
            <div class="card profile-card" style="padding: 60px 50px; border-bottom: 4px solid var(--accent);">
                <h3
                    style="font-family: 'Cormorant Garamond', serif; font-size: 1.8rem; color: var(--primary); margin-bottom: 40px; border-bottom: 1px solid var(--border); padding-bottom: 15px; font-weight: 300;">
                    Sécurité</h3>

                <form action="{{ route('profile.password') }}" method="POST"
                    style="display: flex; flex-direction: column; gap: 40px;">
                    @csrf
                    @method('PUT')

                    <div x-data="{ show: false }" class="form-group">
                        <label
                            style="display: block; font-weight: 600; font-size: 0.7rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.25em; opacity: 0.8;">Mot
                            de passe actuel</label>
                        <div style="position: relative;">
                            <input :type="show ? 'text' : 'password'" name="current_password" required
                                style="width: 100%; height: 50px; padding: 0 0; background: transparent; border: none; border-bottom: 1px solid var(--border); font-family: 'Jost', sans-serif; font-size: 1.1rem; color: var(--primary); letter-spacing: 0.1em; transition: all 0.4s; outline: none;"
                                onfocus="this.style.borderBottomColor='var(--primary)'; this.style.paddingLeft='10px';"
                                onblur="this.style.borderBottomColor='var(--border)'; this.style.paddingLeft='0';">
                            <button type="button" @click="show = !show"
                                style="position: absolute; right: 0; top: 12px; background: none; border: none; color: var(--primary); cursor: pointer; opacity: 0.5;">
                                <svg x-show="!show" width="20" height="20" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" width="20" height="20" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24" x-cloak>
                                    <path
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L5.93 5.93m7.444 7.444l3.95 3.95M13.475 4.835A9.959 9.959 0 0112 5c4.477 0 8.268 2.943 9.542 7"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div x-data="{ show: false }" class="form-group">
                        <label
                            style="display: block; font-weight: 600; font-size: 0.7rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.25em; opacity: 0.8;">Nouveau
                            mot de passe</label>
                        <div style="position: relative;">
                            <input :type="show ? 'text' : 'password'" name="password" required
                                style="width: 100%; height: 50px; padding: 0 0; background: transparent; border: none; border-bottom: 1px solid var(--border); font-family: 'Jost', sans-serif; font-size: 1.1rem; color: var(--primary); letter-spacing: 0.1em; transition: all 0.4s; outline: none;"
                                onfocus="this.style.borderBottomColor='var(--primary)'; this.style.paddingLeft='10px';"
                                onblur="this.style.borderBottomColor='var(--border)'; this.style.paddingLeft='0';">
                            <button type="button" @click="show = !show"
                                style="position: absolute; right: 0; top: 12px; background: none; border: none; color: var(--primary); cursor: pointer; opacity: 0.5;">
                                <svg x-show="!show" width="20" height="20" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" width="20" height="20" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24" x-cloak>
                                    <path
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L5.93 5.93m7.444 7.444l3.95 3.95M13.475 4.835A9.959 9.959 0 0112 5c4.477 0 8.268 2.943 9.542 7"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div x-data="{ show: false }" class="form-group">
                        <label
                            style="display: block; font-weight: 600; font-size: 0.7rem; text-transform: uppercase; margin-bottom: 12px; color: var(--primary); letter-spacing: 0.25em; opacity: 0.8;">Confirmation</label>
                        <div style="position: relative;">
                            <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                                style="width: 100%; height: 50px; padding: 0 0; background: transparent; border: none; border-bottom: 1px solid var(--border); font-family: 'Jost', sans-serif; font-size: 1.1rem; color: var(--primary); letter-spacing: 0.1em; transition: all 0.4s; outline: none;"
                                onfocus="this.style.borderBottomColor='var(--primary)'; this.style.paddingLeft='10px';"
                                onblur="this.style.borderBottomColor='var(--border)'; this.style.paddingLeft='0';">
                            <button type="button" @click="show = !show"
                                style="position: absolute; right: 0; top: 12px; background: none; border: none; color: var(--primary); cursor: pointer; opacity: 0.5;">
                                <svg x-show="!show" width="20" height="20" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" width="20" height="20" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24" x-cloak>
                                    <path
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L5.93 5.93m7.444 7.444l3.95 3.95M13.475 4.835A9.959 9.959 0 0112 5c4.477 0 8.268 2.943 9.542 7"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn"
                        style="width: 100%; height: 65px; background: none; border: 1px solid var(--accent); color: var(--accent); font-size: 0.8rem; font-weight: 700; letter-spacing: 0.3em; text-transform: uppercase; cursor: pointer; transition: all 0.4s; border-radius: 4px; margin-top: 10px;"
                        onmouseover="this.style.background='var(--accent)'; this.style.color='white'; this.style.transform='translateY(-2px)';"
                        onmouseout="this.style.background='none'; this.style.color='var(--accent)'; this.style.transform='translateY(0)';">MODIFIER
                        LE CODE D'ACCÈS</button>
                </form>
            </div>

        </div>

        <!-- ZONE DE DANGER : Suppression de Compte -->
        <div class="danger-zone"
            style="margin-top: 80px; padding: 60px; background: white; border-radius: 4px; box-shadow: var(--shadow-soft); border: 2px dashed #ef4444; text-align: center; border-bottom: 6px solid #ef4444;">
            <h3
                style="font-family: 'Cormorant Garamond', serif; font-size: 1.8rem; color: #ef4444; margin-bottom: 20px; font-weight: 300;">
                Suppression Définitive</h3>
            <p
                style="color: var(--text-dim); max-width: 600px; margin: 0 auto 40px; line-height: 1.8; font-style: italic; font-family: 'Cormorant Garamond', serif; font-size: 1.2rem;">
                L'élégance s'efface. La suppression est irréversible et entraine la perte de toutes vos données
                prestigieuses.</p>

            <button id="delete-account-btn"
                style="background: none; border: 1px solid #ef4444; color: #ef4444; padding: 25px 50px; font-weight: 700; letter-spacing: 0.25em; text-transform: uppercase; transition: 0.4s; cursor: pointer; border-radius: 4px; font-size: 0.8rem;"
                onmouseover="this.style.background='#ef4444'; this.style.color='white';"
                onmouseout="this.style.background='none'; this.style.color='#ef4444';">
                RÉVOQUER MON COMPTE DÉFINITIVEMENT
            </button>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete Account Logic
            const deleteBtn = document.getElementById('delete-account-btn');
            deleteBtn.addEventListener('click', async function() {
                // Step 1: Password Confirmation
                const {
                    value: password
                } = await Swal.fire({
                    title: 'Confirmer la suppression',
                    text: 'Veuillez saisir votre mot de passe pour continuer.',
                    input: 'password',
                    inputPlaceholder: 'Mot de passe actuel',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#003229',
                    confirmButtonText: 'Suivant',
                    cancelButtonText: 'Annuler',
                    background: '#fff8e7',
                    color: '#003229'
                });

                if (password) {
                    // Step 2: Request OTP
                    Swal.fire({
                        title: 'Envoi du code...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    try {
                        const response = await fetch("{{ route('profile.delete.request') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                password: password
                            })
                        });
                        const data = await response.json();

                        if (response.ok) {
                            // Step 3: Sophisticated 6-digit OTP Input (GitHub style)
                            const {
                                value: otp
                            } = await Swal.fire({
                                title: 'Code OTP envoyé',
                                html: `
                                <p style="color: var(--text-dim); margin-bottom: 25px;">Saisissez le code secret reçu par e-mail.</p>
                                <div id="otp-inputs" style="display: flex; gap: 8px; justify-content: center; margin-bottom: 20px;">
                                    <input type="text" maxlength="1" class="otp-box" inputmode="numeric" pattern="[0-9]*" style="width: 42px; height: 52px; text-align: center; font-size: 22px; border: 1px solid var(--border); border-radius: 4px; background: #f9f6f0; color: var(--primary); font-weight: 700; outline: none;">
                                    <input type="text" maxlength="1" class="otp-box" inputmode="numeric" pattern="[0-9]*" style="width: 42px; height: 52px; text-align: center; font-size: 22px; border: 1px solid var(--border); border-radius: 4px; background: #f9f6f0; color: var(--primary); font-weight: 700; outline: none;">
                                    <input type="text" maxlength="1" class="otp-box" inputmode="numeric" pattern="[0-9]*" style="width: 42px; height: 52px; text-align: center; font-size: 22px; border: 1px solid var(--border); border-radius: 4px; background: #f9f6f0; color: var(--primary); font-weight: 700; outline: none;">
                                    <input type="text" maxlength="1" class="otp-box" inputmode="numeric" pattern="[0-9]*" style="width: 42px; height: 52px; text-align: center; font-size: 22px; border: 1px solid var(--border); border-radius: 4px; background: #f9f6f0; color: var(--primary); font-weight: 700; outline: none;">
                                    <input type="text" maxlength="1" class="otp-box" inputmode="numeric" pattern="[0-9]*" style="width: 42px; height: 52px; text-align: center; font-size: 22px; border: 1px solid var(--border); border-radius: 4px; background: #f9f6f0; color: var(--primary); font-weight: 700; outline: none;">
                                    <input type="text" maxlength="1" class="otp-box" inputmode="numeric" pattern="[0-9]*" style="width: 42px; height: 52px; text-align: center; font-size: 22px; border: 1px solid var(--border); border-radius: 4px; background: #f9f6f0; color: var(--primary); font-weight: 700; outline: none;">
                                </div>
                            `,
                                showCancelButton: true,
                                confirmButtonColor: '#ef4444',
                                confirmButtonText: 'Confirmer la suppression',
                                cancelButtonText: 'Annuler',
                                background: '#fff8e7',
                                color: '#003229',
                                didOpen: () => {
                                    const inputs = document.querySelectorAll('.otp-box');
                                    inputs[0].focus();

                                    inputs.forEach((input, index) => {
                                        input.addEventListener('input', (e) => {
                                            if (e.target.value.length ===
                                                1 && index < inputs.length -
                                                1) {
                                                inputs[index + 1].focus();
                                            }
                                        });
                                        input.addEventListener('keydown', (e) => {
                                            if (e.key === 'Backspace' && !e
                                                .target.value && index > 0
                                                ) {
                                                inputs[index - 1].focus();
                                            }
                                            if (!/[0-9]|Backspace|Tab|Enter/
                                                .test(e.key)) {
                                                e.preventDefault();
                                            }
                                        });
                                    });
                                },
                                preConfirm: () => {
                                    const code = Array.from(document.querySelectorAll(
                                            '.otp-box'))
                                        .map(input => input.value)
                                        .join('');
                                    if (code.length < 6) {
                                        Swal.showValidationMessage(
                                            'Veuillez saisir les 6 chiffres du code.');
                                        return false;
                                    }
                                    return code;
                                }
                            });

                            if (otp) {
                                Swal.fire({
                                    title: 'Révocation en cours...',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });

                                const confirmResp = await fetch(
                                "{{ route('profile.delete.confirm') }}", {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        otp: otp
                                    })
                                });
                                const result = await confirmResp.json();

                                if (confirmResp.ok) {
                                    Swal.fire({
                                        title: 'Adieu',
                                        text: 'Votre compte a été révoqué avec succès.',
                                        icon: 'success',
                                        confirmButtonColor: '#003229'
                                    }).then(() => {
                                        window.location.href = result.redirect;
                                    });
                                } else {
                                    Swal.fire('Erreur', result.message || 'Le code OTP est incorrect.',
                                        'error');
                                }
                            }
                        } else {
                            // Handling specific server errors (like validation or mail failure)
                            const errorMsg = data.message || (response.status === 422 ?
                                'Le mot de passe saisi est invalide.' : 'Une erreur est survenue.');
                            Swal.fire('Erreur', errorMsg, 'error');
                        }
                    } catch (e) {
                        Swal.fire('Erreur', 'le mot de passe saisi est invalide.', 'error');
                    }
                }
            });
        });
    </script>

    <!-- Affichage des messages Flash -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Succès',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonColor: '#003229',
                    background: '#fff8e7',
                    color: '#003229'
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Erreur',
                    html: '<ul style="text-align:left;">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                    icon: 'error',
                    confirmButtonColor: '#003229',
                    background: '#fff8e7',
                    color: '#003229'
                });
            });
        </script>
    @endif
@endsection
