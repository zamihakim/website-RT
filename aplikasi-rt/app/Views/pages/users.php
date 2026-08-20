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
        <h3 class="card-title">Daftar Akun Users</h3>
        <div class="card-tools">
          <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus"></i> Tambah Akun</button>
        </div>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Role</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $i => $u): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= esc($u['nama']) ?></td>
              <td><?= esc($u['email']) ?></td>
              <td>
                <?php if ($u['role'] === 'pengurus'): ?>
                  <span class="badge badge-primary">Pengurus</span>
                <?php else: ?>
                  <span class="badge badge-info">Warga</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($u['aktif']): ?>
                  <span class="badge badge-success">Aktif</span>
                <?php else: ?>
                  <span class="badge badge-danger">Nonaktif</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="<?= base_url('users/edit/' . $u['id']) ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                <form method="post" action="<?= base_url('users/hapus/' . $u['id']) ?>" style="display:inline" onsubmit="return confirm('Yakin hapus akun ini?')">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($users)): ?>
            <tr><td colspan="6" class="text-center text-muted">Belum ada akun</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: Tambah Akun -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" action="<?= base_url('users/tambah') ?>">
        <?= csrf_field() ?>
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"><i class="fas fa-user-plus mr-1"></i> Tambah Akun</h5>
          <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" placeholder="Nama lengkap" required>
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
          </div>
          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
          </div>
          <div class="form-group">
            <label>Role</label>
            <select name="role" class="form-control" required>
              <option value="warga">Warga</option>
              <option value="pengurus">Pengurus</option>
            </select>
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
