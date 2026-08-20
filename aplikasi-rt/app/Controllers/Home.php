<?php

namespace App\Controllers;

use App\Models\WargaModel;
use App\Models\PembayaranModel;
use App\Models\PengaturanIuranModel;
use App\Models\PengeluaranModel;

class Home extends BaseController
{
    public function index()
    {
        $auth = $this->getAuthData();

        if (($auth['role'] ?? 'pengurus') === 'warga') {
            return redirect()->to('/warga/tagihan');
        }

        $periode = date('Y-m');

        $wargaModel       = new WargaModel();
        $pembayaranModel  = new PembayaranModel();
        $iuranModel       = new PengaturanIuranModel();
        $pengeluaranModel = new PengeluaranModel();

        $iuran       = $iuranModel->getBerjalan();
        $nominal     = (float)($iuran['nominal'] ?? 50000);
        $totalWarga  = $wargaModel->where('status', 'aktif')->countAllResults();
        $sudahBayar  = $pembayaranModel->countPaidByPeriode($periode);
        $belumBayar  = $totalWarga - $sudahBayar;
        $totalMasuk  = $pembayaranModel->getTotalByPeriode($periode);
        $totalKeluar = $pengeluaranModel->getTotalByPeriode($periode);
        $saldo       = $totalMasuk - $totalKeluar;

        $rekap = $pembayaranModel->select("
            pembayaran.periode,
            COUNT(*) as jml_bayar,
            SUM(pembayaran.nominal) as total
        ")->where('pembayaran.status', 'lunas')
          ->groupBy('pembayaran.periode')
          ->orderBy('pembayaran.periode', 'DESC')
          ->limit(3)
          ->findAll();

        $pengeluaranPerKategori = $pengeluaranModel->getTotalByKategoriPeriode($periode);

        $chartLabels = [];
        $chartPemasukan = [];
        $chartPengeluaran = [];
        for ($i = 5; $i >= 0; $i--) {
            $p = date('Y-m', strtotime("-{$i} months"));
            $chartLabels[] = date('M', strtotime($p . '-01'));
            $chartPemasukan[] = $pembayaranModel->getTotalByPeriode($p);
            $chartPengeluaran[] = $pengeluaranModel->getTotalByPeriode($p);
        }

        $data = [
            'title'              => 'Dashboard',
            'page_title'         => 'Dashboard Pengurus RT',
            'active'             => 'dashboard',
            'role'               => $auth['role'],
            'nama'               => $auth['nama'],
            'total_kas'          => $saldo,
            'sudah_bayar'        => $sudahBayar,
            'belum_bayar'        => $belumBayar,
            'total_warga'        => $totalWarga,
            'nominal'            => $nominal,
            'pengeluaran_bulan'  => $totalKeluar,
            'rekap'              => $rekap,
            'pengeluaran_kategori' => $pengeluaranPerKategori,
            'periode'            => $periode,
            'chart_labels'       => $chartLabels,
            'chart_pemasukan'    => $chartPemasukan,
            'chart_pengeluaran'  => $chartPengeluaran,
        ];

        return view('pages/dashboard', $data);
    }
}
