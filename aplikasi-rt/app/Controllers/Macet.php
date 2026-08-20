<?php

namespace App\Controllers;

use App\Models\WargaModel;
use App\Models\PembayaranModel;
use App\Models\PengaturanIuranModel;

class Macet extends BaseController
{
    public function index(): string
    {
        $auth = $this->getAuthData();
        $periode = $this->request->getGet('periode') ?: date('Y-m');

        $wargaModel      = new WargaModel();
        $pembayaranModel = new PembayaranModel();
        $iuranModel      = new PengaturanIuranModel();

        $semuaWarga  = $wargaModel->where('status', 'aktif')->orderBy('no_rumah', 'ASC')->findAll();
        $sudahBayar  = $pembayaranModel->getWargaPaidPeriode($periode);
        $paidIds     = array_column($sudahBayar, 'warga_id');

        $pending = $pembayaranModel->select('pembayaran.warga_id')
            ->join('warga', 'warga.id = pembayaran.warga_id')
            ->where('pembayaran.periode', $periode)
            ->where('pembayaran.status', 'tertunda')
            ->findAll();
        $pendingIds = array_column($pending, 'warga_id');

        $iuran       = $iuranModel->getByPeriode($periode);
        $nominal     = (float)($iuran['nominal'] ?? 50000);

        $macetList = [];
        $no = 1;
        foreach ($semuaWarga as $w) {
            if (!in_array($w['id'], $paidIds) && !in_array($w['id'], $pendingIds)) {
                $macetList[] = [
                    'no'       => $no++,
                    'nama'     => $w['nama'],
                    'no_rumah' => $w['no_rumah'],
                    'nominal'  => $nominal,
                ];
            }
        }

        $data = [
            'title'      => 'Pembayaran Macet',
            'page_title' => 'Warga Pembayaran Macet',
            'active'     => 'macet',
            'role'       => $auth['role'],
            'nama'       => $auth['nama'],
            'periode'    => $periode,
            'macet_list' => $macetList,
        ];

        return view('pages/macet', $data);
    }
}
