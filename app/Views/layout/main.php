<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'James Webb Studio') ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts: EB Garamond + Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Overrides de cor/fonte apenas — não altera layout Bootstrap -->
    <style>
        :root {
            --mst-gold:      #C5A059;
            --mst-gold-dim:  rgba(197, 160, 89, 0.55);
            --mst-bg:        #000000;
        }

        body {
            background-color: var(--mst-bg);
            font-family: 'Inter', system-ui, sans-serif;
            font-weight: 300;
        }

        /* Títulos: EB Garamond */
        h1, h2, h3, h4, h5, h6, .brand-font {
            font-family: 'EB Garamond', Georgia, serif;
            font-weight: 500;
            letter-spacing: 0.03em;
        }

        /* Dourado no lugar do branco para headings de destaque */
        .text-gold { color: var(--mst-gold) !important; }

        /* Navbar */
        .navbar { background-color: transparent !important; transition: background-color 0.3s ease; }
        .navbar.scrolled { background-color: rgba(0, 0, 0, 0.92) !important; backdrop-filter: blur(10px); }
        .navbar-brand { font-family: 'EB Garamond', serif; font-size: 1.1rem; color: var(--mst-gold) !important; }
        .navbar-brand .nav-sub { font-family: 'Inter', sans-serif; font-size: 0.65rem; font-weight: 400; letter-spacing: 0.18em; text-transform: uppercase; opacity: 0.5; margin-left: 8px; }
        .nav-link { font-family: 'Inter', sans-serif; font-size: 0.72rem; letter-spacing: 0.18em; text-transform: uppercase; }

        /* Botão dourado (override do Bootstrap btn-light) */
        .btn-terroso {
            background-color: var(--mst-gold);
            border-color:     var(--mst-gold);
            color: #000;
            font-family: 'Inter', sans-serif;
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }
        .btn-terroso:hover {
            background-color: #d4b06a;
            border-color:     #d4b06a;
            color: #000;
        }
        .btn-outline-terroso {
            border-color: var(--mst-gold);
            color: var(--mst-gold);
            font-family: 'Inter', sans-serif;
            font-size: 0.72rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }
        .btn-outline-terroso:hover {
            background-color: var(--mst-gold);
            color: #000;
        }

        .hero-link { text-decoration: none; color: inherit; }

        /* Zoom suave no hover da foto de capa */
        a:hover .hero-card-img { transform: scale(1.05); }
    </style>

    <!-- SEO: Meta Tags -->
    <meta name="description" content="<?= esc($metaDescription ?? 'Estúdio fotográfico na Lapa, São Paulo. Ensaios de branding pessoal, retratos profissionais e fotografia autoral. Pacotes personalizados com entrega digital em alta resolução.') ?>">
    <meta name="author" content="James Webb Studio">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= current_url() ?>">

    <!-- SEO: Geo Tags (Local) -->
    <meta name="geo.region" content="BR-SP">
    <meta name="geo.placename" content="Lapa, São Paulo">
    <meta name="geo.position" content="-23.5205;-46.7015">
    <meta name="ICBM" content="-23.5205, -46.7015">

    <!-- Open Graph (Facebook / WhatsApp) -->
    <meta property="og:type" content="website">
    <meta property="og:locale" content="pt_BR">
    <meta property="og:site_name" content="James Webb Studio">
    <meta property="og:title" content="<?= esc($ogTitle ?? $title ?? 'James Webb Studio') ?>">
    <meta property="og:description" content="<?= esc($ogDescription ?? $metaDescription ?? 'Ensaios fotográficos em estúdio na Lapa, São Paulo. Branding pessoal, retratos profissionais e fotografia autoral.') ?>">
    <meta property="og:url" content="<?= current_url() ?>">
    <?php if (!empty($ogImage)): ?>
    <meta property="og:image" content="<?= esc($ogImage) ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <?php endif; ?>

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= esc($ogTitle ?? $title ?? 'James Webb Studio') ?>">
    <meta name="twitter:description" content="<?= esc($ogDescription ?? $metaDescription ?? 'Ensaios fotográficos em estúdio na Lapa, São Paulo.') ?>">

    <!-- Schema.org: LocalBusiness + Photographer -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": ["LocalBusiness", "PhotographAction"],
        "@id": "https://jameswebbstudio.com.br/#business",
        "name": "James Webb Studio",
        "alternateName": "JWS",
        "description": "Estúdio fotográfico na Lapa, São Paulo. Ensaios de branding pessoal, retratos profissionais e fotografia autoral.",
        "url": "https://jameswebbstudio.com.br",
        "telephone": "+5511964322103",
        "email": "contato@jameswebbstudio.com.br",
        "image": "https://jameswebbstudio.com.br/assets/img/jws-logo.svg",
        "priceRange": "$$",
        "currenciesAccepted": "BRL",
        "paymentAccepted": "Pix, Cartão de Crédito, Cartão de Débito",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "Rua Domingos Rodrigues, 242, Sala 31",
            "addressLocality": "São Paulo",
            "addressRegion": "SP",
            "postalCode": "05075-010",
            "addressCountry": "BR",
            "areaServed": {
                "@type": "City",
                "name": "São Paulo"
            }
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": -23.5205,
            "longitude": -46.7015
        },
        "openingHoursSpecification": [
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday"],
                "opens": "09:00",
                "closes": "18:00"
            },
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": "Saturday",
                "opens": "09:00",
                "closes": "14:00"
            }
        ],
        "founder": {
            "@type": "Person",
            "name": "Marcos Vieira dos Santos",
            "jobTitle": "Fotógrafo",
            "knowsAbout": ["Branding Pessoal", "Retrato Profissional", "Fotografia Autoral"]
        },
        "hasOfferCatalog": {
            "@type": "OfferCatalog",
            "name": "Pacotes de Ensaio Fotográfico",
            "itemListElement": [
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "Ensaio Fotográfico Profissional",
                        "description": "Sessão fotográfica em estúdio com entrega digital em alta resolução"
                    }
                }
            ]
        },
        "sameAs": [
            "https://www.instagram.com/jameswebbstudio",
            "https://www.jameswebbstudio.com.br"
        ],
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "5",
            "bestRating": "5",
            "worstRating": "1",
            "ratingCount": "1"
        }
    }
    </script>

    <?= $this->renderSection('styles') ?>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="<?= site_url('/') ?>">
                <img src="<?= base_url('assets/img/jws-logo-horizontal.png') ?>"
                     alt="James Webb Studio"
                     style="height:36px; width:auto;">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navPublic" aria-controls="navPublic" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navPublic">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('investimento') ?>" style="color:rgba(197,160,89,.7);">Investimento</a>
                    </li>
                    <?php if (auth()->loggedIn()): ?>
                        <?php if (auth()->user()->inGroup('admin', 'superadmin', 'developer')): ?>
                            <li class="nav-item">
                                <a class="nav-link text-info fw-bold" href="<?= site_url('admin') ?>">Painel Admin</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link text-gold fw-bold" href="<?= site_url('client/meus-ensaios') ?>"><i class="fas fa-camera me-1"></i> Meus Ensaios</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="btn btn-outline-danger btn-sm px-3" href="<?= site_url('logout') ?>" style="font-size: 0.65rem; letter-spacing: 0.1em; text-transform: uppercase;">Sair</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="btn btn-outline-terroso btn-sm px-4" href="<?= site_url('login') ?>">Entrar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <?= $this->renderSection('content') ?>

    <footer style="background:#000; border-top: 1px solid rgba(197,160,89,0.15); padding: 2rem 0; text-align: center;">
        <p style="margin:0; font-family:'Inter',sans-serif; font-size:0.72rem; font-weight:300; letter-spacing:0.15em; color:rgba(255,255,255,0.3); text-transform:uppercase;">
            &copy; <?= date('Y') ?> James Webb Studio &mdash; Todos os direitos reservados
        </p>
        <p style="margin:0.4rem 0 0; font-family:'Inter',sans-serif; font-size:0.62rem; font-weight:300; letter-spacing:0.08em; color:rgba(197,160,89,0.4);">
            Reprodução proibida sem autorização expressa
        </p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('scroll', function () {
            document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 50);
        }, { passive: true });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
