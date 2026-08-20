<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\PembayaranModel;
use App\Models\PengeluaranModel;
use App\Models\PengaturanIuranModel;
use App\Models\WargaModel;

class Export extends BaseController
{
    public function strukBayar($pembayaranId)
    {
        $auth   = $this->getAuthData();
        $userId = $this->session->get('user_id');

        $db = \Config\Database::connect();
        $row = $db->table('pembayaran')
            ->select('pembayaran.*, warga.nama as warga_nama, warga.no_rumah, warga.alamat, warga.no_hp')
            ->join('warga', 'warga.id = pembayaran.warga_id')
            ->where('pembayaran.id', $pembayaranId)
            ->where('warga.user_id', $userId)
            ->get()
            ->getRowArray();

        if (!$row) {
            return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $html = '
        <style>
            body { font-family: sans-serif; font-size: 12px; color: #333; }
            .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px; }
            .header h2 { margin: 0; font-size: 18px; }
            .header p { margin: 2px 0; font-size: 11px; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            td { padding: 4px 0; vertical-align: top; }
            .label { width: 140px; font-weight: bold; }
            .footer { margin-top: 30px; border-top: 1px dashed #999; padding-top: 10px; font-size: 10px; text-align: center; color: #999; }
            .status { display: inline-block; padding: 3px 10px; border-radius: 4px; color: white; font-weight: bold; }
            .status-lunas { background: #28a745; }
            .status-tertunda { background: #ffc107; color: #333; }
            .status-ditolak { background: #dc3545; }
        </style>

        <div class="header">
            <h2>BUKTI PEMBAYARAN IURAN RT</h2>
            <p>RT 001 / RW 002</p>
            <p>Kelurahan Contoh, Kecamatan Contoh</p>
        </div>

        <table>
            <tr><td class="label">No. Pembayaran</td><td>: ' . $row['id'] . '</td></tr>
            <tr><td class="label">Nama Warga</td><td>: ' . esc($row['warga_nama']) . '</td></tr>
            <tr><td class="label">No. Rumah</td><td>: ' . esc($row['no_rumah']) . '</td></tr>
            <tr><td class="label">Alamat</td><td>: ' . esc($row['alamat'] ?? '-') . '</td></tr>
            <tr><td class="label">No. HP</td><td>: ' . esc($row['no_hp'] ?? '-') . '</td></tr>
            <tr><td class="label">Periode</td><td>: ' . date('F Y', strtotime($row['periode'] . '-01')) . '</td></tr>
            <tr><td class="label">Nominal Iuran</td><td>: Rp ' . number_format($row['nominal'], 0, ',', '.') . '</td></tr>
            <tr><td class="label">Tanggal Bayar</td><td>: ' . date('d/m/Y', strtotime($row['tanggal_bayar'])) . '</td></tr>
            <tr><td class="label">Metode Bayar</td><td>: ' . ucfirst($row['metode']) . '</td></tr>
            <tr><td class="label">Status</td><td>: <span class="status status-' . $row['status'] . '">' . strtoupper($row['status']) . '</span></td></tr>
            ' . (!empty($row['catatan']) ? '<tr><td class="label">Catatan</td><td>: ' . esc($row['catatan']) . '</td></tr>' : '') . '
            ' . (!empty($row['catatan_tolak']) ? '<tr><td class="label">Alasan Ditolak</td><td>: ' . esc($row['catatan_tolak']) . '</td></tr>' : '') . '
        </table>

        <div class="footer">
            <p>Dicetak pada ' . date('d/m/Y H:i') . ' &mdash; Dokumen ini dicetak otomatis oleh Sistem Iuran RT</p>
        </div>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A5', 'portrait');
        $dompdf->render();

        $filename = 'struk-pembayaran-' . $row['id'] . '-' . $row['periode'] . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
    }

    public function laporanPDF()
    {
        $auth   = $this->getAuthData();
        $periode = $this->request->getGet('periode') ?: date('Y-m');

        $iuranModel       = new PengaturanIuranModel();
        $pembayaranModel  = new PembayaranModel();
        $pengeluaranModel = new PengeluaranModel();

        $iuran       = $iuranModel->getByPeriode($periode);
        $nominal     = (float)($iuran['nominal'] ?? 50000);
        $jmlBayar    = $pembayaranModel->countPaidByPeriode($periode);
        $totalMasuk  = $pembayaranModel->getTotalByPeriode($periode);
        $pengeluaran = $pengeluaranModel->getByPeriode($periode);
        $totalKeluar = $pengeluaranModel->getTotalByPeriode($periode);
        $saldo       = $totalMasuk - $totalKeluar;

        $pengeluaranRows = '';
        foreach ($pengeluaran as $p) {
            $pengeluaranRows .= '<tr><td>' . esc($p['keterangan']) . '</td><td style="text-align:right">Rp ' . number_format($p['jumlah'], 0, ',', '.') . '</td></tr>';
        }
        if (empty($pengeluaran)) {
            $pengeluaranRows = '<tr><td colspan="2" style="text-align:center;color:#999">Belum ada pengeluaran</td></tr>';
        }

        $html = '
        <style>
            body { font-family: sans-serif; font-size: 12px; color: #333; }
            .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px; }
            .header h2 { margin: 0; font-size: 18px; }
            .header p { margin: 2px 0; font-size: 11px; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th, td { border: 1px solid #ccc; padding: 6px 10px; }
            th { background: #f4f4f4; }
            .text-right { text-align: right; }
            .footer { margin-top: 30px; border-top: 1px dashed #999; padding-top: 10px; font-size: 10px; text-align: center; color: #999; }
        </style>

        <div class="header">
            <h2>LAPORAN KAS RT</h2>
            <p>Periode: ' . date('F Y', strtotime($periode . '-01')) . '</p>
            <p>RT 001 / RW 002</p>
        </div>

        <table>
            <thead><tr><th colspan="2">PEMASUKAN</th></tr></thead>
            <tbody>
                <tr><td>Iuran warga @ Rp ' . number_format($nominal, 0, ',', '.') . ' (' . $jmlBayar . ' warga sudah lunas)</td><td class="text-right">Rp ' . number_format($totalMasuk, 0, ',', '.') . '</td></tr>
            </tbody>
            <thead><tr><th colspan="2">PENGELUARAN</th></tr></thead>
            <tbody>' . $pengeluaranRows . '</tbody>
            <tfoot>
                <tr><th>SALDO AKHIR</th><th class="text-right">Rp ' . number_format($saldo, 0, ',', '.') . '</th></tr>
            </tfoot>
        </table>

        <div class="footer">
            <p>Dicetak pada ' . date('d/m/Y H:i') . ' &mdash; Sistem Iuran RT</p>
        </div>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'laporan-kas-rt-' . $periode . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
    }

    public function riwayatExcel()
    {
        $userId = $this->session->get('user_id');
        $auth   = $this->getAuthData();

        $pembayaranModel = new PembayaranModel();
        $riwayat = $pembayaranModel->getHistoryByUserId($userId);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Riwayat Pembayaran Iuran RT');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->setCellValue('A2', 'Nama: ' . $auth['nama']);
        $sheet->mergeCells('A2:F2');

        $headers = ['Periode', 'Nominal', 'Tanggal Bayar', 'Metode', 'Status', 'Keterangan'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '4', $h);
            $col++;
        }

        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => 'solid', 'fgColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => 'center'],
        ];
        $sheet->getStyle('A4:F4')->applyFromArray($headerStyle);

        $row = 5;
        foreach ($riwayat as $r) {
            $sheet->setCellValue('A' . $row, date('F Y', strtotime($r['periode'] . '-01')));
            $sheet->setCellValue('B' . $row, $r['nominal']);
            $sheet->setCellValue('C' . $row, ($r['status'] === 'ditolak') ? '-' : date('d/m/Y', strtotime($r['tanggal_bayar'])));
            $sheet->setCellValue('D' . $row, ucfirst($r['metode']));
            $sheet->setCellValue('E' . $row, ucfirst($r['status']));
            $sheet->setCellValue('F' . $row, ($r['status'] === 'ditolak' && !empty($r['catatan_tolak'])) ? $r['catatan_tolak'] : '-');

            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $row++;
        }

        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(16);
        $sheet->getColumnDimension('D')->setWidth(14);
        $sheet->getColumnDimension('E')->setWidth(14);
        $sheet->getColumnDimension('F')->setWidth(30);

        $writer = new Xlsx($spreadsheet);
        $filename = 'riwayat-pembayaran-' . $auth['nama'] . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function dataWargaExcel()
    {
        $auth = $this->getAuthData();

        $wargaModel  = new WargaModel();
        $userModel   = new \App\Models\UserModel();
        $daftarWarga = $wargaModel->orderBy('no_rumah', 'ASC')->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Data Warga RT');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $headers = ['No.', 'Nama', 'No. Rumah', 'Alamat', 'No. HP', 'Akun Login'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '3', $h);
            $col++;
        }

        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => 'solid', 'fgColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => 'center'],
        ];
        $sheet->getStyle('A3:F3')->applyFromArray($headerStyle);

        $row = 4;
        $no = 1;
        foreach ($daftarWarga as $w) {
            $akun = '-';
            if (!empty($w['user_id'])) {
                $user = $userModel->find($w['user_id']);
                if ($user) {
                    $akun = $user['email'] . ' (' . ucfirst($user['role']) . ')';
                }
            }
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $w['nama']);
            $sheet->setCellValue('C' . $row, $w['no_rumah']);
            $sheet->setCellValue('D' . $row, $w['alamat'] ?? '-');
            $sheet->setCellValue('E' . $row, $w['no_hp'] ?? '-');
            $sheet->setCellValue('F' . $row, $akun);
            $row++;
        }

        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(30);

        $writer = new Xlsx($spreadsheet);
        $filename = 'data-warga-rt.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function laporanExcel()
    {
        $auth   = $this->getAuthData();
        $periode = $this->request->getGet('periode') ?: date('Y-m');

        $iuranModel       = new PengaturanIuranModel();
        $pembayaranModel  = new PembayaranModel();
        $pengeluaranModel = new PengeluaranModel();

        $iuran       = $iuranModel->getByPeriode($periode);
        $nominal     = (float)($iuran['nominal'] ?? 50000);
        $jmlBayar    = $pembayaranModel->countPaidByPeriode($periode);
        $totalMasuk  = $pembayaranModel->getTotalByPeriode($periode);
        $pengeluaran = $pengeluaranModel->getByPeriode($periode);
        $totalKeluar = $pengeluaranModel->getTotalByPeriode($periode);
        $saldo       = $totalMasuk - $totalKeluar;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Laporan Kas RT - ' . date('F Y', strtotime($periode . '-01')));
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->setCellValue('A3', 'PEMASUKAN');
        $sheet->getStyle('A3')->getFont()->setBold(true);
        $sheet->setCellValue('A4', 'Iuran warga @ Rp ' . number_format($nominal, 0, ',', '.') . ' (' . $jmlBayar . ' warga sudah lunas)');
        $sheet->setCellValue('C4', $totalMasuk);
        $sheet->getStyle('C4')->getNumberFormat()->setFormatCode('#,##0');

        $sheet->setCellValue('A6', 'PENGELUARAN');
        $sheet->getStyle('A6')->getFont()->setBold(true);
        $row = 7;
        foreach ($pengeluaran as $p) {
            $sheet->setCellValue('A' . $row, $p['keterangan']);
            $sheet->setCellValue('C' . $row, $p['jumlah']);
            $sheet->getStyle('C' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $row++;
        }

        $row += 1;
        $sheet->setCellValue('A' . $row, 'SALDO AKHIR');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $sheet->setCellValue('C' . $row, $saldo);
        $sheet->getStyle('C' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('A' . $row . ':C' . $row)->getFont()->setBold(true);

        $sheet->getColumnDimension('A')->setWidth(45);
        $sheet->getColumnDimension('B')->setWidth(5);
        $sheet->getColumnDimension('C')->setWidth(22);

        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan-kas-rt-' . $periode . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function pembayaranExcel()
    {
        $auth = $this->getAuthData();
        $year = $this->request->getGet('year') ?: date('Y');

        $wargaModel      = new WargaModel();
        $pembayaranModel = new PembayaranModel();

        $semuaWarga = $wargaModel->where('status', 'aktif')->orderBy('no_rumah', 'ASC')->findAll();

        $bulanan = [];
        for ($m = 1; $m <= 12; $m++) {
            $periode = $year . '-' . str_pad($m, 2, '0', STR_PAD_LEFT);
            $pembayaran = $pembayaranModel->where('periode', $periode)->findAll();
            $map = [];
            foreach ($pembayaran as $pb) {
                $map[$pb['warga_id']] = $pb;
            }
            $bulanan[$m] = $map;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Monitoring Pembayaran Iuran Tahun ' . $year);
        $sheet->mergeCells('A1:O1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Nama Warga');
        $sheet->setCellValue('C3', 'No. Rumah');

        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $col = 'D';
        for ($m = 1; $m <= 12; $m++) {
            $sheet->setCellValue($col . '3', $monthNames[$m - 1]);
            $col++;
        }
        $sheet->setCellValue($col . '3', 'Total');
        $totalCol = $col;

        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => 'solid', 'fgColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => 'center', 'wrapText' => true],
        ];
        $sheet->getStyle('A3:' . $totalCol . '3')->applyFromArray($headerStyle);

        $row = 4;
        $no = 1;
        $statusLabels = ['lunas' => 'L', 'tertunda' => 'T', 'ditolak' => 'D'];

        foreach ($semuaWarga as $w) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $w['nama']);
            $sheet->setCellValue('C' . $row, $w['no_rumah']);

            $col = 'D';
            $totalBayar = 0;
            for ($m = 1; $m <= 12; $m++) {
                $pb = $bulanan[$m][$w['id']] ?? null;
                if ($pb) {
                    $label = $statusLabels[$pb['status']] ?? '-';
                    $sheet->setCellValue($col . $row, $label . ' ' . number_format($pb['nominal'], 0, ',', '.'));
                    if ($pb['status'] === 'lunas') {
                        $totalBayar += $pb['nominal'];
                    }
                } else {
                    $sheet->setCellValue($col . $row, '-');
                }
                $col++;
            }

            $sheet->setCellValue($col . $row, $totalBayar);
            $sheet->getStyle($col . $row)->getNumberFormat()->setFormatCode('#,##0');

            $row++;
        }

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(12);
        for ($m = 1; $m <= 12; $m++) {
            $sheet->getColumnDimension(chr(64 + 2 + $m))->setWidth(16);
        }
        $sheet->getColumnDimension($totalCol)->setWidth(16);

        $row++;
        $sheet->setCellValue('A' . $row, 'Keterangan:');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        $sheet->setCellValue('A' . $row, 'L = Lunas, T = Tertunda, D = Ditolak');
        $row++;
        $sheet->setCellValue('A' . $row, 'Angka nominal = nominal yang dibayarkan');

        $writer = new Xlsx($spreadsheet);
        $filename = 'monitoring-pembayaran-' . $year . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
