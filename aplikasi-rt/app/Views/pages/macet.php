<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Warga Belum Bayar &mdash; <?= date('F Y', strtotime($periode . '-01')) ?></h3>
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
      <div class="card-body p-0">
        <table class="table table-hover table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama Warga</th>
              <th>No. Rumah</th>
              <th>Nominal</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($macet_list as $m): ?>
            <tr>
              <td><?= $m['no'] ?></td>
              <td><?= esc($m['nama']) ?></td>
              <td><?= esc($m['no_rumah']) ?></td>
              <td><?= 'Rp ' . number_format($m['nominal'], 0, ',', '.') ?></td>
              <td><span class="badge badge-danger">Belum Bayar</span></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($macet_list)): ?>
            <tr><td colspan="5" class="text-center text-success">Semua warga sudah bayar</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
