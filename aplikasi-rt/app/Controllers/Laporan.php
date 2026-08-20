<?php

namespace App\Controllers;

use App\Models\WargaModel;
use App\Models\PembayaranModel;
use App\Models\PengaturanIuranModel;
use App\Models\PengeluaranModel;

class Laporan extends BaseController
{
    public function index(): string
    {
        $auth = $this->getAuthData();
        $periode = $this->request->getGet('periode') ?: date('Y-m');

        $wargaModel       = new WargaModel();
        $pembayaranModel  = new PembayaranModel();
        $iuranModel       = new PengaturanIuranModel();
        $pengeluaranModel = new PengeluaranModel();

        $iuran       = $iuranModel->getByPeriode($periode);
        $nominal     = (float)($iuran['nominal'] ?? 50000);
        $sudahBayar  = $pembayaranModel->countPaidByPeriode($periode);
        $totalMasuk  = $pembayaranModel->getTotalByPeriode($periode);
        $pengeluaran = $pengeluaranModel->getByPeriode($periode);
        $totalKeluar = $pengeluaranModel->getTotalByPeriode($periode);
        $saldo       = $totalMasuk - $totalKeluar;

        $data = [
            'title'           => 'Laporan',
            'page_title'      => 'Laporan Kas RT',
            'active'          => 'laporan',
            'role'            => $auth['role'],
            'nama'            => $auth['nama'],
            'periode'         => $periode,
            'nominal'         => $nominal,
            'jml_bayar'       => $sudahBayar,
            'total_masuk'     => $totalMasuk,
            'pengeluaran'     => $pengeluaran,
            'total_keluar'    => $totalKeluar,
            'saldo'           => $saldo,
        ];

        return view('pages/laporan', $data);
    }
}
