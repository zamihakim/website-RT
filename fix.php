<?php
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=aplikasi_rt', 'root', '');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('DELETE FROM pembayaran');

$iuranMap = [
    '2026-01' => 34,
    '2026-02' => 35,
    '2026-03' => 36,
    '2026-04' => 37,
    '2026-05' => 38,
];

$nominalMap = [
    '2026-01' => 50000,
    '2026-02' => 60000,
    '2026-03' => 70000,
    '2026-04' => 80000,
    '2026-05' => 90000,
];

$rows = [];
foreach ($iuranMap as $periode => $iuranId) {
    $nominal = $nominalMap[$periode];
    $day = (int)substr($periode, 5, 2);
    for ($w = 1; $w <= 5; $w++) {
        $bayarDay = str_pad($w + 2, 2, '0', STR_PAD_LEFT);
        $metode = ($w % 2 == 0) ? 'transfer' : 'tunai';
        $rows[] = "($w, $iuranId, '$periode', $nominal, '$periode-$bayarDay', '$metode', 'lunas')";
    }
}

$sql = "INSERT INTO pembayaran (warga_id, iuran_id, periode, nominal, tanggal_bayar, metode, status) VALUES " . implode(',', $rows);
$pdo->exec($sql);
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

$count = $pdo->query("SELECT COUNT(*) FROM pembayaran p JOIN warga w ON w.id = p.warga_id WHERE w.user_id = 3")->fetchColumn();
echo "Done! Citra riwayat: $count rows" . PHP_EOL;
