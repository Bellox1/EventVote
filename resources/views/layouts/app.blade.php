<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="referrer" content="no-referrer">
    <title>@yield('title', 'Plateforme de Vote') - Excellence & Transparence</title>

    <!-- Fonts: Cormorant Garamond for Serif, Jost for Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Jost:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        :root {
            --primary: #003229;
            --primary-light: #004D41;
            --accent: #d4ae6d;
            --accent-hover: #b59069;
            --bg: #fff8e7;
            --surface: #FFFFFF;
            --border: #E8E2D9;
            --text-main: #00332B;
            --text-dim: #6B7A77;
            --white: #FFFFFF;
            --shadow-soft: 0 4px 20px rgba(0, 51, 43, 0.04);
            --shadow-hard: 0 10px 40px rgba(0, 51, 43, 0.08);
            --radius: 20px;
        }

        body {
            font-family: 'Jost', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        .serif {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 500;
        }

        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            box-sizing: border-box;
        }

        /* Skinny Smart Header */
        header {
            height: 80px;
            display: flex;
            align-items: center;
            background: transparent;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), background 0.4s, height 0.4s;
        }

        header.scrolled {
            background: rgba(0, 50, 41, 0.95);
            backdrop-filter: blur(10px);
            height: 65px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        header.hidden {
            transform: translateY(-100%);
        }

        .header-content {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        .nav-side-left,
        .nav-side-right {
            display: flex;
            align-items: center;
        }

        .nav-side-left {
            justify-content: flex-start;
            gap: 20px;
        }

        .nav-side-right {
            justify-content: flex-end;
            gap: 24px;
        }

        .logo-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem;
            letter-spacing: 0.15em;
            font-weight: 400;
            text-transform: uppercase;
            transition: color 0.4s;
        }

        header.scrolled .logo-text,
        header.scrolled .burger-line,
        header.scrolled .explorer-text {
            color: white !important;
        }

        /* Side Drawer */
        .side-drawer {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 100%;
            max-width: 450px;
            background: var(--primary);
            z-index: 4000;
            padding: 80px 60px;
            display: flex;
            flex-direction: column;
            box-shadow: 20px 0 100px rgba(0, 0, 0, 0.5);
            transform: translateX(-110%);
            transition: transform 0.6s cubic-bezier(0.19, 1, 0.22, 1);
        }

        .side-drawer.is-open {
            transform: translateX(0);
        }

        .drawer-link {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            color: white !important;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 25px;
            transition: all 0.4s;
            opacity: 0.8;
            font-weight: 300;
        }

        .drawer-link:hover,
        .drawer-link.active {
            opacity: 1;
            color: var(--accent) !important;
            padding-left: 10px;
        }

        .drawer-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            z-index: 3500;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.5s;
        }

        .drawer-overlay.is-open {
            opacity: 1;
            pointer-events: auto;
        }

        /* Burger Component */
        .burger-menu {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 20px;
            z-index: 1500;
        }

        .burger-lines {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .burger-line {
            height: 2px;
            background: var(--primary);
            transition: all 0.4s;
            border-radius: 4px;
        }

        .burger-menu:hover .burger-line {
            background: var(--accent) !important;
        }

        /* Back to Top */
        .back-to-top {
            position: fixed;
            bottom: 40px;
            right: 40px;
            width: 50px;
            height: 50px;
            background: var(--primary);
            color: var(--accent);
            border: 1px solid var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 900;
            transition: all 0.4s;
            opacity: 0;
            pointer-events: none;
        }

        .back-to-top.visible {
            opacity: 1;
            pointer-events: auto;
        }

        .back-to-top:hover {
            background: var(--accent);
            color: var(--primary);
            transform: translateY(-5px);
        }

        footer {
            background: var(--primary);
            color: rgba(255, 255, 255, 0.8);
            padding: 80px 0 40px;
            border-top: 1px solid rgba(199, 161, 122, 0.15);
        }

        .footer-logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: white !important;
        }

        a,
        a:visited,
        a:hover,
        a:active {
            text-decoration: none;
            color: inherit;
        }

        /* Pagination Style (Boutique) */
        .pagination {
            display: flex;
            gap: 15px;
            list-style: none;
            padding: 0;
            align-items: center;
        }

        .page-item {
            font-family: 'Jost', sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .page-link {
            color: var(--primary);
            padding: 8px 16px;
            border: 1px solid var(--border);
            transition: all 0.3s;
            opacity: 0.7;
        }

        .page-item.active .page-link {
            color: var(--accent);
            border-color: var(--accent);
            opacity: 1;
        }

        .page-link:hover {
            opacity: 1;
            border-color: var(--accent);
            color: var(--accent);
        }

        .page-item.disabled .page-link {
            opacity: 0.3;
            pointer-events: none;
        }

        .menu-indent {
            padding-left: 20px;
            border-left: 1px solid rgba(212, 174, 109, 0.15);
            margin-left: 8px;
            margin-top: 5px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .rotate-180 {
            transform: rotate(180deg);
        }

        .drawer-sub-link {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.15rem;
            color: #FFFFFF !important; /* Pure white */
            text-decoration: none;
            transition: all 0.2s;
            padding: 8px 0;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: 0.05em;
        }

        .drawer-sub-link:hover,
        .drawer-sub-link:hover span {
            color: var(--accent) !important;
            padding-left: 5px;
        }

        .drawer-section-title {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.3em;
            color: var(--accent);
            margin-bottom: 25px;
            opacity: 0.6;
            font-weight: 600;
        }

        /* Subtle Custom Scrollbar for Drawer Navigation */
        nav::-webkit-scrollbar {
            width: 4px; /* Even thinner */
        }

        nav::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        nav::-webkit-scrollbar-thumb {
            background: rgba(212, 174, 109, 0.35); /* Muted/Lower Opacity gold */
            border-radius: 10px;
            transition: background 0.3s;
        }

        nav::-webkit-scrollbar-thumb:hover {
            background: rgba(212, 174, 109, 0.6); /* Slightly more visible on hover */
        }
    </style>
    @yield('styles')
</head>

<body x-data="{
    mobileMenu: false,
    scrolled: false,
    headerHidden: false,
    lastScrollY: 0,
    init() {
        window.addEventListener('scroll', () => {
            this.scrolled = window.pageYOffset > 50;
            if (window.pageYOffset > this.lastScrollY && window.pageYOffset > 100) {
                this.headerHidden = true;
            } else {
                this.headerHidden = false;
            }
            this.lastScrollY = window.pageYOffset;
        });
    }
} " x-init="init()">

    <!-- Drawer Overlay -->
    <div :class="{ 'is-open': mobileMenu }" @click="mobileMenu = false" class="drawer-overlay"></div>

    <!-- Side Drawer -->
    <div :class="{ 'is-open': mobileMenu }" class="side-drawer">
        <div style="display: flex; justify-content: flex-end; margin-bottom: 60px;">
            <button @click="mobileMenu = false"
                style="background: none; border: none; color: white; cursor: pointer; font-size: 0.75rem; letter-spacing: 0.3em; text-transform: uppercase;">Fermer
                [X]</button>
        </div>

        <nav style="display: flex; flex-direction: column; flex: 1; overflow-y: auto; padding-right: 10px;">
            <div class="drawer-section-title">Navigation Principale</div>
            <a href="/" class="drawer-link {{ request()->is('/') ? 'active' : '' }}">Accueil</a>
            
            @php
                $currentCampaign = null;
                $slug = request()->route('slug');
                if ($slug) {
                    $currentCampaign = \App\Models\Campaign::where('slug', $slug)->first();
                }

                $user = Auth::user();
                $allActiveCampaigns = \App\Models\Campaign::where('status', 'active')->latest()->get();
                $myCreatedCampaigns = $user ? $user->campaigns()->latest()->get() : collect();
                $myVotedCampaigns = collect();
                if ($user && $myCreatedCampaigns->isEmpty()) {
                    $votedIds = $user->votes()->pluck('campaign_id')->unique();
                    $myVotedCampaigns = \App\Models\Campaign::whereIn('id', $votedIds)->latest()->get();
                }
            @endphp

            <!-- SECTION ÉVÉNEMENTS GLOBAUX -->
            @if($currentCampaign)
                <div x-data="{ open: true }" style="margin-bottom: 25px;">
                    <button @click="open = !open"
                        style="display:flex; justify-content:space-between; align-items: center; width:100%; background:none; border:none; color:white; cursor:pointer; padding: 0; margin-bottom: 15px; text-align: left;">
                        <span class="drawer-link" style="margin-bottom: 0; font-size: 2rem;">L'Événement</span>
                        <svg style="width:16px; height:16px; transition: transform 0.4s; color: var(--accent);"
                             :class="{ 'rotate-180': open }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" x-transition x-cloak class="menu-indent" style="margin-top: 0; padding-left: 20px; gap: 10px;">
                        <div style="font-size: 1.2rem; font-family:'Cormorant Garamond', serif; color: var(--accent); margin-bottom: 5px;">
                            {{ $currentCampaign->name }}
                        </div>
                        
                        <div class="menu-indent" style="margin-top: 0; gap: 8px;">
                            <a href="{{ route('campaigns.show', $currentCampaign->slug) }}" class="drawer-sub-link" style="font-size: 0.95rem; color: var(--accent);">
                                <span>⟡</span> Vue Globale
                            </a>
                            @foreach($currentCampaign->candidates as $c)
                                <a href="{{ route('candidates.show', [$currentCampaign->slug, $c->id]) }}" class="drawer-sub-link" style="font-size: 0.95rem;">
                                    <span>•</span> {{ $c->name }}
                                </a>
                            @endforeach
                        </div>
                        <a href="{{ route('campaigns.index') }}" class="drawer-sub-link" style="margin-top: 10px; font-size: 0.8rem; opacity: 0.6; text-transform: uppercase;">
                            ⟡ Explorer tout
                        </a>
                    </div>
                </div>
            @else
                <!-- Toutes les sessions -->
                <div x-data="{ open: @json(!$user) }" style="margin-bottom: 25px;">
                    <button @click="open = !open"
                        style="display:flex; justify-content:space-between; align-items: center; width:100%; background:none; border:none; color:white; cursor:pointer; padding: 0; margin-bottom: 12px; text-align: left;">
                        <span class="drawer-link" style="margin-bottom: 0; font-size: 2rem;">Événements</span>
                        <svg style="width:14px; height:14px; transition: transform 0.4s; color: var(--accent); opacity: 0.5;"
                             :class="{ 'rotate-180': open }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" x-transition x-cloak class="menu-indent" style="padding-left: 20px; gap: 12px;">
                        <a href="{{ route('campaigns.index') }}" class="drawer-sub-link" style="color: var(--accent); font-weight: 500;">⟡ Tous les scrutins</a>
                        @foreach($allActiveCampaigns as $camp)
                             <a href="{{ route('campaigns.show', $camp->slug) }}" class="drawer-sub-link" style="font-size: 1rem;">• {{ $camp->name }}</a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($user && ($myCreatedCampaigns->isNotEmpty() || $myVotedCampaigns->isNotEmpty()))
                <!-- Mes Participations Privées -->
                <div x-data="{ open: true }" style="margin-bottom: 25px;">
                    <div style="display:flex; justify-content:space-between; align-items: center; width:100%; margin-bottom: 12px;">
                        <a href="{{ route('dashboard') }}" class="drawer-link" style="margin-bottom: 0; font-size: 1.8rem; color: var(--accent); flex-grow: 1;">Mes Accès</a>
                        
                        <button @click="open = !open"
                            style="background:none; border:none; color:var(--accent); cursor:pointer; padding: 5px; opacity: 0.7;"
                            onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.7'">
                            <svg style="width:20px; height:20px; transition: transform 0.4s;"
                                 :class="{ 'rotate-180': open }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>

                    <div x-show="open" class="menu-indent" style="padding-left: 20px; gap: 10px;">
                        @foreach($myCreatedCampaigns as $camp)
                            <a href="{{ route('campaigns.show', $camp->slug) }}" class="drawer-sub-link" style="font-size: 1rem;">
                                 <span style="color: white; font-weight: 600;">[voir]</span> {{ $camp->name }}
                            </a>
                        @endforeach
                        @foreach($myVotedCampaigns as $camp)
                            <a href="{{ route('campaigns.show', $camp->slug) }}" class="drawer-sub-link" style="font-size: 1rem;">
                                 <span style="opacity: 0.5;">[Voté]</span> {{ $camp->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div style="width: 30px; height: 1px; background: rgba(212, 174, 109, 0.3); margin: 15px 0 30px;"></div>

            <!-- SECTION ESPACE MEMBRE -->
            <div class="drawer-section-title">Espace Personnel</div>
            
            @guest
                <a href="{{ route('login') }}" class="drawer-link {{ request()->routeIs('login') ? 'active' : '' }}"
                    style="font-size: 1.8rem;">Se Connecter</a>
                <a href="{{ route('register') }}" class="drawer-link {{ request()->routeIs('register') ? 'active' : '' }}"
                    style="font-size: 1.8rem;">Créer un Compte</a>
            @else
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                        style="display:flex; justify-content:space-between; align-items: center; width:100%; background:none; border:none; color:white; cursor:pointer; padding: 0; margin-bottom: 15px; text-align: left;">
                        <span class="drawer-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" style="margin-bottom: 0; font-size: 1.8rem;">Mon Espace</span>
                        <svg style="width:14px; height:14px; transition: transform 0.4s; color: var(--accent); opacity: 0.5;"
                             :class="{ 'rotate-180': open }"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" x-transition x-cloak class="menu-indent" style="gap: 10px; margin-bottom: 20px;">
                        <a href="{{ route('dashboard') }}" class="drawer-sub-link" style="font-size: 1.1rem;">Tableau de Bord</a>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="drawer-sub-link" style="font-size: 1.1rem; color: var(--accent);">Administration</a>
                        @endif
                        
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>

                        <button type="button"
                            @click="mobileMenu = false; Swal.fire({
                                title: 'Déconnexion',
                                text: 'Voulez-vous vraiment clore cette session d\'exception ?',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#003229',
                                cancelButtonColor: '#d4ae6d',
                                confirmButtonText: 'Oui, me déconnecter',
                                cancelButtonText: 'Rester connecté',
                                background: '#fff8e7',
                                color: '#003229'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    document.getElementById('logout-form').submit();
                                }
                            })"
                            class="drawer-sub-link"
                            style="background: none; border: none; padding: 0; cursor: pointer; text-align: left; font-size: 1.1rem; color: #ff6b6b; opacity: 0.8; margin-top: 10px;"
                            onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'">
                            Déconnexion
                        </button>
                    </div>
                </div>
            @endguest
        </nav>

        <div
            style="margin-top: auto; color: var(--accent); font-size: 0.6rem; letter-spacing: 0.4em; text-transform: uppercase;">
            Voter, soutenez vos proches.
        </div>
    </div>

    <!-- Smart Header -->
    <header :class="{ 'scrolled': scrolled, 'hidden': headerHidden }">
        <div class="container" style="height: 100%;">
            <div class="header-content">
                <div class="nav-side-left">
                    <button @click="mobileMenu = true" class="burger-menu">
                        <div class="burger-lines">
                            <div class="burger-line"
                                :style="{ background: (scrolled || @json(isset($isWelcome))) ? 'white' : 'var(--primary)',
                                    width: '30px' }">
                            </div>
                            <div class="burger-line"
                                :style="{ background: (scrolled || @json(isset($isWelcome))) ? 'white' : 'var(--primary)',
                                    width: '22px' }">
                            </div>
                        </div>
                        <span class="explorer-text desktop-only"
                            :style="{ color: (scrolled || @json(isset($isWelcome))) ? 'white' : 'var(--primary)' }"
                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.3em; font-weight: 600;">Explorer</span>
                    </button>
                </div>

                <a href="/" style="text-decoration: none;">
                    <div class="logo-text"
                        :style="{ color: (scrolled || @json(isset($isWelcome))) ? 'white' : 'var(--primary)' }">
                        VOTE <span style="font-weight: 300; color: var(--accent);">ÉVÉNEMENTIELLE</span>
                    </div>
                </a>

                <div class="nav-side-right">
                    @guest
                        <a href="{{ route('login') }}" class="explorer-text"
                            :style="{ color: (scrolled || @json(isset($isWelcome))) ? 'white' : 'var(--primary)' }"
                            style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; transition: all 0.3s;"
                            onmouseover="this.style.color='var(--accent)'"
                            onmouseout="this.style.color=(scrolled || @json(isset($isWelcome))) ? 'white' : 'var(--primary)'">
                            Se Connecter
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="explorer-text"
                            :style="{ color: (scrolled || @json(isset($isWelcome))) ? 'white' : 'var(--primary)' }"
                            style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; transition: all 0.3s;"
                            onmouseover="this.style.color='var(--accent)'"
                            onmouseout="this.style.color=(scrolled || @json(isset($isWelcome))) ? 'white' : 'var(--primary)'">
                            Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </header>

    <main>
        @yield('welcome-hero')

        <div class="container" style="min-height: 50vh; padding-bottom: 100px; padding-top: 120px;">
            @if (session('success'))
                <div
                    style="padding: 20px; background: white; border-top: 2px solid var(--accent); box-shadow: var(--shadow-soft); color: var(--primary); margin-bottom: 40px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; font-size: 0.8rem;">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Back to Top Button -->
    <div class="back-to-top" :class="{ 'visible': scrolled }" @click="window.scrollTo({ top: 0, behavior: 'smooth' })">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 15l-6-6-6 6" />
        </svg>
    </div>

    <footer>
        <div class="container" style="display: flex; flex-direction: column; align-items: center;">
            <div class="footer-logo">VOTE <span style="font-weight: 300; color: var(--accent);">ÉVÉNEMENTIELLE</span>
            </div>
            <div style="width: 40px; height: 1px; background: rgba(255,255,255,0.1); margin: 30px 0;"></div>
            <div
                style="font-size: 0.6rem; font-weight: 600; letter-spacing: 0.4em; text-transform: uppercase; opacity: 0.5; text-align: center;">
                © 2026 VOTE ÉVÉNEMENTIELLE • TOUS DROITS RÉSERVÉS.
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>

</html>
