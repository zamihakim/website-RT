<?= $this->extend('layout/layout') ?>

<?= $this->section('content') ?>
<?php if (session('success')): ?>
  <div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-check-circle mr-1"></i> <?= session('success') ?></div>
<?php endif; ?>
<?php if (session('error')): ?>
  <div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><i class="fas fa-exclamation-circle mr-1"></i> <?= session('error') ?></div>
<?php endif; ?>
<?php if (!empty($ditolak_list)): ?>
  <div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h5><i class="fas fa-exclamation-triangle mr-1"></i> Ada Pembayaran Ditolak</h5>
    <p class="mb-1">Pembayaran Anda pada periode berikut ditolak oleh pengurus RT:</p>
    <ul class="mb-1">
      <?php foreach ($ditolak_list as $d): ?>
        <li><strong><?= esc(date('d/m/Y', strtotime($d['periode'] . '-01'))) ?></strong> — <?= esc($d['catatan_tolak'] ?? 'Tidak ada alasan') ?></li>
      <?php endforeach; ?>
    </ul>
    <small>Mohon periksa kembali dan lakukan pembayaran ulang.</small>
  </div>
<?php endif; ?>

<div class="row">
  <div class="col-lg-12">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-file-invoice mr-1"></i> Tagihan Iuran Saya</h3>
      </div>
      <div class="card-body p-0">
        <?php if (empty($tagihan_list)): ?>
          <div class="text-center text-muted p-4">Belum ada data tagihan</div>
        <?php else: ?>
        <table class="table table-hover table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Periode</th>
              <th>Nominal</th>
              <th>Tanggal Bayar</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $bulanPertamaBelumBayar = null;
            foreach (array_reverse($tagihan_list) as $cek) {
                if ($cek['status'] !== 'lunas' && $cek['status'] !== 'tertunda' && $cek['status'] !== 'ditolak') {
                    $bulanPertamaBelumBayar = $cek['periode'];
                    break;
                }
                if ($cek['status'] === 'ditolak' && !$bulanPertamaBelumBayar) {
                    $bulanPertamaBelumBayar = $cek['periode'];
                    break;
                }
            }
            ?>
            <?php $no = 1; foreach ($tagihan_list as $t): ?>
            <?php $lunas = ($t['status'] === 'lunas'); ?>
            <?php $tertunda = ($t['status'] === 'tertunda'); ?>
            <?php $ditolak = ($t['status'] === 'ditolak'); ?>
            <?php $showBayar = ((!$lunas && !$tertunda && !$ditolak) && $t['periode'] === $bulanPertamaBelumBayar) || ($ditolak && $t['periode'] === $bulanPertamaBelumBayar); ?>
            <tr class="<?= $tertunda ? 'table-warning' : ($ditolak ? 'table-danger' : (!$lunas ? 'table-danger' : '')) ?>">
              <td><?= $no++ ?></td>
              <td><?= esc(date('d/m/Y', strtotime($t['periode'] . '-01'))) ?></td>
              <td><?= 'Rp ' . number_format($t['nominal'] ?? $t['iuran_nominal'] ?? 0, 0, ',', '.') ?></td>
              <td><?= $lunas ? esc(date('d/m/Y', strtotime($t['tanggal_bayar']))) : '-' ?></td>
              <td>
                <?php if ($lunas): ?>
                  <span class="badge badge-success">Lunas</span>
                <?php elseif ($tertunda): ?>
                  <span class="badge badge-warning">Menunggu Persetujuan</span>
                <?php elseif ($ditolak): ?>
                  <span class="badge badge-danger" data-toggle="tooltip" title="<?= esc($t['catatan_tolak'] ?? 'Tidak ada alasan') ?>">Ditolak</span>
                <?php else: ?>
                  <span class="badge badge-danger">Belum Bayar</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($lunas): ?>
                  -
                <?php elseif ($tertunda): ?>
                  <span class="text-warning"><small><i class="fas fa-clock"></i> Menunggu persetujuan pengurus</small></span>
                <?php elseif ($ditolak): ?>
                  <div>
                    <small class="text-danger d-block mb-1"><i class="fas fa-exclamation-triangle"></i> <?= esc($t['catatan_tolak'] ?? 'Tidak ada alasan') ?></small>
                    <?php if ($showBayar): ?>
                      <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalBayar"
                        data-periode="<?= esc($t['periode']) ?>"
                        data-nominal="<?= 'Rp ' . number_format($t['nominal'] ?? $t['iuran_nominal'] ?? 0, 0, ',', '.') ?>"
                        data-nominal-raw="<?= $t['nominal'] ?? $t['iuran_nominal'] ?? 0 ?>">
                        <i class="fas fa-redo"></i> Bayar Ulang
                      </button>
                    <?php endif; ?>
                  </div>
                <?php elseif ($showBayar): ?>
                  <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalBayar"
                    data-periode="<?= esc($t['periode']) ?>"
                    data-nominal="<?= 'Rp ' . number_format($t['nominal'] ?? $t['iuran_nominal'] ?? 0, 0, ',', '.') ?>"
                    data-nominal-raw="<?= $t['nominal'] ?? $t['iuran_nominal'] ?? 0 ?>">
                    <i class="fas fa-money-bill-wave"></i> Bayar
                  </button>
                <?php else: ?>
                  <span class="text-muted"><small>Lunasi tagihan sebelumnya dulu</small></span>
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

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Informasi</h3>
      </div>
      <div class="card-body">
        <div class="callout callout-warning">
          <h5><i class="fas fa-info-circle"></i> Pengingat</h5>
          <p>
            Iuran bulan berjalan sebesar <strong><?= 'Rp ' . number_format($nominal, 0, ',', '.') ?></strong>
            <?php if ($keterangan): ?>
              dengan rincian: <?= esc($keterangan) ?>.
            <?php endif; ?>
            Pembayaran dilakukan paling lambat tanggal 10 setiap bulan kepada pengurus RT.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: Bayar Tagihan -->
<div class="modal fade" id="modalBayar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="post" action="<?= base_url('warga/bayar') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title"><i class="fas fa-money-bill-wave mr-1"></i> Bayar Iuran</h5>
          <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Periode</label>
            <input type="text" name="periode" id="bayarPeriode" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Nominal</label>
            <input type="text" id="bayarNominal" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Metode Pembayaran</label>
            <select name="metode" class="form-control" id="bayarMetode" required>
              <option value="">-- Pilih Metode --</option>
              <option value="tunai">Tunai</option>
              <option value="transfer">Transfer</option>
            </select>
          </div>
          <div id="infoTransfer" style="display:none;">
            <div class="callout callout-info mb-3">
              <h6><i class="fas fa-university mr-1"></i> Rekening Tujuan</h6>
              <p class="mb-1"><strong>Bank BRI</strong></p>
              <p class="mb-1">No. Rek: <strong>1234-5678-9012</strong></p>
              <p class="mb-1">Atas Nama: <strong>H. Slamet Riyadi (Ketua RT)</strong></p>
              <p class="mb-0"><small>Masukkan nominal sesuai tagihan: <strong id="infoNominal"></strong></small></p>
            </div>
            <div class="text-center mb-3">
              <img id="qrCode" src="" alt="QR Code" style="max-width:180px; border:1px solid #ddd; padding:5px; border-radius:8px;">
              <p class="text-muted mt-1"><small>Pindai untuk bayar</small></p>
            </div>
          </div>
          <div class="form-group">
            <label>Tanggal Bayar</label>
            <input type="date" name="tanggal_bayar" class="form-control" value="<?= date('Y-m-d') ?>" readonly>
            <small class="form-text text-muted">Tanggal otomatis hari ini.</small>
          </div>
          <div class="form-group" id="buktiGroup" style="display:none;">
            <label>Bukti Transfer <span class="text-danger">*</span></label>
            <input type="file" name="bukti" id="buktiInput" class="form-control-file" accept="image/jpeg,image/png" required>
            <small class="form-text text-muted">Wajib upload bukti transfer. Format: JPG/PNG, Maks 2MB.</small>
          </div>
          <div class="form-group">
            <label>Catatan (Opsional)</label>
            <input type="text" name="catatan" class="form-control" placeholder="Catatan pembayaran...">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success"><i class="fas fa-check mr-1"></i> Bayar Sekarang</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $this->section('scripts') ?>
<script>
$('[data-toggle="tooltip"]').tooltip();
$('#modalBayar').on('show.bs.modal', function(e) {
  var btn = $(e.relatedTarget);
  var periode = btn.data('periode');
  var nominal = btn.data('nominal');
  var nominalRaw = btn.data('nominal-raw');
  $(this).find('#bayarPeriode').val(periode);
  $(this).find('#bayarNominal').val(nominal);
  $(this).find('#infoNominal').text(nominal);
  
  var periodeLabel = periode.replace('-', '/');
  var qrText = 'IURAN RT|' + nominalRaw + '|' + periodeLabel;
  var qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(qrText);
  $(this).find('#qrCode').attr('src', qrUrl);
  
  $('#bayarMetode').val('').trigger('change');
});

$('#bayarMetode').on('change', function() {
  if ($(this).val() === 'transfer') {
    $('#buktiGroup').show();
    $('#infoTransfer').show();
    $('#buktiInput').attr('required', true);
  } else {
    $('#buktiGroup').hide();
    $('#infoTransfer').hide();
    $('#buktiInput').removeAttr('required');
  }
});

$('#modalBayar').on('hidden.bs.modal', function() {
  $('#bayarMetode').val('').trigger('change');
});
</script>
<?php $this->endSection() ?>
<?= $this->endSection() ?>
