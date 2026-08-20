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
        <h3 class="card-title">Pengeluaran RT &mdash; <?= esc(date('d/m/Y', strtotime($periode . '-01'))) ?></h3>
        <div class="card-tools">
          <div class="btn-group btn-group-sm mr-2">
            <?php
              $prev = date('Y-m', strtotime($periode . '-01 -1 month'));
              $next = date('Y-m', strtotime($periode . '-01 +1 month'));
            ?>
            <a href="?periode=<?= $prev ?>" class="btn btn-outline-secondary"><i class="fas fa-chevron-left"></i></a>
            <span class="btn btn-secondary disabled"><?= date('F Y', strtotime($periode . '-01')) ?></span>
            <?php if ($periode < date('Y-m')): ?>
              <a href="?periode=<?= $next ?>" class="btn btn-outline-secondary"><i class="fas fa-chevron-right"></i></a>
            <?php endif; ?>
          </div>
          <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus"></i> Tambah Pengeluaran</button>
        </div>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Tanggal</th>
              <th>Kategori</th>
              <th>Keterangan</th>
              <th>Jumlah</th>
              <th>Bukti</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $badgeMap = ['Kas' => 'bg-info', 'Sosial' => 'bg-success', 'Konsumsi' => 'bg-warning'];
            foreach ($pengeluaran as $i => $p): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= esc($p['tanggal']) ?></td>
              <td><span class="badge <?= $badgeMap[$p['kategori_nama']] ?? 'bg-secondary' ?>"><?= esc($p['kategori_nama']) ?></span></td>
              <td><?= esc($p['keterangan']) ?></td>
              <td><?= 'Rp ' . number_format($p['jumlah'], 0, ',', '.') ?></td>
              <td>
                <?php
                $fotos = [];
                if (!empty($p['foto_bukti'])) {
                    $decoded = json_decode($p['foto_bukti'], true);
                    $fotos = is_array($decoded) ? $decoded : [$p['foto_bukti']];
                }
                ?>
                <?php if (!empty($fotos)): ?>
                  <?php foreach ($fotos as $f): ?>
                    <a href="<?= base_url('uploads/' . $f) ?>" target="_blank" class="btn btn-sm btn-outline-info mr-1"><i class="fas fa-image"></i></a>
                  <?php endforeach; ?>
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
              <td>
                <a href="<?= base_url('pengeluaran/edit/' . $p['id']) ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-edit"></i></a>
                <form method="post" action="<?= base_url('pengeluaran/hapus/' . $p['id']) ?>" style="display:inline" onsubmit="return confirm('Yakin hapus pengeluaran ini?')">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($pengeluaran)): ?>
            <tr><td colspan="7" class="text-center text-muted">Belum ada pengeluaran bulan ini</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Pengeluaran -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" action="<?= base_url('pengeluaran/tambah') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"><i class="fas fa-plus-circle mr-1"></i> Tambah Pengeluaran</h5>
          <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Kategori</label>
            <select name="kategori_id" id="kategoriSelect" class="form-control" required>
              <option value="">-- Pilih Kategori --</option>
              <?php foreach ($kategori_list as $k): ?>
                <option value="<?= $k['id'] ?>"><?= esc($k['nama']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
          </div>
          <div class="form-group">
            <label>Jumlah (Rp)</label>
            <input type="number" name="jumlah" class="form-control" min="0" placeholder="Contoh: 500000" required>
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <input type="text" name="keterangan" class="form-control" placeholder="Keterangan pengeluaran" required>
          </div>
          <div class="form-group" id="fotoGroup">
            <label>Bukti Foto <span class="text-danger" id="fotoWajib" style="display:none;">*</span></label>
            <input type="file" name="foto_bukti[]" id="fotoInput" class="form-control-file" accept="image/jpeg,image/png" multiple>
            <small class="form-text text-muted">Bisa pilih beberapa foto sekaligus (Ctrl+Klik). Format: JPG/PNG, Maks 2MB/file. <strong id="fotoInfo" style="display:none;">Wajib untuk kategori Sosial.</strong></small>
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

<?php $this->section('scripts') ?>
<script>
$('#kategoriSelect').on('change', function() {
  var text = $(this).find(':selected').text();
  var isSosial = text.toLowerCase() === 'sosial';
  $('#fotoWajib').toggle(isSosial);
  $('#fotoInfo').toggle(isSosial);
  if (isSosial) {
    $('#fotoInput').attr('required', true);
  } else {
    $('#fotoInput').removeAttr('required');
  }
});
</script>
<?php $this->endSection() ?>
<?= $this->endSection() ?>
