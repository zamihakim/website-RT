<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Laporan Keuangan RT &mdash; <?= date('F Y', strtotime($periode . '-01')) ?></h3>
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
        </div>
      </div>
      <div class="card-body">

        <!-- Ringkasan -->
        <div class="row mb-4">
          <div class="col-md-4">
            <div class="info-box bg-success">
              <span class="info-box-icon"><i class="fas fa-arrow-down"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Pemasukan</span>
                <span class="info-box-number">Rp <?= number_format($total_masuk, 0, ',', '.') ?></span>
                <small><?= $jml_bayar ?> warga sudah bayar</small>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="info-box bg-danger">
              <span class="info-box-icon"><i class="fas fa-arrow-up"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Total Pengeluaran</span>
                <span class="info-box-number">Rp <?= number_format($total_keluar, 0, ',', '.') ?></span>
                <small><?= count($pengeluaran) ?> transaksi</small>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="info-box <?= $saldo >= 0 ? 'bg-info' : 'bg-warning' ?>">
              <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Saldo</span>
                <span class="info-box-number">Rp <?= number_format($saldo, 0, ',', '.') ?></span>
                <small><?= $saldo >= 0 ? 'Surplus' : 'Defisit' ?></small>
              </div>
            </div>
          </div>
        </div>

        <!-- Detail Pemasukan -->
        <div class="card card-outline card-success">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-arrow-down mr-1"></i> Detail Pemasukan (Iuran Warga)</h3>
          </div>
          <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0">
              <thead class="thead-light">
                <tr>
                  <th style="width:5%">No</th>
                  <th>Keterangan</th>
                  <th style="width:20%" class="text-right">Nominal</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Iuran warga periode <?= date('F Y', strtotime($periode . '-01')) ?> (Rp <?= number_format($nominal, 0, ',', '.') ?> x <?= $jml_bayar ?> warga)</td>
                  <td class="text-right font-weight-bold text-success">Rp <?= number_format($total_masuk, 0, ',', '.') ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Detail Pengeluaran -->
        <div class="card card-outline card-danger mt-3">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-arrow-up mr-1"></i> Detail Pengeluaran</h3>
          </div>
          <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0">
              <thead class="thead-light">
                <tr>
                  <th style="width:5%">No</th>
                  <th>Tanggal</th>
                  <th>Kategori</th>
                  <th>Keterangan</th>
                  <th style="width:20%" class="text-right">Jumlah</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($pengeluaran)): ?>
                  <?php foreach ($pengeluaran as $i => $p): ?>
                  <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= date('d/m/Y', strtotime($p['tanggal'])) ?></td>
                    <td><span class="badge badge-secondary"><?= esc($p['kategori_nama'] ?? '-') ?></span></td>
                    <td><?= esc($p['keterangan']) ?></td>
                    <td class="text-right font-weight-bold text-danger">- Rp <?= number_format($p['jumlah'], 0, ',', '.') ?></td>
                  </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="5" class="text-center text-muted py-3">
                      <i class="fas fa-check-circle text-success mr-1"></i> Belum ada pengeluaran pada periode ini
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
              <?php if (!empty($pengeluaran)): ?>
              <tfoot>
                <tr class="bg-light">
                  <th colspan="4">Total Pengeluaran</th>
                  <th class="text-right text-danger">Rp <?= number_format($total_keluar, 0, ',', '.') ?></th>
                </tr>
              </tfoot>
              <?php endif; ?>
            </table>
          </div>
        </div>

        <!-- Saldo Akhir -->
        <div class="card card-outline card-primary mt-3">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-calculator mr-1"></i> Ringkasan Saldo</h3>
          </div>
          <div class="card-body">
            <table class="table table-bordered mb-0">
              <tbody>
                <tr>
                  <td>Total Pemasukan</td>
                  <td class="text-right font-weight-bold text-success">Rp <?= number_format($total_masuk, 0, ',', '.') ?></td>
                </tr>
                <tr>
                  <td>Total Pengeluaran</td>
                  <td class="text-right font-weight-bold text-danger">- Rp <?= number_format($total_keluar, 0, ',', '.') ?></td>
                </tr>
                <tr class="bg-light">
                  <td><strong>Saldo Akhir</strong></td>
                  <td class="text-right font-weight-bold <?= $saldo >= 0 ? 'text-success' : 'text-danger' ?>">
                    <h5 class="mb-0">Rp <?= number_format($saldo, 0, ',', '.') ?></h5>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
