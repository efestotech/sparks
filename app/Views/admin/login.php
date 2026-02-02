<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= lang('Sparks.login_title') ?> | SPARKS</title>

  <!-- Favicon SVG -->
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⚡</text></svg>">

  <!-- Google Font: Cinzel & Inter -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Inter:wght@300;400;600;700&swap">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <style>
    :root {
        --hephaestus-gold: #c5a021;
        --terracotta: #b3542d;
        --marble: #fcfaf7;
        --stone: #24292e;
    }
    body { 
        font-family: 'Inter', sans-serif; 
        background-color: var(--stone);
        background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1542156822-6924d1a71ace?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
        background-size: cover;
        background-position: center;
    }
    .login-box { width: 450px; }
    .card { 
        border-radius: 0; 
        border: none; 
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
        background-color: var(--marble);
        border-top: 5px solid var(--hephaestus-gold);
    }
    .login-logo a {
        font-family: 'Cinzel', serif;
        font-weight: 700;
        color: var(--hephaestus-gold) !important;
        letter-spacing: 4px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    .btn-primary { 
        background-color: var(--terracotta); 
        border-color: var(--terracotta); 
        border-radius: 0;
        padding: 0.75rem; 
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    .btn-primary:hover { background-color: #8e4222; }
    .input-group-text { 
        background-color: transparent; 
        border-right: none; 
        border-radius: 0;
        color: var(--hephaestus-gold);
    }
    .form-control { 
        background-color: transparent; 
        border-left: none; 
        border-radius: 0;
        border-color: #ced4da;
    }
    .form-control:focus {
        border-color: var(--hephaestus-gold);
        box-shadow: none;
    }
    .login-box-msg {
        font-family: 'Cinzel', serif;
        font-weight: 700;
        color: var(--stone);
        font-size: 1.2rem;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo mb-4">
    <a href="#"><b>SPARKS</b> <?= lang('Sparks.hephaestus_forge') ?></a>
  </div>
  
  <div class="card">
    <div class="card-body login-card-body p-5">
      <p class="login-box-msg mb-4"><?= lang('Sparks.present_credentials') ?></p>

      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger border-0 small py-2 mb-4"><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>

      <form action="<?= base_url('admin/login') ?>" method="post">
        <label class="small font-weight-bold text-muted text-uppercase"><?= lang('Sparks.guardian_username') ?></label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
          </div>
          <input type="text" name="username" class="form-control" placeholder="<?= lang('Sparks.who_seeks') ?>" required title="<?= lang('Sparks.guardian_username') ?>">
        </div>
        
        <label class="small font-weight-bold text-muted text-uppercase"><?= lang('Sparks.secret_sigil') ?></label>
        <div class="input-group mb-4">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-key"></i></span>
          </div>
          <input type="password" name="password" class="form-control" placeholder="<?= lang('Sparks.word_of_fire') ?>" required title="<?= lang('Sparks.secret_sigil') ?>">
        </div>
        
        <div class="row mt-4">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block"><?= lang('Sparks.ignite_forge') ?></button>
          </div>
        </div>
      </form>
      
      <div class="text-center mt-4">
        <small class="text-muted font-italic">Developed by Efesto Tech di Marco Spinelli</small>
        <div class="mt-2 small">
            <a href="?lang=it" class="mx-1 <?= service('request')->getLocale() == 'it' ? 'font-weight-bold text-warning' : 'text-muted' ?>">IT</a>
            <span class="text-muted">|</span>
            <a href="?lang=en" class="mx-1 <?= service('request')->getLocale() == 'en' ? 'font-weight-bold text-warning' : 'text-muted' ?>">EN</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
