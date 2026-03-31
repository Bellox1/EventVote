@extends('layouts.app')

@section('title', 'Politique de Confidentialité')

@section('content')
<div class="card" style="background: var(--surface); border-radius: var(--radius); padding: 50px; box-shadow: var(--shadow-soft); max-width: 900px; margin: 0 auto; line-height: 1.8;">
    <h1 style="color: var(--primary); font-size: 2.8rem; margin-bottom: 20px; text-align: center; font-weight: 600;">Politique de Confidentialité</h1>
    <div style="width: 80px; height: 3px; background: var(--accent); margin: 0 auto 40px;"></div>

    <div style="font-size: 1.15rem; color: var(--text-main);">
        <p style="margin-bottom: 40px; text-align: center; font-style: italic; opacity: 0.8;">La protection de vos données personnelles et le respect de votre vie privée sont au cœur de nos engagements d'excellence.</p>

        <h2 style="color: var(--primary); font-size: 1.6rem; margin-bottom: 15px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">1. Finalité du traitement des données</h2>
        <p style="margin-bottom: 30px;">Les informations que vous nous confiez sont utilisées <strong>uniquement</strong> dans le cadre du bon fonctionnement de la plateforme. Cela inclut la gestion de vos sessions d'accès, l'enregistrement de vos votes, la sécurisation des scrutins et la communication liée aux événements auxquels vous participez.</p>
        
        <h2 style="color: var(--primary); font-size: 1.6rem; margin-bottom: 15px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">2. Absence de collecte commerciale</h2>
        <p style="margin-bottom: 30px;">Nous nous engageons fermement à ne pas exploiter vos données à des fins publicitaires. <strong>Aucune information personnelle n'est revendue, louée ou partagée à des tiers</strong> à des fins commerciales. Le traitement reste strictement interne et nécessaire à la délivrance du service.</p>
        
        <h2 style="color: var(--primary); font-size: 1.6rem; margin-bottom: 15px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">3. Sécurité et transparence</h2>
        <p style="margin-bottom: 30px;">L'intégrité de vos votes et de vos informations est primordiale. Nous appliquons des mesures de sécurité rigoureuses pour protéger l'ensemble de nos systèmes contre tout accès non autorisé et garantir la fiabilité absolue de chaque scrutin.</p>
        
        <h2 style="color: var(--primary); font-size: 1.6rem; margin-bottom: 15px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">4. Vos droits sur vos données</h2>
        <p style="margin-bottom: 30px;">Conformément aux normes en vigueur, vous disposez d'un droit d'accès, de rectification et de suppression totale de vos données via votre espace profil. Lors de la suppression volontaire d'un compte, l'ensemble des données associées sont purgées ou anonymisées sans délai.</p>
    </div>
</div>
@endsection
