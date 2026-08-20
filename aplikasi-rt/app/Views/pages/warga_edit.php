<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>
<?php if (session('success')): ?>
  <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-check-circle mr-1"></i> <?= session('success') ?></div>
<?php endif; ?>
<?php if (session('error')): ?>
  <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-circle mr-1"></i> <?= session('error') ?></div>
<?php endif; ?>

<div class="row">
  <div class="col-md-8">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-edit mr-1"></i> Edit Data Warga</h3>
      </div>
      <div class="card-body">
        <form method="post" action="<?= base_url('warga/update/' . $warga['id']) ?>">
          <?= csrf_field() ?>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" value="<?= esc($warga['nama']) ?>" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>No. Rumah <span class="text-danger">*</span></label>
                <input type="text" name="no_rumah" class="form-control" value="<?= esc($warga['no_rumah']) ?>" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" class="form-control" value="<?= esc($warga['alamat'] ?? '') ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>No. HP</label>
                <input type="text" name="no_hp" class="form-control" value="<?= esc($warga['no_hp'] ?? '') ?>">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
              <option value="aktif" <?= $warga['status'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
              <option value="nonaktif" <?= $warga['status'] === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
            </select>
          </div>

          <hr>
          <h5><i class="fas fa-key mr-1"></i> Akun Login</h5>
          <p class="text-muted"><small>
            <?php if ($user): ?>
              Warga ini sudah punya akun login. Ubah data di bawah untuk memperbarui akun.
            <?php else: ?>
              Warga ini belum punya akun login. Isi email + password untuk membuat akun baru.
            <?php endif; ?>
          </small></p>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= esc($user['email'] ?? '') ?>" placeholder="email@contoh.com">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Password <?= $user ? '(Kosongkan jika tidak diubah)' : '' ?></label>
                <input type="password" name="password" class="form-control" placeholder="<?= $user ? 'Biarkan kosong jika tidak diubah' : 'Wajib isi jika membuat akun baru' ?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control">
                  <option value="warga" <?= ($user['role'] ?? 'warga') === 'warga' ? 'selected' : '' ?>>Warga</option>
                  <option value="pengurus" <?= ($user['role'] ?? '') === 'pengurus' ? 'selected' : '' ?>>Pengurus</option>
                </select>
              </div>
            </div>
          </div>

          <div class="mt-3">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
            <a href="<?= base_url('warga') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
