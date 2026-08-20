<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=aplikasi_rt', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('DELETE FROM pembayaran');
$pdo->exec('DELETE FROM pengaturan_iuran');

$pdo->exec("INSERT INTO pengaturan_iuran (nominal, berlaku_mulai, keterangan) VALUES (50000.00,'2026-01-01','Januari'),(60000.00,'2026-02-01','Februari'),(70000.00,'2026-03-01','Maret'),(80000.00,'2026-04-01','April'),(90000.00,'2026-05-01','Mei'),(100000.00,'2026-06-01','Juni'),(110000.00,'2026-07-01','Juli'),(120000.00,'2026-08-01','Agustus')");

$rows = [];
$rows[] = "(1,1,'2026-01',50000,'2026-01-05','tunai','lunas')";
$rows[] = "(2,1,'2026-01',50000,'2026-01-06','transfer','lunas')";
$rows[] = "(3,1,'2026-01',50000,'2026-01-07','tunai','lunas')";
$rows[] = "(4,1,'2026-01',50000,'2026-01-08','transfer','lunas')";
$rows[] = "(5,1,'2026-01',50000,'2026-01-10','tunai','lunas')";
$rows[] = "(1,2,'2026-02',60000,'2026-02-03','tunai','lunas')";
$rows[] = "(2,2,'2026-02',60000,'2026-02-05','transfer','lunas')";
$rows[] = "(3,2,'2026-02',60000,'2026-02-06','tunai','lunas')";
$rows[] = "(4,2,'2026-02',60000,'2026-02-08','transfer','lunas')";
$rows[] = "(5,2,'2026-02',60000,'2026-02-10','tunai','lunas')";
$rows[] = "(1,3,'2026-03',70000,'2026-03-04','transfer','lunas')";
$rows[] = "(2,3,'2026-03',70000,'2026-03-05','tunai','lunas')";
$rows[] = "(3,3,'2026-03',70000,'2026-03-07','tunai','lunas')";
$rows[] = "(4,3,'2026-03',70000,'2026-03-09','transfer','lunas')";
$rows[] = "(5,3,'2026-03',70000,'2026-03-10','tunai','lunas')";
$rows[] = "(1,4,'2026-04',80000,'2026-04-03','tunai','lunas')";
$rows[] = "(2,4,'2026-04',80000,'2026-04-05','transfer','lunas')";
$rows[] = "(3,4,'2026-04',80000,'2026-04-06','tunai','lunas')";
$rows[] = "(4,4,'2026-04',80000,'2026-04-08','tunai','lunas')";
$rows[] = "(5,4,'2026-04',80000,'2026-04-09','transfer','lunas')";
$rows[] = "(1,5,'2026-05',90000,'2026-05-02','transfer','lunas')";
$rows[] = "(2,5,'2026-05',90000,'2026-05-04','tunai','lunas')";
$rows[] = "(3,5,'2026-05',90000,'2026-05-06','transfer','lunas')";
$rows[] = "(4,5,'2026-05',90000,'2026-05-07','tunai','lunas')";
$rows[] = "(5,5,'2026-05',90000,'2026-05-09','tunai','lunas')";

$sql = "INSERT INTO pembayaran (warga_id, iuran_id, periode, nominal, tanggal_bayar, metode, status) VALUES " . implode(',', $rows);
$pdo->exec($sql);

$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$c = $pdo->query('SELECT COUNT(*) FROM pembayaran')->fetchColumn();
$i = $pdo->query('SELECT COUNT(*) FROM pengaturan_iuran')->fetchColumn();
echo "Selesai! Iuran: {$i} rows, Pembayaran: {$c} rows\n";
