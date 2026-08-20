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
        <h3 class="card-title"><i class="fas fa-calendar-alt mr-1"></i> Monitoring Pembayaran Tahun <?= $year ?></h3>
        <div class="card-tools">
          <div class="btn-group btn-group-sm mr-2">
            <a href="?year=<?= $year - 1 ?>" class="btn btn-outline-secondary"><i class="fas fa-chevron-left"></i></a>
            <span class="btn btn-secondary disabled"><?= $year ?></span>
            <?php if ($year < date('Y')): ?>
              <a href="?year=<?= $year + 1 ?>" class="btn btn-outline-secondary"><i class="fas fa-chevron-right"></i></a>
            <?php endif; ?>
          </div>
          <a href="<?= base_url('pembayaran/export?year=' . $year) ?>" class="btn btn-success btn-sm" title="Export Excel"><i class="fas fa-file-excel mr-1"></i> Export</a>
          <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalBayar"><i class="fas fa-plus"></i> Catat Pembayaran</button>
        </div>
      </div>
      <div class="card-body table-responsive p-0">
        <table class="table table-bordered table-sm text-center">
          <thead class="thead-dark">
            <tr>
              <th rowspan="2" class="align-middle">#</th>
              <th rowspan="2" class="align-middle" style="text-align:left">Nama Warga</th>
              <th rowspan="2" class="align-middle">Rumah</th>
              <?php for ($m = 1; $m <= 12; $m++): ?>
                <th><?= date('M', mktime(0, 0, 0, $m, 1)) ?></th>
              <?php endfor; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data_list as $d): ?>
            <tr>
              <td><?= $d['no'] ?></td>
              <td style="text-align:left;font-weight:600"><?= esc($d['nama']) ?></td>
              <td><?= esc($d['rumah']) ?></td>
              <?php for ($m = 1; $m <= 12; $m++): ?>
                <?php $pb = $d['bulan'][$m]; ?>
                <td>
                  <?php if ($pb): ?>
                    <?php if ($pb['status'] === 'lunas'): ?>
                      <span class="badge badge-success" title="Lunas - <?= esc($pb['tanggal_bayar']) ?> / <?= ucfirst($pb['metode']) ?>">L</span>
                    <?php elseif ($pb['status'] === 'tertunda'): ?>
                      <div class="btn-group btn-group-xs">
                        <button class="btn btn-warning btn-xs" title="Menunggu - <?= esc($pb['tanggal_bayar']) ?> / <?= ucfirst($pb['metode']) ?>"><i class="fas fa-clock"></i></button>
                      </div>
                      <div class="mt-1" style="font-size:10px">
                        <form method="post" action="<?= base_url('pembayaran/setuju/' . $pb['id']) ?>" style="display:inline" onsubmit="return confirm('Setujui pembayaran ini?')">
                          <?= csrf_field() ?>
                          <button type="submit" class="btn btn-success btn-xs" title="Setujui"><i class="fas fa-check"></i></button>
                        </form>
                        <button class="btn btn-danger btn-xs" title="Tolak" data-toggle="modal" data-target="#modalTolak" data-id="<?= $pb['id'] ?>" data-nama="<?= esc($d['nama']) ?>" data-bulan="<?= date('d/m/Y', mktime(0,0,0,$m,1)) ?>-<?= $year ?>"><i class="fas fa-times"></i></button>
                      </div>
                      <?php if (!empty($pb['bukti'])): ?>
                        <div class="mt-1"><a href="<?= base_url('uploads/' . $pb['bukti']) ?>" target="_blank" class="text-info" title="Lihat Bukti" style="font-size:10px"><i class="fas fa-image"></i></a></div>
                      <?php endif; ?>
                    <?php elseif ($pb['status'] === 'ditolak'): ?>
                      <span class="badge badge-danger" title="Ditolak - <?= esc($pb['catatan_tolak'] ?? '') ?>" style="cursor:pointer">D</span>
                      <?php if (!empty($pb['bukti'])): ?>
                        <div class="mt-1"><a href="<?= base_url('uploads/' . $pb['bukti']) ?>" target="_blank" class="text-info" title="Lihat Bukti" style="font-size:10px"><i class="fas fa-image"></i></a></div>
                      <?php endif; ?>
                    <?php endif; ?>
                  <?php else: ?>
                    <span class="text-muted">&mdash;</span>
                  <?php endif; ?>
                </td>
              <?php endfor; ?>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="card-footer">
        <small>
          <span class="badge badge-success">L</span> = Lunas &nbsp;&nbsp;
          <span class="badge badge-warning"><i class="fas fa-clock"></i></span> = Menunggu Persetujuan &nbsp;&nbsp;
          <span class="badge badge-danger">D</span> = Ditolak &nbsp;&nbsp;
          <a href="#" class="text-success" title="Setujui"><i class="fas fa-check"></i></a> = Setujui &nbsp;&nbsp;
          <a href="#" class="text-danger" title="Tolak"><i class="fas fa-times"></i></a> = Tolak &nbsp;&nbsp;
          <i class="fas fa-image text-info"></i> = Lihat Bukti &nbsp;&nbsp;
          &mdash; = Belum Bayar
        </small>
      </div>
    </div>
  </div>
</div>

<!-- Modal Catat Pembayaran -->
<div class="modal fade" id="modalBayar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" action="<?= base_url('pembayaran/tambah') ?>">
        <?= csrf_field() ?>
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title"><i class="fas fa-money-bill-wave mr-1"></i> Catat Pembayaran</h5>
          <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Warga</label>
            <select name="warga_id" class="form-control" required>
              <option value="">-- Pilih Warga --</option>
              <?php foreach ($data_list as $d): ?>
                <option value="<?= $d['id'] ?>"><?= esc($d['nama']) ?> (No. <?= esc($d['rumah']) ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Periode</label>
            <input type="month" name="periode" class="form-control" value="<?= $year ?>-<?= str_pad(date('m'), 2, '0', STR_PAD_LEFT) ?>" required>
          </div>
          <div class="form-group">
            <label>Tanggal Bayar</label>
            <input type="date" name="tanggal_bayar" class="form-control" value="<?= date('Y-m-d') ?>" required>
          </div>
          <div class="form-group">
            <label>Metode</label>
            <select name="metode" class="form-control" required>
              <option value="tunai">Tunai</option>
              <option value="transfer">Transfer</option>
            </select>
          </div>
          <div class="form-group">
            <label>Catatan (opsional)</label>
            <input type="text" name="catatan" class="form-control" placeholder="Catatan tambahan">
          </div>
          <div class="callout callout-info">
            <small>Nominal iuran: <strong><?= 'Rp ' . number_format($iuran['nominal'] ?? 50000, 0, ',', '.') ?></strong></small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success"><i class="fas fa-check mr-1"></i> Simpan Pembayaran</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Tolak Pembayaran -->
<div class="modal fade" id="modalTolak" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" id="formTolak" action="<?= base_url('pembayaran/tolak/0') ?>">
        <?= csrf_field() ?>
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title"><i class="fas fa-times-circle mr-1"></i> Tolak Pembayaran</h5>
          <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <p>Menolak pembayaran dari <strong id="tolakNama"></strong> periode <strong id="tolakBulan"></strong>.</p>
          <div class="form-group">
            <label>Alasan Penolakan <span class="text-danger">*</span></label>
            <textarea name="catatan_tolak" id="catatanTolak" class="form-control" rows="3" placeholder="Contoh: Bukti transfer tidak valid, nominal tidak sesuai, dll." required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger"><i class="fas fa-times mr-1"></i> Tolak Pembayaran</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $this->section('scripts') ?>
<script>
$('#modalTolak').on('show.bs.modal', function(e) {
  var btn = $(e.relatedTarget);
  var id = btn.data('id');
  var nama = btn.data('nama');
  var bulan = btn.data('bulan');
  $(this).find('#tolakNama').text(nama);
  $(this).find('#tolakBulan').text(bulan);
  $(this).find('#formTolak').attr('action', '<?= base_url('pembayaran/tolak/') ?>' + id);
  $(this).find('#catatanTolak').val('');
});
</script>
<?php $this->endSection() ?>
<?= $this->endSection() ?>
