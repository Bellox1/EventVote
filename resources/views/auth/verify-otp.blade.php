@extends('layouts.app')

@section('title', 'Vérification du Code')

@section('content')
    <div style="max-width: 600px; margin: 60px auto; padding: 0 20px;">
        <div class="card"
            style="border-bottom: 6px solid var(--accent); padding: 80px 60px; box-shadow: var(--shadow-hard);">
            <div style="text-align: center; margin-bottom: 60px;">
                <div
                    style="font-family: 'Cormorant Garamond', serif; font-size: 3rem; color: var(--primary); letter-spacing: 0.05em; margin-bottom: 16px; font-weight: 300;">
                    VÉRIFI <span style="font-weight: 500; color: var(--accent);">•</span> CATION
                </div>
                <div class="ornament" style="margin: 0 auto 32px;"></div>
                <h1
                    style="font-size: 1.4rem; color: var(--primary); text-transform: uppercase; letter-spacing: 0.3em; font-weight: 400; font-family: 'Jost', sans-serif;">
                    Code Secret</h1>
                <p style="color: var(--text-dim); font-size: 0.9rem; margin-top: 20px; font-family: 'Jost', sans-serif; line-height: 1.6;">
                    Entrez le code OTP à 6 chiffres envoyé à l'adresse <strong>{{ $email }}</strong>.
                </p>
            </div>

            @if (session('success'))
                <div style="background: rgba(26, 77, 46, 0.1); color: #1a4d2e; padding: 15px; border-radius: 4px; margin-bottom: 30px; font-size: 0.9rem; text-align: center; border: 1px solid rgba(26, 77, 46, 0.2);">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.verify-otp-post') }}" id="otp-form" style="display: flex; flex-direction: column; gap: 35px;">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="otp" id="otp-full">
                
                <div class="form-group">
                    <label
                        style="display: block; font-weight: 600; font-size: 0.7rem; text-transform: uppercase; margin-bottom: 25px; color: var(--primary); letter-spacing: 0.25em; opacity: 0.8; text-align: center;">Code de Vérification</label>
                    
                    <div style="display: flex; justify-content: center; gap: 12px;" id="otp-inputs">
                        @for ($i = 1; $i <= 6; $i++)
                            <input type="text" maxlength="1" 
                                class="otp-digit"
                                style="width: 50px; height: 65px; text-align: center; background: white; border: 1px solid var(--border); border-bottom: 3px solid var(--border); font-family: 'Jost', sans-serif; font-size: 1.8rem; color: var(--primary); font-weight: 600; border-radius: 8px; outline: none; transition: all 0.3s;"
                                onfocus="this.style.borderBottomColor='var(--accent)'; this.style.transform='translateY(-2px)';"
                                onblur="this.style.borderBottomColor='var(--border)'; this.style.transform='translateY(0)';"
                                inputmode="numeric"
                            >
                        @endfor
                    </div>
                    @error('otp')
                        <span style="color: #bc3e3e; font-size: 0.8rem; margin-top: 20px; display: block; text-align: center;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" class="btn"
                        style="width: 100%; height: 70px; background: var(--primary); color: white; border: none; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.3em; text-transform: uppercase; cursor: pointer; transition: all 0.4s; border-radius: 4px;"
                        onmouseover="this.style.background='var(--primary-light)'; this.style.transform='translateY(-2px)';"
                        onmouseout="this.style.background='var(--primary)'; this.style.transform='translateY(0)';"
                    >VÉRIFIER LE CODE</button>
                </div>
            </form>

            <div style="margin-top: 50px; text-align: center; border-top: 1px solid var(--border); padding-top: 30px;">
                <p style="color: var(--text-dim); font-size: 0.8rem; font-family: 'Jost', sans-serif; margin-bottom: 10px;">Vous n'avez pas reçu le code ?</p>
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <button type="submit"
                        style="background: none; border: none; color: var(--accent); font-weight: 700; cursor: pointer; text-decoration: underline; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.15em;"
                    >RENVOYER UN CODE &rarr;</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.otp-digit');
            const fullInput = document.getElementById('otp-full');
            const form = document.getElementById('otp-form');

            inputs.forEach((input, index) => {
                // Focus the first input on load
                if (index === 0) input.focus();

                input.addEventListener('input', (e) => {
                    const value = e.target.value;
                    // Only allow digits
                    if (!/^\d$/.test(value)) {
                        e.target.value = '';
                        return;
                    }

                    if (value && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                    updateFullValue();
                });


                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });

                // Paste support
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const paste = (e.clipboardData || window.clipboardData).getData('text');
                    const digits = paste.match(/\d/g);
                    if (digits) {
                        digits.forEach((digit, i) => {
                            if (index + i < inputs.length) {
                                inputs[index + i].value = digit;
                            }
                        });
                        if (index + digits.length < inputs.length) {
                            inputs[index + digits.length].focus();
                        } else {
                            inputs[inputs.length - 1].focus();
                        }
                        updateFullValue();
                    }
                });
            });

            function updateFullValue() {
                let otp = '';
                inputs.forEach(input => otp += input.value);
                fullInput.value = otp;
            }

            form.addEventListener('submit', (e) => {
                updateFullValue();
                if (fullInput.value.length !== 6) {
                    e.preventDefault();
                    alert('Veuillez entrer le code complet de 6 chiffres.');
                }
            });
        });
    </script>
@endsection
