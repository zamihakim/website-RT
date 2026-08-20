<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>
<?php if (session('error')): ?>
  <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-circle mr-1"></i> <?= session('error') ?></div>
<?php endif; ?>
<?php if (session('success')): ?>
  <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-check-circle mr-1"></i> <?= session('success') ?></div>
<?php endif; ?>

<div class="row">
  <div class="col-md-8">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-1"></i> Edit Pengeluaran</h3>
      </div>
      <div class="card-body">
        <form method="post" action="<?= base_url('pengeluaran/update/' . $pengeluaran['id']) ?>" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <div class="form-group">
            <label>Kategori</label>
            <select name="kategori_id" id="kategoriSelect" class="form-control" required>
              <option value="">-- Pilih Kategori --</option>
              <?php foreach ($kategori_list as $k): ?>
                <option value="<?= $k['id'] ?>" <?= ($k['id'] == $pengeluaran['kategori_id']) ? 'selected' : '' ?>><?= esc($k['nama']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="<?= esc($pengeluaran['tanggal']) ?>" required>
          </div>
          <div class="form-group">
            <label>Jumlah (Rp)</label>
            <input type="number" name="jumlah" class="form-control" min="0" value="<?= esc($pengeluaran['jumlah']) ?>" required>
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <input type="text" name="keterangan" class="form-control" value="<?= esc($pengeluaran['keterangan']) ?>" required>
          </div>

          <div class="form-group" id="fotoGroup">
            <label>Upload Foto Baru (Opsional)</label>
            <input type="file" name="foto_bukti[]" id="fotoInput" class="form-control-file" accept="image/jpeg,image/png" multiple>
            <small class="form-text text-muted">Bisa pilih beberapa file sekaligus (Ctrl+Klik). Format: JPG/PNG, Maks 2MB/file.</small>
          </div>

          <div class="mt-3">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
            <a href="<?= base_url('pengeluaran') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
          </div>
        </form>

        <?php $fotos = $pengeluaran['foto_list'] ?? []; ?>
        <?php if (!empty($fotos)): ?>
        <hr>
        <div class="form-group">
          <label><strong>Bukti Foto Saat Ini</strong></label>
          <div class="row">
            <?php foreach ($fotos as $idx => $f): ?>
              <div class="col-md-4 mb-2">
                <div class="card">
                  <a href="<?= base_url('uploads/' . $f) ?>" target="_blank">
                    <img src="<?= base_url('uploads/' . $f) ?>" class="card-img-top" style="height:150px;object-fit:cover;">
                  </a>
                  <div class="card-body p-1 text-center">
                    <form method="post" action="<?= base_url('pengeluaran/hapus_foto/' . $pengeluaran['id']) ?>" onsubmit="return confirm('Hapus foto ini?')">
                      <?= csrf_field() ?>
                      <input type="hidden" name="idx" value="<?= $idx ?>">
                      <button type="submit" class="btn btn-danger btn-sm btn-block">
                        <i class="fas fa-trash"></i> Hapus
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php $this->section('scripts') ?>
<script>
$('#kategoriSelect').on('change', function() {
  var text = $(this).find(':selected').text();
  var isSosial = text.toLowerCase() === 'sosial';
  if (isSosial) {
    $('#fotoInput').attr('required', true);
  } else {
    $('#fotoInput').removeAttr('required');
  }
});
</script>
<?php $this->endSection() ?>
<?= $this->endSection() ?>
