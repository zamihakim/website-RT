<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>
<?php if (session('success')): ?>
  <div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-check-circle mr-1"></i> <?= session('success') ?>
  </div>
<?php endif; ?>
<?php if (session('error')): ?>
  <div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <i class="fas fa-exclamation-circle mr-1"></i> <?= session('error') ?>
  </div>
<?php endif; ?>

<div class="row">
  <div class="col-md-5">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title">Iuran Aktif Saat Ini</h3>
      </div>
      <div class="card-body text-center">
        <span class="display-4"><?= 'Rp ' . number_format($iuran['nominal'] ?? 0, 0, ',', '.') ?></span>
        <p class="text-muted mb-0">Berlaku mulai <?= $iuran['berlaku_mulai'] ? date('d/m/Y', strtotime($iuran['berlaku_mulai'])) : '-' ?></p>
        <?php if (!empty($iuran['keterangan'])): ?>
          <p class="text-muted mt-1"><small><?= esc($iuran['keterangan']) ?></small></p>
        <?php endif; ?>
      </div>
    </div>

    <div class="card card-success card-outline">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus-circle mr-1"></i> Tambah Iuran Baru</h3>
      </div>
      <div class="card-body">
        <div class="callout callout-info mb-3">
          <small><i class="fas fa-info-circle"></i> Iuran baru akan menjadi iuran aktif mulai tanggal yang dipilih. Iuran lama tetap tersimpan di riwayat.</small>
        </div>
        <form method="post" action="<?= base_url('iuran/tambah') ?>">
          <?= csrf_field() ?>
          <div class="form-group">
            <label for="nominal">Nominal Iuran Baru (Rp)</label>
            <input type="number" class="form-control" id="nominal" name="nominal" min="0"
                   placeholder="Contoh: 55000" required>
          </div>
          <div class="form-group">
            <label for="berlaku_mulai">Berlaku Mulai</label>
            <input type="text" class="form-control" id="berlaku_mulai" name="berlaku_mulai"
                   value="<?= date('d/m/Y', strtotime('first day of next month')) ?>"
                   placeholder="DD/MM/YYYY" pattern="\d{2}/\d{2}/\d{4}" required>
            <small class="form-text text-muted">Format: DD/MM/YYYY (contoh: 01/09/2026)</small>
          </div>
          <div class="form-group">
            <label for="keterangan">Keterangan (opsional)</label>
            <input type="text" class="form-control" id="keterangan" name="keterangan"
                   placeholder="Contoh: Kas Rp30.000, Sosial Rp15.000, Konsumsi Rp10.000">
          </div>
          <button type="submit" class="btn btn-success btn-block">
            <i class="fas fa-save mr-1"></i> Simpan Iuran Baru
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-7">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Riwayat Nominal Iuran</h3>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover table-striped">
          <thead>
            <tr>
              <th>Nominal</th>
              <th>Berlaku Mulai</th>
              <th>Keterangan</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($riwayat as $r): ?>
            <?php $isAktif = ($iuran && $r['id'] == $iuran['id']); ?>
            <tr class="<?= $isAktif ? 'table-success' : '' ?>">
              <td>
                <?= 'Rp ' . number_format($r['nominal'], 0, ',', '.') ?>
                <?php if ($isAktif): ?>
                  <span class="badge badge-success ml-1">Aktif</span>
                <?php endif; ?>
              </td>
              <td><?= date('d/m/Y', strtotime($r['berlaku_mulai'])) ?></td>
              <td><?= esc($r['keterangan'] ?? '-') ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($riwayat)): ?>
            <tr><td colspan="3" class="text-center text-muted">Belum ada data</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
