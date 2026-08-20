<?php
// Layout utama aplikasi — menggabungkan semua komponen (header, navbar, sidebar, footer)
// Halaman konten menggunakan $this->extend('layout/layout') dan $this->section('content')
$role   = $role ?? 'pengurus';
$active = $active ?? '';
$nama   = $nama ?? ($role === 'warga' ? 'Warga RT' : 'Pengurus RT');
?>

<?= $this->include('components/header') ?>
<?= $this->include('components/navbar') ?>
<?= $this->include('components/sidebar') ?>

<!-- Content Wrapper. Berisi judul halaman dan isi konten -->
<div class="content-wrapper">
  <!-- Content Header (breadcrumb / judul halaman) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"><?= esc($page_title ?? $title ?? 'Dashboard') ?></h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Beranda</a></li>
            <li class="breadcrumb-item active"><?= esc($page_title ?? $title ?? 'Dashboard') ?></li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <?= $this->renderSection('content') ?>
    </div>
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?= $this->include('components/footer') ?>
