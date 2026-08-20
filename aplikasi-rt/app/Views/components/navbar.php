<!-- Navbar / Top Bar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="<?= base_url('/') ?>" class="nav-link">Beranda</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="<?= base_url('/') ?>" class="nav-link">Informasi</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
    <!-- User account dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-user-circle"></i>
        <span class="d-none d-sm-inline ml-1"><?= esc($nama ?? 'Pengguna') ?></span>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="#" class="dropdown-item">
          <i class="fas fa-user mr-2"></i> Profil Saya
        </a>
        <div class="dropdown-divider"></div>
        <form method="post" action="<?= base_url('logout') ?>" style="display:inline">
          <?= csrf_field() ?>
          <button type="submit" class="dropdown-item text-danger">
            <i class="fas fa-sign-out-alt mr-2"></i> Keluar
          </button>
        </form>
      </div>
    </li>
  </ul>
</nav>
<!-- /.navbar -->
