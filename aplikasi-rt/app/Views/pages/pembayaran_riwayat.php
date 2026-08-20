<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-history mr-1"></i> Riwayat Pembayaran &mdash; <?= esc($warga['nama']) ?> (No. <?= esc($warga['no_rumah']) ?>)</h3>
        <div class="card-tools">
          <div class="btn-group btn-group-sm">
            <a href="?year=<?= $year - 1 ?>" class="btn btn-outline-secondary"><i class="fas fa-chevron-left"></i></a>
            <span class="btn btn-secondary disabled"><?= $year ?></span>
            <?php if ($year < date('Y')): ?>
              <a href="?year=<?= $year + 1 ?>" class="btn btn-outline-secondary"><i class="fas fa-chevron-right"></i></a>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <?php if (empty($riwayat[$year] ?? null)): ?>
          <div class="text-center text-muted p-4">Belum ada riwayat pembayaran tahun <?= $year ?></div>
        <?php else: ?>
        <table class="table table-hover table-striped">
          <thead>
            <tr>
              <th>Bulan</th>
              <th>Nominal</th>
              <th>Tanggal Bayar</th>
              <th>Metode</th>
              <th>Status</th>
              <th>Keterangan</th>
              <th>Bukti</th>
            </tr>
          </thead>
          <tbody>
            <?php for ($m = 12; $m >= 1; $m--): ?>
              <?php $pb = $riwayat[$year][$m] ?? null; ?>
              <tr>
                <td><?= date('F Y', mktime(0, 0, 0, $m, 1, $year)) ?></td>
                <?php if ($pb): ?>
                  <td><?= 'Rp ' . number_format($pb['nominal'], 0, ',', '.') ?></td>
                  <td>
                    <?php if ($pb['status'] === 'ditolak'): ?>
                      -
                    <?php else: ?>
                      <?= esc(date('d/m/Y', strtotime($pb['tanggal_bayar']))) ?>
                    <?php endif; ?>
                  </td>
                  <td><?= ucfirst(esc($pb['metode'])) ?></td>
                  <td>
                    <?php if ($pb['status'] === 'lunas'): ?>
                      <span class="badge badge-success">Lunas</span>
                    <?php elseif ($pb['status'] === 'tertunda'): ?>
                      <span class="badge badge-warning">Tertunda</span>
                    <?php elseif ($pb['status'] === 'ditolak'): ?>
                      <span class="badge badge-danger">Ditolak</span>
                    <?php else: ?>
                      <span class="badge badge-secondary">-</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($pb['status'] === 'ditolak' && !empty($pb['catatan_tolak'])): ?>
                      <small class="text-danger"><i class="fas fa-exclamation-triangle"></i> <?= esc($pb['catatan_tolak']) ?></small>
                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if (!empty($pb['bukti'])): ?>
                      <a href="<?= base_url('uploads/' . $pb['bukti']) ?>" target="_blank" class="btn btn-sm btn-outline-info"><i class="fas fa-image"></i> Lihat</a>
                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>
                <?php else: ?>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td><span class="text-muted">&mdash;</span></td>
                  <td>-</td>
                  <td>-</td>
                <?php endif; ?>
              </tr>
            <?php endfor; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php $this->section('scripts') ?>
<script>
$('[data-toggle="tooltip"]').tooltip();
</script>
<?php $this->endSection() ?>
<?= $this->endSection() ?>
