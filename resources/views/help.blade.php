@extends('layouts.app')

@section('title', 'Aide & Fonctionnement')

@section('content')
<div class="card" style="background: var(--surface); border-radius: var(--radius); padding: 50px; box-shadow: var(--shadow-soft); max-width: 900px; margin: 0 auto;">
    <h1 style="color: var(--primary); font-size: 2.8rem; margin-bottom: 20px; text-align: center; font-weight: 600;">Aide & Fonctionnement</h1>
    <div style="width: 80px; height: 3px; background: var(--accent); margin: 0 auto 40px;"></div>

    <div style="font-size: 1.15rem; color: var(--text-main); margin-bottom: 40px; line-height: 1.8;">
        <h2 style="color: var(--primary); font-size: 1.8rem; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">Que pouvez-vous faire sur ce site ?</h2>
        <ul style="list-style-type: none; padding-left: 0; display: flex; flex-direction: column; gap: 20px; margin-top: 20px;">
            <li style="display: flex; gap: 15px; align-items: flex-start;">
                <span style="color: var(--accent); font-size: 1.5rem; line-height: 1;">⟡</span> 
                <div>
                    <strong style="color: var(--primary); display: block; margin-bottom: 5px;">Explorer les événements :</strong>
                    Consultez les campagnes de votes actives et découvrez les profils des différents candidats avec élégance.
                </div>
            </li>
            <li style="display: flex; gap: 15px; align-items: flex-start;">
                <span style="color: var(--accent); font-size: 1.5rem; line-height: 1;">⟡</span> 
                <div>
                    <strong style="color: var(--primary); display: block; margin-bottom: 5px;">Voter pour vos candidats :</strong>
                    Participez de manière transparente en apportant votre soutien à vos favoris au sein d'un environnement sécurisé.
                </div>
            </li>
            <li style="display: flex; gap: 15px; align-items: flex-start;">
                <span style="color: var(--accent); font-size: 1.5rem; line-height: 1;">⟡</span> 
                <div>
                    <strong style="color: var(--primary); display: block; margin-bottom: 5px;">Créer et gérer un scrutin :</strong>
                    Mettez en place vos propres événements, validez ou rejetez des candidatures, et accédez aux analyses détaillées.
                </div>
            </li>
            <li style="display: flex; gap: 15px; align-items: flex-start;">
                <span style="color: var(--accent); font-size: 1.5rem; line-height: 1;">⟡</span> 
                <div>
                    <strong style="color: var(--primary); display: block; margin-bottom: 5px;">Soumettre une candidature :</strong>
                    Postulez directement à une campagne avec des visuels de haute qualité en attente de l'approbation des administrateurs de la session.
                </div>
            </li>
            <li style="display: flex; gap: 15px; align-items: flex-start;">
                <span style="color: var(--accent); font-size: 1.5rem; line-height: 1;">⟡</span> 
                <div>
                    <strong style="color: var(--primary); display: block; margin-bottom: 5px;">Rejoindre un scrutin exclusif :</strong>
                    Vous avez reçu un code de participation privée (ex: <i>LUX-VOTE-2024</i>) ? Entrez ce code dans la section "Participation Privée" sur la page d'accueil ou depuis votre tableau de bord pour accéder immédiatement à un événement à huis clos.
                </div>
            </li>
        </ul>
    </div>

    <div style="background: rgba(212, 174, 109, 0.05); border-left: 4px solid var(--accent); padding: 30px; border-radius: 0 var(--radius) var(--radius) 0; margin-top: 50px;">
        <h3 style="color: var(--primary); font-size: 1.6rem; margin-bottom: 15px; margin-top: 0;">Protection de vos données : Notre Engagement</h3>
        <p style="margin-bottom: 0; font-size: 1.1rem; line-height: 1.7;">Les données fournies sur cette plateforme sont utilisées <strong>exclusivement pour le traitement des votes et le bon fonctionnement du site</strong>. Nous n'effectuons <strong>aucune collecte à des fins commerciales</strong> ni aucune revente de vos informations personnelles. Votre confiance et votre confidentialité constituent la fondation de notre plateforme.</p>
    </div>
</div>
@endsection
