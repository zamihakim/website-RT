<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Laporan Kas RT &mdash; <?= esc(date('d/m/Y', strtotime($periode . '-01'))) ?></h3>
        <div class="card-tools">
          <?php
            $prev = date('Y-m', strtotime($periode . '-01 -1 month'));
            $next = date('Y-m', strtotime($periode . '-01 +1 month'));
          ?>
          <div class="btn-group btn-group-sm">
            <a href="?periode=<?= $prev ?>" class="btn btn-outline-secondary"><i class="fas fa-chevron-left"></i></a>
            <span class="btn btn-secondary disabled"><?= date('F Y', strtotime($periode . '-01')) ?></span>
            <?php if ($periode < date('Y-m')): ?>
              <a href="?periode=<?= $next ?>" class="btn btn-outline-secondary"><i class="fas fa-chevron-right"></i></a>
            <?php endif; ?>
          </div>
          <a href="<?= base_url('laporan/cetak?periode=' . $periode) ?>" class="btn btn-danger btn-sm ml-2" title="Export PDF"><i class="fas fa-file-pdf mr-1"></i> PDF</a>
          <a href="<?= base_url('laporan/export?periode=' . $periode) ?>" class="btn btn-success btn-sm" title="Export Excel"><i class="fas fa-file-excel mr-1"></i> Excel</a>
        </div>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th colspan="2">Pemasukan</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Iuran warga @ <?= 'Rp ' . number_format($nominal, 0, ',', '.') ?> (<?= $jml_bayar ?> warga sudah lunas)</td>
              <td class="text-right"><?= 'Rp ' . number_format($total_masuk, 0, ',', '.') ?></td>
            </tr>
          </tbody>
          <thead>
            <tr>
              <th colspan="2">Pengeluaran</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pengeluaran as $p): ?>
            <tr>
              <td><?= esc($p['keterangan']) ?></td>
              <td class="text-right"><?= 'Rp ' . number_format($p['jumlah'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($pengeluaran)): ?>
            <tr><td colspan="2" class="text-center text-muted">Belum ada pengeluaran</td></tr>
            <?php endif; ?>
          </tbody>
          <tfoot>
            <tr class="bg-light">
              <th>Saldo Akhir</th>
              <th class="text-right"><?= 'Rp ' . number_format($saldo, 0, ',', '.') ?></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
