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
        <h3 class="card-title">Daftar Kategori Pengeluaran</h3>
        <div class="card-tools">
          <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus"></i> Tambah Kategori</button>
        </div>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama Kategori</th>
              <th>Keterangan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $badgeMap = ['Kas' => 'bg-info', 'Sosial' => 'bg-success', 'Konsumsi' => 'bg-warning'];
            foreach ($kategori as $i => $k): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><span class="badge <?= $badgeMap[$k['nama']] ?? 'bg-secondary' ?>"><?= esc($k['nama']) ?></span></td>
              <td><?= esc($k['keterangan'] ?? '-') ?></td>
              <td>
                <a href="<?= base_url('kategori-pengeluaran/edit/' . $k['id']) ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                <form method="post" action="<?= base_url('kategori-pengeluaran/hapus/' . $k['id']) ?>" style="display:inline" onsubmit="return confirm('Yakin hapus kategori ini?')">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($kategori)): ?>
            <tr><td colspan="4" class="text-center text-muted">Belum ada kategori</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: Tambah Kategori -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" action="<?= base_url('kategori-pengeluaran/tambah') ?>">
        <?= csrf_field() ?>
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"><i class="fas fa-plus-circle mr-1"></i> Tambah Kategori</h5>
          <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Kategori</label>
            <select name="nama" id="namaKategori" class="form-control" required>
              <option value="">-- Pilih Kategori --</option>
              <option value="Kas">Kas</option>
              <option value="Sosial">Sosial</option>
              <option value="Konsumsi">Konsumsi</option>
              <option value="Lainnya">Lainnya (ketik manual)</option>
            </select>
          </div>
          <div class="form-group" id="namaLainnya" style="display:none;">
            <label>Nama Kategori Lainnya</label>
            <input type="text" name="nama_manual" class="form-control" placeholder="Masukkan nama kategori">
          </div>
          <div class="form-group">
            <label>Keterangan (Opsional)</label>
            <input type="text" name="keterangan" class="form-control" placeholder="Deskripsi kategori...">
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

<?php $this->section('scripts') ?>
<script>
$('#namaKategori').on('change', function() {
  if ($(this).val() === 'Lainnya') {
    $('#namaLainnya').show();
    $('#namaLainnya input').attr('required', true);
  } else {
    $('#namaLainnya').hide();
    $('#namaLainnya input').removeAttr('required');
  }
});
</script>
<?php $this->endSection() ?>
