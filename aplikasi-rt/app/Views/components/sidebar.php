<?php $active = $active ?? ''; ?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= base_url('/') ?>" class="brand-link">
    <img src="<?= base_url('assets/img/AdminLTELogo.png') ?>" alt="Logo Iuran RT" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Iuran RT</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?= base_url('assets/img/user2-160x160.jpg') ?>" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?= esc($nama ?? 'Pengurus RT') ?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <!-- Menu untuk semua pengguna -->
        <li class="nav-item">
          <a href="<?= base_url('/') ?>" class="nav-link <?= ($active === 'dashboard') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <?php if (($role ?? 'pengurus') === 'pengurus'): ?>
          <!-- ===== Menu khusus Pengurus ===== -->
          <li class="nav-header">MENU PENGURUS</li>

          <li class="nav-item">
            <a href="<?= base_url('warga') ?>" class="nav-link <?= ($active === 'warga') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>Kelola Warga</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?= base_url('iuran') ?>" class="nav-link <?= ($active === 'iuran') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-cogs"></i>
              <p>Pengaturan Iuran</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?= base_url('pembayaran') ?>" class="nav-link <?= ($active === 'pembayaran') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-money-bill-wave"></i>
              <p>Monitoring Pembayaran</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?= base_url('pembayaran/macet') ?>" class="nav-link <?= ($active === 'macet') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-exclamation-triangle"></i>
              <p>Pembayaran Macet</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?= base_url('pengeluaran') ?>" class="nav-link <?= ($active === 'pengeluaran') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-file-invoice-dollar"></i>
              <p>Pengeluaran RT</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?= base_url('kategori-pengeluaran') ?>" class="nav-link <?= ($active === 'kategori') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-tags"></i>
              <p>Kategori Pengeluaran</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?= base_url('laporan') ?>" class="nav-link <?= ($active === 'laporan') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Laporan Bulanan</p>
            </a>
          </li>

        <?php else: ?>
          <!-- ===== Menu khusus Warga ===== -->
          <li class="nav-header">MENU WARGA</li>

          <li class="nav-item">
            <a href="<?= base_url('warga/tagihan') ?>" class="nav-link <?= ($active === 'tagihan') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-file-invoice"></i>
              <p>Tagihan Saya</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?= base_url('warga/history') ?>" class="nav-link <?= ($active === 'history') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-history"></i>
              <p>Riwayat Pembayaran</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?= base_url('warga/laporan') ?>" class="nav-link <?= ($active === 'laporan') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>Laporan Keuangan</p>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
