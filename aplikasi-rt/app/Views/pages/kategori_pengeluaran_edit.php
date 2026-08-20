<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>
<?php if (session('error')): ?>
  <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-circle mr-1"></i> <?= session('error') ?></div>
<?php endif; ?>

<div class="row">
  <div class="col-md-6">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Edit Kategori</h3>
      </div>
      <div class="card-body">
        <form method="post" action="<?= base_url('kategori-pengeluaran/update/' . $kategori['id']) ?>">
          <?= csrf_field() ?>
          <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="nama" class="form-control" value="<?= esc($kategori['nama']) ?>" required>
          </div>
          <div class="form-group">
            <label>Keterangan (Opsional)</label>
            <input type="text" name="keterangan" class="form-control" value="<?= esc($kategori['keterangan'] ?? '') ?>">
          </div>
          <div class="mt-3">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
            <a href="<?= base_url('kategori-pengeluaran') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
