<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>
<?php if ($success): ?>
  <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-check-circle mr-1"></i> <?= $success ?></div>
<?php endif; ?>
<?php if ($error): ?>
  <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-circle mr-1"></i> <?= $error ?></div>
<?php endif; ?>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Data Warga RT</h3>
        <div class="card-tools">
          <a href="<?= base_url('warga/export') ?>" class="btn btn-success btn-sm" title="Export Excel"><i class="fas fa-file-excel mr-1"></i> Export</a>
          <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus"></i> Tambah Warga</button>
        </div>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama Warga</th>
              <th>No. Rumah</th>
              <th>Alamat</th>
              <th>No. HP</th>
              <th>Akun Login</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($daftar_warga as $i => $w): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= esc($w['nama']) ?></td>
              <td><?= esc($w['no_rumah']) ?></td>
              <td><?= esc($w['alamat'] ?? '-') ?></td>
              <td><?= esc($w['no_hp'] ?? '-') ?></td>
              <td>
                <?php if (!empty($w['user_id'])): ?>
                  <span class="badge badge-primary" title="<?= esc($w['user_email'] ?? '') ?>">
                    <i class="fas fa-check-circle"></i> <?= esc($w['user_role'] ?? 'warga') ?>
                  </span>
                <?php else: ?>
                  <span class="badge badge-secondary">Tidak ada</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($w['status'] === 'aktif'): ?>
                  <span class="badge badge-success">Aktif</span>
                <?php else: ?>
                  <span class="badge badge-warning">Nonaktif</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="<?= base_url('warga/edit/' . $w['id']) ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                <form method="post" action="<?= base_url('warga/hapus/' . $w['id']) ?>" style="display:inline" onsubmit="return confirm('Yakin hapus data warga ini?')">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($daftar_warga)): ?>
            <tr><td colspan="8" class="text-center text-muted">Belum ada data warga</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Warga -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form method="post" action="<?= base_url('warga/tambah') ?>">
        <?= csrf_field() ?>
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"><i class="fas fa-user-plus mr-1"></i> Tambah Warga Baru</h5>
          <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" placeholder="Nama warga" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>No. Rumah <span class="text-danger">*</span></label>
                <input type="text" name="no_rumah" class="form-control" placeholder="Contoh: 01" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" class="form-control" placeholder="Alamat lengkap">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>No. HP</label>
                <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx">
              </div>
            </div>
          </div>
          <hr>
          <p class="text-muted"><small><i class="fas fa-info-circle"></i> Buat akun login untuk warga (opsional). Jika tidak diisi, warga tidak bisa login.</small></p>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Email (untuk login)</label>
                <input type="email" name="email" class="form-control" placeholder="email@contoh.com">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Wajib isi jika membuat akun">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Role Akun</label>
                <select name="role" class="form-control">
                  <option value="warga">Warga</option>
                  <option value="pengurus">Pengurus</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
