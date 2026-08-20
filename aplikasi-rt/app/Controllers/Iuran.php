<?php

namespace App\Controllers;

use App\Models\PengaturanIuranModel;

class Iuran extends BaseController
{
    public function index(): string
    {
        $auth = $this->getAuthData();

        $iuranModel = new PengaturanIuranModel();
        $berjalan   = $iuranModel->getBerjalan();
        $riwayat    = $iuranModel->getRiwayat();

        $data = [
            'title'      => 'Pengaturan Iuran',
            'page_title' => 'Pengaturan Nominal Iuran',
            'active'     => 'iuran',
            'role'       => $auth['role'],
            'nama'       => $auth['nama'],
            'iuran'      => $berjalan,
            'riwayat'    => $riwayat,
        ];

        return view('pages/iuran', $data);
    }

    public function tambah()
    {
        $nominal    = $this->request->getPost('nominal');
        $berlaku    = $this->request->getPost('berlaku_mulai');
        $keterangan = $this->request->getPost('keterangan');

        if (empty($nominal) || empty($berlaku)) {
            return redirect()->back()->with('error', 'Nominal dan tanggal berlaku harus diisi.');
        }

        if ($nominal < 0) {
            return redirect()->back()->with('error', 'Nominal tidak boleh negatif.');
        }

        $dateObj = \DateTime::createFromFormat('d/m/Y', $berlaku);
        if (!$dateObj) {
            return redirect()->back()->with('error', 'Format tanggal salah. Gunakan DD/MM/YYYY.');
        }
        $berlakuDb = $dateObj->format('Y-m-d');

        $iuranModel = new PengaturanIuranModel();
        $iuranModel->insert([
            'nominal'       => $nominal,
            'berlaku_mulai' => $berlakuDb,
            'keterangan'    => $keterangan ?: null,
        ]);

        return redirect()->to('/iuran')->with('success', 'Iuran baru berhasil ditambahkan. Nominal lama tetap tersimpan di riwayat.');
    }
}
