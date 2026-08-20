  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <div class="p-3">
      <h5>Panel Kontrol</h5>
      <p>Konten pengaturan aplikasi.</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-inline">
      Iuran, Kas &amp; Kegiatan Sosial RT
    </div>
    <strong>&copy; <?= date('Y') ?> <a href="<?= base_url('/') ?>">Aplikasi Iuran RT</a>.</strong> Semua hak dilindungi.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/adminlte.min.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
