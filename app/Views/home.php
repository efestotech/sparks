<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPARKS | <?= lang('Sparks.hephaestus_forge') ?></title>
    <!-- Favicon SVG -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⚡</text></svg>">
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Inter:wght@300;400;600&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <style>
        :root {
            --hephaestus-gold: #c5a021;
            --terracotta: #b3542d;
            --marble: #fcfaf7;
            --stone: #1a1c1e;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--stone);
            color: var(--marble);
            overflow-x: hidden;
        }

        .hero-section {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1518709268805-4e9042af9f23?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 150px;
            background: linear-gradient(transparent, var(--stone));
        }

        .hero-content h1 {
            font-family: 'Cinzel', serif;
            font-size: 5rem;
            font-weight: 900;
            letter-spacing: 15px;
            color: var(--hephaestus-gold);
            text-transform: uppercase;
            margin-bottom: 0;
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from { text-shadow: 0 0 10px rgba(197, 160, 33, 0.5); }
            to { text-shadow: 0 0 30px rgba(197, 160, 33, 0.8), 0 0 10px var(--terracotta); }
        }

        .hero-content p {
            font-size: 1.5rem;
            letter-spacing: 2px;
            color: #ccc;
            margin-bottom: 2rem;
        }

        .btn-forge {
            font-family: 'Cinzel', serif;
            background-color: var(--terracotta);
            color: white;
            padding: 1rem 3rem;
            font-size: 1.2rem;
            font-weight: 700;
            border: none;
            border-radius: 0;
            text-transform: uppercase;
            letter-spacing: 3px;
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }

        .btn-forge:hover {
            background-color: var(--hephaestus-gold);
            color: var(--stone);
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(197, 160, 33, 0.4);
        }

        .feature-grid {
            padding: 100px 20px;
            max-width: 1200px;
            margin: auto;
        }

        .feature-card {
            background-color: rgba(255,255,255,0.05);
            border-top: 3px solid var(--hephaestus-gold);
            padding: 40px;
            text-align: center;
            height: 100%;
            transition: transform 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            background-color: rgba(255,255,255,0.08);
        }

        .feature-icon {
            font-size: 3rem;
            color: var(--hephaestus-gold);
            margin-bottom: 20px;
        }

        .feature-card h3 {
            font-family: 'Cinzel', serif;
            margin-bottom: 15px;
        }

        .greek-pattern {
            height: 40px;
            background: repeating-linear-gradient(90deg, var(--hephaestus-gold) 0, var(--hephaestus-gold) 10px, transparent 10px, transparent 20px);
            opacity: 0.3;
            margin: 50px 0;
        }

        footer {
            padding: 50px 0;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: #777;
        }
        
        .lang-switcher-home {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .lang-switcher-home a {
            color: var(--marble);
            margin-left: 15px;
            font-family: 'Cinzel', serif;
            font-weight: 700;
            opacity: 0.6;
            transition: 0.3s;
        }
        .lang-switcher-home a:hover, .lang-switcher-home a.active {
            opacity: 1;
            color: var(--hephaestus-gold);
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="lang-switcher-home">
        <a href="?lang=it" class="<?= service('request')->getLocale() == 'it' ? 'active' : '' ?>">IT</a>
        <a href="?lang=en" class="<?= service('request')->getLocale() == 'en' ? 'active' : '' ?>">EN</a>
    </div>

    <section class="hero-section">
        <div class="hero-content">
            <p><?= lang('Sparks.home_hero_title') ?></p>
            <h1>SPARKS</h1>
            <p class="mt-3"><?= lang('Sparks.home_hero_subtitle') ?></p>
            <a href="<?= base_url('admin') ?>" class="btn btn-forge mt-4"><?= lang('Sparks.home_enter_citadel') ?></a>
        </div>
    </section>

    <div class="container feature-grid">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-bolt"></i></div>
                    <h3><?= lang('Sparks.feature_speed_title') ?></h3>
                    <p><?= lang('Sparks.feature_speed_desc') ?></p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-sync"></i></div>
                    <h3><?= lang('Sparks.feature_rotation_title') ?></h3>
                    <p><?= lang('Sparks.feature_rotation_desc') ?></p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                    <h3><?= lang('Sparks.feature_protection_title') ?></h3>
                    <p><?= lang('Sparks.feature_protection_desc') ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="greek-pattern"></div>

    <div class="container text-center py-5">
        <h2 class="font-family-cinzel mb-4" style="font-family: 'Cinzel', serif;"><?= lang('Sparks.home_crafted_in') ?></h2>
        <h4 class="text-terracotta" style="color: var(--terracotta); font-family: 'Cinzel', serif;">Efesto Tech di Marco Spinelli</h4>
        <p class="text-muted mt-3"><?= lang('Sparks.home_crafted_desc') ?></p>
    </div>

    <footer>
        <p>&copy; <?= date('Y') ?> Sparks - <?= lang('Sparks.home_masterpiece') ?></p>
        <p class="small"><?= lang('Sparks.home_titans_disclaimer') ?></p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
