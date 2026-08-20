<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>
<?php if (session('error')): ?>
  <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-circle mr-1"></i> <?= session('error') ?></div>
<?php endif; ?>

<div class="row">
  <div class="col-md-6">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-edit mr-1"></i> Edit Akun</h3>
      </div>
      <div class="card-body">
        <form method="post" action="<?= base_url('users/update/' . $user['id']) ?>">
          <?= csrf_field() ?>
          <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" value="<?= esc($user['nama']) ?>" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required>
          </div>
          <div class="form-group">
            <label>Password Baru (Kosongkan jika tidak diubah)</label>
            <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak diubah">
          </div>
          <div class="form-group">
            <label>Role</label>
            <select name="role" class="form-control" required>
              <option value="warga" <?= ($user['role'] === 'warga') ? 'selected' : '' ?>>Warga</option>
              <option value="pengurus" <?= ($user['role'] === 'pengurus') ? 'selected' : '' ?>>Pengurus</option>
            </select>
          </div>
          <div class="form-group">
            <label>Status Aktif</label>
            <select name="aktif" class="form-control" required>
              <option value="1" <?= ($user['aktif']) ? 'selected' : '' ?>>Aktif</option>
              <option value="0" <?= (!$user['aktif']) ? 'selected' : '' ?>>Nonaktif</option>
            </select>
          </div>
          <div class="mt-3">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
            <a href="<?= base_url('users') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
