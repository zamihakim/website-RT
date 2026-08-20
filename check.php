<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=aplikasi_rt', 'root', '');

echo "=== Cek warga for user_id=3 (Citra) ===" . PHP_EOL;
$r = $pdo->query("SELECT id, user_id, nama FROM warga WHERE user_id = 3");
foreach ($r as $row) echo json_encode($row) . PHP_EOL;

echo PHP_EOL . "=== Cek pembayaran for warga_id=2 (Citra) ===" . PHP_EOL;
$r = $pdo->query("SELECT p.*, pi.nominal as iuran_nominal FROM pembayaran p JOIN warga w ON w.id = p.warga_id JOIN pengaturan_iuran pi ON pi.id = p.iuran_id WHERE w.user_id = 3 ORDER BY p.periode DESC");
$count = 0;
foreach ($r as $row) { echo $row['periode'].' | '.$row['nominal'].' | '.$row['status'].PHP_EOL; $count++; }
echo "Total: $count" . PHP_EOL;

echo PHP_EOL . "=== Cek iuran IDs ===" . PHP_EOL;
$r = $pdo->query("SELECT id, nominal, berlaku_mulai FROM pengaturan_iuran ORDER BY id");
foreach ($r as $row) echo 'id='.$row['id'].' | '.$row['nominal'].' | '.$row['berlaku_mulai'].PHP_EOL;

echo PHP_EOL . "=== Cek pembayaran iuran_ids ===" . PHP_EOL;
$r = $pdo->query("SELECT DISTINCT iuran_id FROM pembayaran ORDER BY iuran_id");
foreach ($r as $row) echo 'iuran_id='.$row['iuran_id'].PHP_EOL;
