@extends('layouts.app')

@section('title', 'Tarification & Transparence')

@section('content')
<style>
    @media (max-width: 768px) {
        .pricing-hero { margin-bottom: 40px !important; }
        .pricing-hero h1 { font-size: 2.2rem !important; }
        .pricing-grid { grid-template-columns: 1fr !important; gap: 30px !important; }
        .pricing-card { padding: 30px 20px !important; text-align: center; }
        .pricing-card ul { display: inline-block; text-align: left; }
        .simulation-section { padding: 40px 15px !important; margin-top: 60px !important; }
        .simulation-section h2 { font-size: 2rem !important; margin-bottom: 30px !important; }
        .simulation-card { padding: 25px 15px !important; text-align: center; }
        .simulation-card div[style*="text-align: right"] { text-align: center !important; }
        .contact-section { gap: 50px !important; margin-top: 60px !important; padding-top: 60px !important; text-align: center !important; }
        .contact-section h2 { font-size: 1.8rem !important; }
        .contact-section p { margin-left: auto; margin-right: auto; }
        .contact-info-item { flex-direction: column !important; text-align: center !important; }
        .contact-form-container { padding: 30px 20px !important; }
    }
</style>
<div style="max-width: 1200px; margin: 40px auto; padding: 0 15px; overflow-x: hidden;">
    
    <!-- Hero Section -->
    <div class="pricing-hero" style="text-align: center; margin-bottom: 100px;">
        <span style="font-size: 0.85rem; font-weight: 600; color: var(--accent); text-transform: uppercase; letter-spacing: 0.5em; display: block; margin-bottom: 20px;">Transparence Totale</span>
        <h1 style="font-family: 'Cormorant Garamond', serif; font-size: clamp(2.5rem, 8vw, 4.5rem); color: var(--primary); font-weight: 300; margin: 0; line-height: 1.1;">
            Nos <span style="font-style: italic;">Conditions.</span>
        </h1>
        <div class="ornament" style="margin: 30px auto;"></div>
        <p style="color: var(--text-dim); font-size: 1.2rem; max-width: 700px; margin: 0 auto; line-height: 1.8; font-family: 'Cormorant Garamond', serif; font-style: italic;">
            Nous prélevons une commission minimale pour assurer l'intégrité technique, la sécurité des votes et la maintenance de la plateforme de prestige.
        </p>
    </div>

    <div class="pricing-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 60px; align-items: start;">
        
        <!-- Commission Plateforme -->
        <div class="pricing-card" style="background: white; padding: 50px; border-radius: 4px; box-shadow: var(--shadow-soft); border-top: 5px solid var(--accent); position: relative;">
            <div style="position: absolute; top: -20px; right: 30px; background: var(--primary); color: white; padding: 10px 20px; font-weight: 800; font-size: 1.5rem; font-family: 'Cormorant Garamond', serif;">2%</div>
            <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 2rem; color: var(--primary); margin-bottom: 25px;">Commission Plateforme</h2>
            <p style="color: var(--text-dim); line-height: 1.7; margin-bottom: 30px;">
                Une commission unique de <strong>2%</strong> est appliquée sur le montant total des votes récoltés par chaque session. Ce montant couvre :
            </p>
            <ul style="list-style: none; padding: 0; color: var(--primary); font-weight: 600; font-size: 0.9rem;">
                <li style="margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                    <span style="color: var(--accent);">✦</span> Hébergement de Haute Disponibilité
                </li>
                <li style="margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                    <span style="color: var(--accent);">✦</span> Sécurisation Anti-Fraude des Scrutins
                </li>
                <li style="margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                    <span style="color: var(--accent);">✦</span> Support Technique 24/7
                </li>
            </ul>
        </div>

        <!-- Frais Agrégateur -->
        <div class="pricing-card" style="background: var(--primary); padding: 50px; border-radius: 4px; box-shadow: var(--shadow-soft); color: white;">
            <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 2rem; color: var(--accent); margin-bottom: 25px;">Frais de l'Agrégateur</h2>
            <p style="color: rgba(255,255,255,0.7); line-height: 1.7; margin-bottom: 30px;">
                Ces frais sont prélevés par l'agrégateur de paiement pour le traitement des transactions (Mobile Money, Cartes).
            </p>
            
            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                <thead style="border-bottom: 1px solid rgba(212, 174, 109, 0.3);">
                    <tr>
                        <th style="padding: 10px 0; text-align: left; color: var(--accent); text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.1em;">Tranche de Montant</th>
                        <th style="padding: 10px 0; text-align: right; color: var(--accent); text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.1em;">Commission Fixe</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td style="padding: 15px 0;">0 – 10 000 XOF</td>
                        <td style="padding: 15px 0; text-align: right; font-weight: 700;">150 XOF</td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td style="padding: 15px 0;">10 001 – 50 000 XOF</td>
                        <td style="padding: 15px 0; text-align: right; font-weight: 700;">300 XOF</td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td style="padding: 15px 0;">50 001 – 150 000 XOF</td>
                        <td style="padding: 15px 0; text-align: right; font-weight: 700;">800 XOF</td>
                    </tr>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td style="padding: 15px 0;">150 001 – 500 000 XOF</td>
                        <td style="padding: 15px 0; text-align: right; font-weight: 700;">2 000 XOF</td>
                    </tr>
                    <tr>
                        <td style="padding: 15px 0;">500 001 XOF et plus</td>
                        <td style="padding: 15px 0; text-align: right; font-weight: 700;">2 500 XOF</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Simulation Section (Luxury Redesign) -->
    <div class="simulation-section" style="margin-top: 120px; padding: 100px 40px; background: var(--primary); border-radius: 4px; position: relative; overflow: hidden; box-shadow: var(--shadow-hard);">
        <!-- Background decorative elements -->
        <div style="position: absolute; top: -100px; right: -100px; width: 300px; height: 300px; border: 1px solid rgba(212, 174, 109, 0.1); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -50px; left: -50px; width: 200px; height: 200px; border: 1px solid rgba(212, 174, 109, 0.1); border-radius: 50%;"></div>

        <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 3rem; color: var(--accent); margin-bottom: 60px; text-align: center; font-weight: 300;">
            Simulations de <span style="font-style: italic;">Transparence.</span>
        </h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 40px; position: relative; z-index: 2;">
            
            <!-- Exemple 1 (Premium Card) -->
            <div class="simulation-card" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(212, 174, 109, 0.3); padding: 40px; border-radius: 2px; backdrop-filter: blur(10px); transition: transform 0.4s;">
                <div style="font-size: 0.65rem; color: var(--accent); letter-spacing: 0.3em; text-transform: uppercase; margin-bottom: 20px; font-weight: 700;">Scrutin Modèle I</div>
                
                <div style="margin-bottom: 40px;">
                    <div style="font-size: 0.8rem; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 5px;">Total des suffrages</div>
                    <div style="font-size: 2.2rem; font-family: 'Cormorant Garamond', serif; color: white;">100 000 <span style="font-size: 0.8rem; color: var(--accent);">XOF</span></div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 20px; border-bottom: 1px solid rgba(212, 174, 109, 0.15); margin-bottom: 30px;">
                    <div style="font-size: 0.85rem; color: rgba(255,255,255,0.7);">Frais de Service (2%)</div>
                    <div style="font-size: 1.1rem; color: var(--accent); font-weight: 600;">- 2 000 XOF</div>
                </div>

                <div style="text-align: right;">
                    <div style="font-size: 0.7rem; color: var(--accent); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 5px;">Reversement Net Final</div>
                    <div style="font-size: 2.8rem; font-family: 'Cormorant Garamond', serif; color: white; line-height: 1;">98 000 <span style="font-size: 1rem;">XOF</span></div>
                </div>
                
                <div style="margin-top: 35px; font-size: 0.6rem; color: rgba(255,255,255,0.4); text-align: left; font-style: italic; line-height: 1.6;">
                    * Les frais d'intermédiation financière (agrégateur) sont intégralement absorbés par EventVote sur sa commission.
                </div>
            </div>

            <!-- Exemple 2 (Premium Card) -->
            <div class="simulation-card" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(212, 174, 109, 0.3); padding: 40px; border-radius: 2px; backdrop-filter: blur(10px); transition: transform 0.4s;">
                <div style="font-size: 0.65rem; color: var(--accent); letter-spacing: 0.3em; text-transform: uppercase; margin-bottom: 20px; font-weight: 700;">Scrutin Modèle II</div>
                
                <div style="margin-bottom: 40px;">
                    <div style="font-size: 0.8rem; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 5px;">Total des suffrages</div>
                    <div style="font-size: 2.2rem; font-family: 'Cormorant Garamond', serif; color: white;">550 000 <span style="font-size: 0.8rem; color: var(--accent);">XOF</span></div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 20px; border-bottom: 1px solid rgba(212, 174, 109, 0.15); margin-bottom: 30px;">
                    <div style="font-size: 0.85rem; color: rgba(255,255,255,0.7);">Frais de Service (2%)</div>
                    <div style="font-size: 1.1rem; color: var(--accent); font-weight: 600;">- 11 000 XOF</div>
                </div>

                <div style="text-align: right;">
                    <div style="font-size: 0.7rem; color: var(--accent); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 5px;">Reversement Net Final</div>
                    <div style="font-size: 2.8rem; font-family: 'Cormorant Garamond', serif; color: white; line-height: 1;">539 000 <span style="font-size: 1rem;">XOF</span></div>
                </div>
                
                <div style="margin-top: 35px; font-size: 0.6rem; color: rgba(255,255,255,0.4); text-align: left; font-style: italic; line-height: 1.6;">
                    * Les frais d'intermédiation financière (agrégateur) sont intégralement absorbés par EventVote sur sa commission.
                </div>
            </div>
        </div>
        
        <div style="margin-top: 80px; text-align: center;">
            <p style="font-size: 0.85rem; color: var(--accent); font-weight: 600; text-transform: uppercase; letter-spacing: 0.3em; opacity: 0.8;">
                Vous recevez 98% • Sans Frais Cachés.
            </p>
        </div>
    </div>

    <!-- Contact Section -->
    <div id="contact" class="contact-section" style="margin-top: 120px; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 80px; align-items: start; border-top: 1px solid var(--border); padding-top: 100px; margin-bottom: 50px;">
        
        <!-- Contact Info -->
        <div style="text-align: left;">
            <span style="font-size: 0.7rem; color: var(--accent); text-transform: uppercase; letter-spacing: 0.3em; font-weight: 700;">Besoin d'accompagnement ?</span>
            <h2 style="font-family: 'Cormorant Garamond', serif; font-size: clamp(2rem, 5vw, 3.5rem); color: var(--primary); margin: 20px 0; line-height: 1.1;">Contactez <span style="font-style: italic;">Nos Experts.</span></h2>
            <p style="color: var(--text-dim); line-height: 1.8; margin-bottom: 40px; font-size: 1.1rem; font-family: 'Cormorant Garamond', serif; font-style: italic;">
                Que vous soyez un organisateur d'événements prestigieux ou une institution, notre équipe est à votre entière disposition pour personnaliser votre expérience.
            </p>
            
            <div style="display: flex; flex-direction: column; gap: 30px;">
                <!-- Email Clickable -->
                <a href="mailto:{{ config('app.super_admin_email') }}" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 20px;" class="contact-info-item">
                    <div style="width: 50px; height: 50px; background: white; border: 1px solid var(--border); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--accent); box-shadow: var(--shadow-soft); transition: all 0.3s;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <div style="font-size: 0.65rem; text-transform: uppercase; color: var(--accent); letter-spacing: 0.2em; font-weight: 700; margin-bottom: 4px;">Demandes Générales</div>
                        <div style="font-weight: 500; color: var(--primary); font-size: 1.1rem;">{{ config('app.super_admin_email') }}</div>
                    </div>
                </a>

                <!-- Appel Direct -->
                <a href="tel:{{ str_replace(' ', '', config('app.super_admin_number')) }}" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 20px;" class="contact-info-item">
                    <div style="width: 50px; height: 50px; background: white; border: 1px solid var(--border); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--accent); box-shadow: var(--shadow-soft); transition: all 0.3s;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <div style="font-size: 0.65rem; text-transform: uppercase; color: var(--accent); letter-spacing: 0.2em; font-weight: 700; margin-bottom: 4px;">Assistance Conciergerie</div>
                        <div style="font-weight: 500; color: var(--primary); font-size: 1.1rem;">{{ config('app.super_admin_number') }}</div>
                    </div>
                </a>

                <!-- WhatsApp Direct -->
                <a href="https://wa.me/{{ str_replace(['+', ' '], '', config('app.super_admin_number')) }}" target="_blank" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 20px;" class="contact-info-item">
                    <div style="width: 50px; height: 50px; background: #25D366; border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 10px 20px rgba(37,211,102,0.2); transition: all 0.3s;">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </div>
                    <div>
                        <div style="font-size: 0.65rem; text-transform: uppercase; color: black; letter-spacing: 0.2em; font-weight: 700; margin-bottom: 4px;">Canal VIP</div>
                        <div style="font-weight: 500; color: var(--primary); font-size: 1.1rem;">Discuter sur WhatsApp</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form-container" style="background: white; padding: clamp(25px, 5vw, 60px); border-radius: 4px; box-shadow: var(--shadow-hard); border: 1px solid var(--border);">
            <form id="contactForm" method="POST" action="{{ route('contact.send') }}">
                @csrf
                <div style="margin-bottom: 30px;">
                    <label style="display: block; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.15em; color: var(--text-dim); margin-bottom: 12px; font-weight: 700;">Nom Complet</label>
                    <input type="text" name="name" required placeholder="Votre nom&prenom" style="width: 100%; padding: 18px; border: none; border-bottom: 1px solid var(--border); font-family: 'Jost', sans-serif; background: #fafafa; outline: none; transition: border-color 0.3s; font-size: 1rem;" onfocus="this.style.borderBottomColor='var(--accent)'; this.style.background='white'" onblur="this.style.borderBottomColor='var(--border)'; this.style.background='#fafafa'">
                </div>
                
                <div style="margin-bottom: 30px;">
                    <label style="display: block; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.15em; color: var(--text-dim); margin-bottom: 12px; font-weight: 700;">Email Professionnel</label>
                    <input type="email" name="email" required placeholder="nom@institution.com" style="width: 100%; padding: 18px; border: none; border-bottom: 1px solid var(--border); font-family: 'Jost', sans-serif; background: #fafafa; outline: none; transition: border-color 0.3s; font-size: 1rem;" onfocus="this.style.borderBottomColor='var(--accent)'; this.style.background='white'" onblur="this.style.borderBottomColor='var(--border)'; this.style.background='#fafafa'">
                </div>

                <div style="margin-bottom: 30px;">
                    <label style="display: block; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.15em; color: var(--text-dim); margin-bottom: 12px; font-weight: 700;">Sujet de la demande</label>
                    <input type="text" name="subject" required placeholder="Détail sur tarification,Accompagnement, Devis personnalisé,..." style="width: 100%; padding: 18px; border: none; border-bottom: 1px solid var(--border); font-family: 'Jost', sans-serif; background: #fafafa; outline: none; transition: border-color 0.3s; font-size: 1rem;" onfocus="this.style.borderBottomColor='var(--accent)'; this.style.background='white'" onblur="this.style.borderBottomColor='var(--border)'; this.style.background='#fafafa'">
                </div>

                <div style="margin-bottom: 40px;">
                    <label style="display: block; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.15em; color: var(--text-dim); margin-bottom: 12px; font-weight: 700;">Votre Message</label>
                    <textarea name="message" required rows="4" placeholder="Décrivez votre vision ou vos besoins spécifiques..." style="width: 100%; padding: 18px; border: none; border-bottom: 1px solid var(--border); font-family: 'Jost', sans-serif; background: #fafafa; outline: none; transition: border-color 0.3s; resize: none; font-size: 1rem;" onfocus="this.style.borderBottomColor='var(--accent)'; this.style.background='white'" onblur="this.style.borderBottomColor='var(--border)'; this.style.background='#fafafa'"></textarea>
                </div>

                <button type="submit" style="width: 100%; background: var(--primary); color: white; padding: 22px; border: none; border-radius: 2px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3em; cursor: pointer; transition: all 0.4s; font-size: 0.85rem;" onmouseover="this.style.background='var(--primary-light)'; this.style.letterSpacing='0.4em'; this.style.boxShadow='0 10px 30px rgba(0,50,41,0.2)'" onmouseout="this.style.background='var(--primary)'; this.style.letterSpacing='0.3em'; this.style.boxShadow='none'">Soumettre ma Demande</button>
            </form>
        </div>
    </div>

</div>
@endsection
