<?php

namespace App\Controllers;

use App\Models\KategoriPengeluaranModel;

class KategoriPengeluaran extends BaseController
{
    public function index(): string
    {
        $auth = $this->getAuthData();

        $kategoriModel = new KategoriPengeluaranModel();
        $daftar = $kategoriModel->findAll();

        $data = [
            'title'      => 'Kategori Pengeluaran',
            'page_title' => 'Kategori Pengeluaran',
            'active'     => 'kategori',
            'role'       => $auth['role'],
            'nama'       => $auth['nama'],
            'kategori'   => $daftar,
            'success'    => session('success'),
            'error'      => session('error'),
        ];

        return view('pages/kategori_pengeluaran', $data);
    }

    public function tambah()
    {
        $nama       = $this->request->getPost('nama');
        $namaManual = $this->request->getPost('nama_manual');
        $keterangan = $this->request->getPost('keterangan');

        if ($nama === 'Lainnya' && !empty($namaManual)) {
            $nama = $namaManual;
        }

        if (empty($nama) || $nama === 'Lainnya') {
            return redirect()->back()->with('error', 'Nama kategori wajib diisi.');
        }

        $kategoriModel = new KategoriPengeluaranModel();
        $existing = $kategoriModel->where('nama', $nama)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Nama kategori sudah ada.');
        }

        $kategoriModel->insert([
            'nama'       => $nama,
            'keterangan' => $keterangan ?: null,
        ]);

        return redirect()->to('/kategori-pengeluaran')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kategoriModel = new KategoriPengeluaranModel();
        $kategori = $kategoriModel->find($id);

        if (!$kategori) {
            return redirect()->to('/kategori-pengeluaran')->with('error', 'Kategori tidak ditemukan.');
        }

        $auth = $this->getAuthData();

        $data = [
            'title'      => 'Edit Kategori',
            'page_title' => 'Edit Kategori Pengeluaran',
            'active'     => 'kategori',
            'role'       => $auth['role'],
            'nama'       => $auth['nama'],
            'kategori'   => $kategori,
        ];

        return view('pages/kategori_pengeluaran_edit', $data);
    }

    public function update($id)
    {
        $kategoriModel = new KategoriPengeluaranModel();
        $kategori = $kategoriModel->find($id);

        if (!$kategori) {
            return redirect()->to('/kategori-pengeluaran')->with('error', 'Kategori tidak ditemukan.');
        }

        $nama       = $this->request->getPost('nama');
        $keterangan = $this->request->getPost('keterangan');

        if (empty($nama)) {
            return redirect()->back()->with('error', 'Nama kategori wajib diisi.');
        }

        $existing = $kategoriModel->where('nama', $nama)->where('id !=', $id)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Nama kategori sudah digunakan.');
        }

        $kategoriModel->update($id, [
            'nama'       => $nama,
            'keterangan' => $keterangan ?: null,
        ]);

        return redirect()->to('/kategori-pengeluaran')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $kategoriModel = new KategoriPengeluaranModel();
        $kategoriModel->delete($id);
        return redirect()->to('/kategori-pengeluaran')->with('success', 'Kategori berhasil dihapus.');
    }
}
