<?= $this->extend('layout/layout') ?>

<?= $this->section('styles') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3><?= 'Rp ' . number_format($total_kas, 0, ',', '.') ?></h3>
        <p>Total Kas RT</p>
      </div>
      <div class="icon"><i class="fas fa-wallet"></i></div>
      <a href="<?= base_url('laporan') ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3><?= $sudah_bayar ?><sup style="font-size:20px">/<?= $total_warga ?></sup></h3>
        <p>Warga Sudah Bayar</p>
      </div>
      <div class="icon"><i class="fas fa-check-circle"></i></div>
      <a href="<?= base_url('pembayaran') ?>" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3><?= $belum_bayar ?></h3>
        <p>Warga Belum Bayar</p>
      </div>
      <div class="icon"><i class="fas fa-hourglass-half"></i></div>
      <a href="<?= base_url('pembayaran/macet') ?>" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
      <div class="inner">
        <h3><?= 'Rp ' . number_format($pengeluaran_bulan, 0, ',', '.') ?></h3>
        <p>Pengeluaran Bulan Ini</p>
      </div>
      <div class="icon"><i class="fas fa-file-invoice-dollar"></i></div>
      <a href="<?= base_url('pengeluaran') ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Pemasukan vs Pengeluaran (6 Bulan Terakhir)</h3>
      </div>
      <div class="card-body">
        <canvas id="chartKeuangan" height="100"></canvas>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Rekap Pembayaran Iuran per Bulan</h3>
      </div>
      <div class="card-body p-0">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Periode</th>
              <th>Nominal Iuran</th>
              <th>Sudah Bayar</th>
              <th>Total Terkumpul</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rekap as $r): ?>
            <tr>
              <td><?= esc(date('d/m/Y', strtotime($r['periode'] . '-01'))) ?></td>
              <td><?= 'Rp ' . number_format(($r['total'] / $r['jml_bayar']), 0, ',', '.') ?></td>
              <td><span class="badge badge-success"><?= $r['jml_bayar'] ?></span></td>
              <td><?= 'Rp ' . number_format($r['total'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($rekap)): ?>
            <tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Pengeluaran Bulan Ini</h3>
      </div>
      <div class="card-body">
        <ul class="list-group">
          <?php
          $icons = ['Kas' => 'fa-cash-register text-info', 'Sosial' => 'fa-hands-helping text-success', 'Konsumsi' => 'fa-utensils text-warning'];
          foreach ($pengeluaran_kategori as $pk): ?>
          <li class="list-group-item d-flex justify-content-between">
            <span><i class="fas <?= $icons[$pk['kategori']] ?? 'fa-receipt' ?> mr-1"></i> <?= esc($pk['kategori']) ?></span>
            <strong><?= 'Rp ' . number_format($pk['total'], 0, ',', '.') ?></strong>
          </li>
          <?php endforeach; ?>
          <?php if (empty($pengeluaran_kategori)): ?>
          <li class="list-group-item text-center text-muted">Belum ada pengeluaran</li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?php $this->section('scripts') ?>
<script>
new Chart(document.getElementById('chartKeuangan'), {
  type: 'bar',
  data: {
    labels: <?= json_encode($chart_labels) ?>,
    datasets: [
      {
        label: 'Pemasukan',
        data: <?= json_encode($chart_pemasukan) ?>,
        backgroundColor: 'rgba(40, 167, 69, 0.7)',
        borderColor: '#28a745',
        borderWidth: 1,
        borderRadius: 4
      },
      {
        label: 'Pengeluaran',
        data: <?= json_encode($chart_pengeluaran) ?>,
        backgroundColor: 'rgba(220, 53, 69, 0.7)',
        borderColor: '#dc3545',
        borderWidth: 1,
        borderRadius: 4
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'top' },
      tooltip: {
        callbacks: {
          label: function(ctx) {
            return ctx.dataset.label + ': Rp ' + ctx.raw.toLocaleString('id-ID');
          }
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          callback: function(val) { return 'Rp ' + val.toLocaleString('id-ID'); }
        }
      }
    }
  }
});
</script>
<?php $this->endSection() ?>
