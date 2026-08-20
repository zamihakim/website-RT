<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($title ?? 'Login') ?> | Aplikasi Iuran RT</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/adminlte.min.css') ?>">
  <style>
    body.login-page {
      background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
      min-height: 100vh;
    }
    .login-box { width: 900px; max-width: 95%; }
    .role-card {
      background: rgba(255,255,255,0.95);
      border-radius: 12px;
      padding: 30px 20px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s ease;
      border: 3px solid transparent;
      height: 200px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    .role-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
      border-color: #007bff;
    }
    .role-card.selected {
      border-color: #28a745;
      background: #fff;
      box-shadow: 0 5px 20px rgba(40,167,69,0.3);
    }
    .role-card .role-icon {
      font-size: 48px;
      margin-bottom: 12px;
    }
    .role-card.pengurus .role-icon { color: #e74c3c; }
    .role-card.warga .role-icon { color: #3498db; }
    .role-card h3 { margin: 0 0 5px; font-size: 18px; color: #333; }
    .role-card p { margin: 0; font-size: 13px; color: #888; }
    .login-card {
      background: rgba(255,255,255,0.97);
      border-radius: 12px;
      overflow: hidden;
    }
    .login-card-body { padding: 30px; }
    .login-card-body .form-control {
      border-radius: 8px;
      padding: 10px 15px;
    }
    .btn-login {
      background: linear-gradient(135deg, #007bff, #0056b3);
      border: none;
      border-radius: 8px;
      padding: 10px;
      font-size: 16px;
      font-weight: 600;
    }
    .btn-login:hover { background: linear-gradient(135deg, #0056b3, #004094); }
    .back-btn {
      cursor: pointer;
      color: #666;
      font-size: 14px;
      margin-bottom: 15px;
      display: inline-block;
    }
    .back-btn:hover { color: #333; }
    .brand-link-login {
      display: block;
      text-align: center;
      margin-bottom: 30px;
    }
    .brand-link-login img { width: 60px; height: 60px; opacity: 0.9; }
    .brand-link-login h2 {
      color: #fff;
      margin: 10px 0 0;
      font-weight: 300;
      font-size: 24px;
    }
    .alert { border-radius: 8px; font-size: 14px; }
    .role-badge {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      margin-bottom: 10px;
    }
    .role-badge.pengurus { background: #fdecea; color: #e74c3c; }
    .role-badge.warga { background: #e8f4fd; color: #3498db; }
  </style>
</head>
<body class="hold-transition login-page">

<div class="login-box">
  <!-- Logo -->
  <div class="brand-link-login">
    <img src="<?= base_url('assets/img/AdminLTELogo.png') ?>" alt="Logo">
    <h2>Aplikasi Iuran RT</h2>
  </div>

  <!-- STEP 1: Pilih Role -->
  <div id="step-role" class="row" <?= (session('errors') || old('email')) ? 'style="display:none;"' : '' ?>>
    <div class="col-md-6 mb-3">
      <div class="role-card pengurus" onclick="selectRole('pengurus')">
        <div class="role-icon"><i class="fas fa-user-shield"></i></div>
        <h3>Pengurus RT</h3>
        <p>Kelola data warga, iuran, dan laporan</p>
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <div class="role-card warga" onclick="selectRole('warga')">
        <div class="role-icon"><i class="fas fa-home"></i></div>
        <h3>Warga</h3>
        <p>Lihat tagihan dan riwayat pembayaran</p>
      </div>
    </div>
  </div>

  <!-- STEP 2: Form Login -->
  <div id="step-login" class="login-card" <?= (session('errors') || old('email')) ? '' : 'style="display:none;"' ?>>
    <div class="login-card-body">
      <a class="back-btn" onclick="backToRole()"><i class="fas fa-arrow-left"></i> Pilih Role Lain</a>

      <div id="selected-role-badge"></div>

      <?php if (session('errors')): ?>
        <div class="alert alert-danger">
          <i class="fas fa-exclamation-circle mr-1"></i> <?= session('errors') ?>
        </div>
      <?php endif; ?>

      <form method="post" action="<?= base_url('login/proses') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="role" id="input-role" value="<?= esc($role ?? old('role')) ?>">

        <div class="form-group">
          <label for="email"><i class="fas fa-envelope mr-1"></i> Email</label>
          <input type="email" name="email" id="email" class="form-control"
                 placeholder="Masukkan email" value="<?= esc(old('email')) ?>" required autofocus>
        </div>

        <div class="form-group">
          <label for="password"><i class="fas fa-lock mr-1"></i> Password</label>
          <div class="input-group">
            <input type="password" name="password" id="password" class="form-control"
                   placeholder="Masukkan password" required>
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                <i class="fas fa-eye" id="toggle-icon"></i>
              </button>
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-login mt-3">
          <i class="fas fa-sign-in-alt mr-1"></i> Masuk
        </button>
      </form>
    </div>
  </div>
</div>

<script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
<script>
  var roleLabels = {
    'pengurus': '<span class="role-badge pengurus"><i class="fas fa-user-shield mr-1"></i> Pengurus RT</span>',
    'warga': '<span class="role-badge warga"><i class="fas fa-home mr-1"></i> Warga</span>'
  };

  function selectRole(role) {
    $('#input-role').val(role);
    $('#selected-role-badge').html(roleLabels[role]);
    $('#step-role').fadeOut(200, function() {
      $('#step-login').fadeIn(200);
    });
  }

  function backToRole() {
    $('#step-login').fadeOut(200, function() {
      $('#step-role').fadeIn(200);
    });
  }

  function togglePassword() {
    var inp = $('#password');
    var icon = $('#toggle-icon');
    if (inp.attr('type') === 'password') {
      inp.attr('type', 'text');
      icon.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
      inp.attr('type', 'password');
      icon.removeClass('fa-eye-slash').addClass('fa-eye');
    }
  }

  // If there are errors or old input, jump straight to login form
  $(function() {
    var role = '<?= esc($role ?? old('role')) ?>';
    if (role) {
      selectRole(role);
    }
  });
</script>

</body>
</html>
