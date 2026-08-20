<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($title ?? 'Dashboard') ?> | Aplikasi Iuran RT</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- AdminLTE Theme style -->
  <link rel="stylesheet" href="<?= base_url('assets/css/adminlte.min.css') ?>">
  <?= $this->renderSection('styles') ?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
