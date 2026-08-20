<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'aplikasi_rt';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) { die('Connection failed: ' . $conn->connect_error); }

$check = $conn->query('SELECT COUNT(*) as cnt FROM pembayaran WHERE warga_id = 1');
$row = $check->fetch_assoc();
echo "Pembayaran Budi saat ini: " . $row['cnt'] . "\n";

$conn->query("DELETE FROM pembayaran WHERE warga_id = 1 AND periode = '2026-08'");

$data = [
    [1, 1, '2026-01', 50000, '2026-01-05', 'tunai', 'lunas'],
    [1, 1, '2026-02', 50000, '2026-02-03', 'tunai', 'lunas'],
    [1, 1, '2026-03', 50000, '2026-03-07', 'transfer', 'lunas'],
    [1, 1, '2026-04', 50000, '2026-04-02', 'tunai', 'lunas'],
    [1, 1, '2026-05', 50000, '2026-05-08', 'tunai', 'lunas'],
    [1, 1, '2026-06', 50000, '2026-06-04', 'transfer', 'lunas'],
    [1, 1, '2026-07', 50000, '2026-07-05', 'tunai', 'lunas'],
];

foreach ($data as $d) {
    $warga_id = (int)$d[0];
    $iuran_id = (int)$d[1];
    $periode = $conn->real_escape_string($d[2]);
    $nominal = (int)$d[3];
    $tanggal = $conn->real_escape_string($d[4]);
    $metode = $conn->real_escape_string($d[5]);
    $status = $conn->real_escape_string($d[6]);

    $sql = "INSERT IGNORE INTO pembayaran (warga_id, iuran_id, periode, nominal, tanggal_bayar, metode, status) VALUES ($warga_id, $iuran_id, '$periode', $nominal, '$tanggal', '$metode', '$status')";
    $conn->query($sql);
}

$check2 = $conn->query('SELECT COUNT(*) as cnt FROM pembayaran WHERE warga_id = 1');
$row2 = $check2->fetch_assoc();
echo "Pembayaran Budi setelah diupdate: " . $row2['cnt'] . "\n";

$conn->close();
echo "Selesai! Refresh halaman Tagihan Saya.\n";
