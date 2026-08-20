<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-history mr-1"></i> Riwayat Pembayaran Iuran Anda</h3>
        <div class="card-tools">
          <a href="<?= base_url('warga/export-history') ?>" class="btn btn-success btn-sm" title="Download Excel"><i class="fas fa-file-excel mr-1"></i> Export Excel</a>
        </div>
      </div>
      <div class="card-body p-0">
        <?php if (empty($riwayat)): ?>
          <div class="text-center text-muted p-4">Belum ada riwayat pembayaran</div>
        <?php else: ?>
        <table class="table table-hover table-striped">
          <thead>
            <tr>
              <th>Periode</th>
              <th>Nominal</th>
              <th>Tanggal Bayar</th>
              <th>Metode</th>
              <th>Status</th>
              <th>Keterangan</th>
              <th>Bukti</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($riwayat as $r): ?>
            <tr>
              <td><?= esc(date('d/m/Y', strtotime($r['periode'] . '-01'))) ?></td>
              <td><?= 'Rp ' . number_format($r['nominal'], 0, ',', '.') ?></td>
              <td><?= ($r['status'] === 'ditolak') ? '-' : esc(date('d/m/Y', strtotime($r['tanggal_bayar']))) ?></td>
              <td><?= ucfirst(esc($r['metode'])) ?></td>
              <td>
                <?php if ($r['status'] === 'lunas'): ?>
                  <span class="badge badge-success">Lunas</span>
                <?php elseif ($r['status'] === 'tertunda'): ?>
                  <span class="badge badge-warning">Tertunda</span>
                <?php elseif ($r['status'] === 'ditolak'): ?>
                  <span class="badge badge-danger">Ditolak</span>
                <?php else: ?>
                  <span class="badge badge-secondary">-</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($r['status'] === 'ditolak' && !empty($r['catatan_tolak'])): ?>
                  <small class="text-danger"><i class="fas fa-exclamation-triangle"></i> <?= esc($r['catatan_tolak']) ?></small>
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
              <td>
                <?php if (!empty($r['bukti'])): ?>
                  <a href="<?= base_url('uploads/' . $r['bukti']) ?>" target="_blank" class="btn btn-sm btn-outline-info"><i class="fas fa-image"></i> Lihat</a>
                <?php else: ?>
                  -
                <?php endif; ?>
              </td>
              <td>
                <?php if ($r['status'] === 'lunas'): ?>
                  <a href="<?= base_url('warga/cetak-bukti/' . $r['id']) ?>" class="btn btn-sm btn-outline-primary" title="Cetak Struk"><i class="fas fa-print"></i> Struk</a>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
