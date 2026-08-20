<?php

namespace App\Controllers;

use App\Models\PengaturanIuranModel;
use App\Models\PembayaranModel;
use App\Models\PengeluaranModel;

class Warga extends BaseController
{
    public function tagihan(): string
    {
        $auth   = $this->getAuthData();
        $userId = $this->session->get('user_id');
        $periodeNow = date('Y-m');

        $iuranModel = new PengaturanIuranModel();
        $iuran = $iuranModel->getBerjalan();
        $nominalNow = (float)($iuran['nominal'] ?? 50000);

        $db = \Config\Database::connect();

        $builder = $db->table('pembayaran');
        $builder->select('pembayaran.periode, pembayaran.nominal, pembayaran.tanggal_bayar, pembayaran.status, pembayaran.bukti, pembayaran.catatan_tolak');
        $builder->join('warga', 'warga.id = pembayaran.warga_id');
        $builder->where('warga.user_id', $userId);
        $builder->orderBy('pembayaran.periode', 'DESC');
        $semuaBayar = $builder->get()->getResultArray();

        $bayarMap = [];
        foreach ($semuaBayar as $pb) {
            $bayarMap[$pb['periode']] = $pb;
        }

        $tagihanList = [];
        $periodeTerlama = $semuaBayar ? $semuaBayar[count($semuaBayar) - 1]['periode'] : $periodeNow;

        if ($periodeTerlama > $periodeNow) {
            $periodeTerlama = $periodeNow;
        }

        $cur = new \DateTime($periodeTerlama . '-01');
        $end = new \DateTime($periodeNow . '-01');
        while ($cur <= $end) {
            $p = $cur->format('Y-m');
            if (isset($bayarMap[$p])) {
                $tagihanList[] = $bayarMap[$p];
            } else {
                $iuranPeriode = $iuranModel->getByPeriode($p);
                $tagihanList[] = [
                    'periode'       => $p,
                    'nominal'       => (float)($iuranPeriode['nominal'] ?? $nominalNow),
                    'tanggal_bayar' => null,
                    'status'        => 'belum',
                ];
            }
            $cur->modify('+1 month');
        }

        $tagihanList = array_reverse($tagihanList);

        $ditolakList = array_filter($tagihanList, fn($t) => ($t['status'] ?? '') === 'ditolak');

        $data = [
            'title'        => 'Tagihan Saya',
            'page_title'   => 'Tagihan Iuran Saya',
            'active'       => 'tagihan',
            'role'         => $auth['role'],
            'nama'         => $auth['nama'],
            'nominal'      => $nominalNow,
            'periode'      => $periodeNow,
            'keterangan'   => $iuran['keterangan'] ?? '',
            'tagihan_list' => $tagihanList,
            'ditolak_list' => array_values($ditolakList),
        ];

        return view('pages/warga_tagihan', $data);
    }

    public function history(): string
    {
        $auth   = $this->getAuthData();
        $userId = $this->session->get('user_id');

        $db = \Config\Database::connect();
        $builder = $db->table('pembayaran');
        $builder->select('pembayaran.*, pengaturan_iuran.nominal as iuran_nominal');
        $builder->join('warga', 'warga.id = pembayaran.warga_id');
        $builder->join('pengaturan_iuran', 'pengaturan_iuran.id = pembayaran.iuran_id');
        $builder->where('warga.user_id', $userId);
        $builder->orderBy('pembayaran.periode', 'DESC');
        $riwayat = $builder->get()->getResultArray();

        $data = [
            'title'      => 'Riwayat Pembayaran',
            'page_title' => 'Riwayat Pembayaran Iuran',
            'active'     => 'history',
            'role'       => $auth['role'],
            'nama'       => $auth['nama'],
            'riwayat'    => $riwayat,
        ];

        return view('pages/warga_history', $data);
    }

    public function bayar()
    {
        $userId     = $this->session->get('user_id');
        $metode     = $this->request->getPost('metode');
        $tanggalBayar = $this->request->getPost('tanggal_bayar');
        $periode    = $this->request->getPost('periode');
        $catatan    = $this->request->getPost('catatan');

        if (empty($metode) || empty($tanggalBayar) || empty($periode)) {
            return redirect()->back()->with('error', 'Metode dan tanggal bayar wajib diisi.');
        }

        $pembayaranModel = new PembayaranModel();
        $wargaId = $pembayaranModel->findWargaIdByUserId($userId);

        if (!$wargaId) {
            return redirect()->back()->with('error', 'Data warga tidak ditemukan.');
        }

        $existing = $pembayaranModel->where('warga_id', $wargaId)->where('periode', $periode)->first();
        if ($existing && $existing['status'] !== 'ditolak') {
            return redirect()->back()->with('error', 'Pembayaran untuk periode ini sudah ada.');
        }

        if ($existing && $existing['status'] === 'ditolak') {
            if (!empty($existing['bukti']) && file_exists(FCPATH . 'uploads/' . $existing['bukti'])) {
                unlink(FCPATH . 'uploads/' . $existing['bukti']);
            }
            $pembayaranModel->delete($existing['id']);
        }

        $allBayar = $pembayaranModel->where('warga_id', $wargaId)->orderBy('periode', 'ASC')->findAll();
        $paidPeriodes = [];
        foreach ($allBayar as $ab) {
            if ($ab['status'] === 'lunas') {
                $paidPeriodes[] = $ab['periode'];
            }
        }
        $cur = new \DateTime($periode . '-01');
        $checkPeriode = $cur->modify('-1 month')->format('Y-m');
        if ($checkPeriode >= '2026-01' && !in_array($checkPeriode, $paidPeriodes)) {
            return redirect()->back()->with('error', 'Lunasi tagihan bulan ' . date('F Y', strtotime($checkPeriode . '-01')) . ' terlebih dahulu.');
        }

        $iuranModel = new PengaturanIuranModel();
        $iuran = $iuranModel->getByPeriode($periode);
        $nominal = (float)($iuran['nominal'] ?? 50000);

        $bukti = $this->request->getFile('bukti');
        $buktiName = null;

        if ($metode === 'transfer') {
            if ($bukti === null || !$bukti->isValid()) {
                return redirect()->back()->with('error', 'Bukti transfer wajib diupload.')->withInput();
            }
        }
        if ($bukti && $bukti->isValid()) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($bukti->getClientMimeType(), $allowedTypes)) {
                return redirect()->back()->with('error', 'File bukti harus gambar (JPG/PNG).')->withInput();
            }
            if ($bukti->getSize() > 2 * 1024 * 1024) {
                return redirect()->back()->with('error', 'Ukuran foto maksimal 2MB.')->withInput();
            }
            $buktiName = $bukti->getRandomName();
            $bukti->move(FCPATH . 'uploads', $buktiName);
        }

        $pembayaranModel->insert([
            'warga_id'      => $wargaId,
            'iuran_id'      => $iuran['id'] ?? 1,
            'periode'       => $periode,
            'nominal'       => $nominal,
            'tanggal_bayar' => $tanggalBayar,
            'metode'        => $metode,
            'bukti'         => $buktiName,
            'catatan'       => $catatan ?: null,
            'status'        => 'tertunda',
        ]);

        return redirect()->to('/warga/tagihan')->with('success', 'Pembayaran berhasil dikirim. Menunggu validasi pengurus RT.');
    }

    public function laporan(): string
    {
        $auth   = $this->getAuthData();
        $periode = $this->request->getGet('periode') ?: date('Y-m');

        $iuranModel       = new PengaturanIuranModel();
        $pembayaranModel  = new PembayaranModel();
        $pengeluaranModel = new PengeluaranModel();

        $iuran       = $iuranModel->getByPeriode($periode);
        $nominal     = (float)($iuran['nominal'] ?? 50000);
        $totalMasuk  = $pembayaranModel->getTotalByPeriode($periode);
        $jmlBayar    = $pembayaranModel->countPaidByPeriode($periode);
        $pengeluaran = $pengeluaranModel->getByPeriode($periode);
        $totalKeluar = $pengeluaranModel->getTotalByPeriode($periode);
        $saldo       = $totalMasuk - $totalKeluar;

        $data = [
            'title'        => 'Laporan Keuangan',
            'page_title'   => 'Laporan Keuangan RT',
            'active'       => 'laporan',
            'role'         => $auth['role'],
            'nama'         => $auth['nama'],
            'periode'      => $periode,
            'nominal'      => $nominal,
            'jml_bayar'    => $jmlBayar,
            'total_masuk'  => $totalMasuk,
            'pengeluaran'  => $pengeluaran,
            'total_keluar' => $totalKeluar,
            'saldo'        => $saldo,
        ];

        return view('pages/warga_laporan', $data);
    }
}
