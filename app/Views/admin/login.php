<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= lang('Sparks.login_title') ?> | SPARKS</title>

  <!-- Favicon SVG -->
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>⚡</text></svg>">

  <!-- Google Font: Inter -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <style>
    :root {
        --olympus-blue: #1e3a8a; /* Deep blue from sky */
        --olympus-gold: #d4a017; /* Gold from temples/borders */
        --olympus-cream: #fdf6e3; /* Cream color for readability */
        --stone: #24292e;
    }
    body { 
        font-family: 'Inter', sans-serif; 
        background-color: var(--stone);
        background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('<?= base_url('assets/img/olympus_bg.png') ?>');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    .login-box { width: 450px; }
    .card { 
        border-radius: 8px; 
        border: none; 
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.4);
        background-color: rgba(255, 255, 255, 0.95);
        border-top: 6px solid var(--olympus-gold);
    }
    .login-logo a {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        color: #fff !important;
        letter-spacing: 3px;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.8);
        text-transform: uppercase;
    }
    .btn-primary { 
        background-color: var(--olympus-blue); 
        border-color: var(--olympus-blue); 
        border-radius: 6px;
        padding: 0.85rem; 
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 6px rgba(30, 58, 138, 0.3);
        transition: all 0.3s ease;
    }
    .btn-primary:hover { 
        background-color: #162e70; 
        border-color: #162e70;
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(30, 58, 138, 0.4);
    }
    .input-group-text { 
        background-color: transparent; 
        border-right: none; 
        border-radius: 6px 0 0 6px;
        color: var(--olympus-gold);
    }
    .form-control { 
        background-color: transparent; 
        border-left: none; 
        border-radius: 0 6px 6px 0;
        border-color: #ced4da;
    }
    .form-control:focus {
        border-color: var(--olympus-gold);
        box-shadow: none;
    }
    .login-box-msg {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        color: var(--stone);
        font-size: 1.15rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        border-bottom: 2px solid var(--olympus-gold);
        display: inline-block;
        padding-bottom: 5px;
        margin-bottom: 20px !important;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo mb-4">
    <a href="#"><b>SPARKS</b> <?= lang('Sparks.forge_brand') ?></a>
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
