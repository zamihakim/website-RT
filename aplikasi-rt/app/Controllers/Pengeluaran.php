<?php

namespace App\Controllers;

use App\Models\PengeluaranModel;
use App\Models\KategoriPengeluaranModel;

class Pengeluaran extends BaseController
{
    public function index(): string
    {
        $auth = $this->getAuthData();
        $periode = $this->request->getGet('periode') ?: date('Y-m');

        $pengeluaranModel = new PengeluaranModel();
        $daftar = $pengeluaranModel->getByPeriode($periode);

        $kategoriModel = new KategoriPengeluaranModel();
        $kategoriList = $kategoriModel->findAll();

        $data = [
            'title'         => 'Pengeluaran RT',
            'page_title'    => 'Pengeluaran RT',
            'active'        => 'pengeluaran',
            'role'          => $auth['role'],
            'nama'          => $auth['nama'],
            'periode'       => $periode,
            'pengeluaran'   => $daftar,
            'kategori_list' => $kategoriList,
            'success'       => session('success'),
            'error'         => session('error'),
        ];

        return view('pages/pengeluaran', $data);
    }

    public function edit($id)
    {
        $pengeluaranModel = new PengeluaranModel();
        $item = $pengeluaranModel->find($id);

        if (!$item) {
            return redirect()->to('/pengeluaran')->with('error', 'Data pengeluaran tidak ditemukan.');
        }

        $kategoriModel = new KategoriPengeluaranModel();
        $kategoriList = $kategoriModel->findAll();

        $auth = $this->getAuthData();
        $periode = date('Y-m');

        $item['foto_list'] = $this->parseFoto($item['foto_bukti']);

        $data = [
            'title'         => 'Edit Pengeluaran',
            'page_title'    => 'Edit Pengeluaran RT',
            'active'        => 'pengeluaran',
            'role'          => $auth['role'],
            'nama'          => $auth['nama'],
            'periode'       => $periode,
            'pengeluaran'   => $item,
            'kategori_list' => $kategoriList,
        ];

        return view('pages/pengeluaran_edit', $data);
    }

    public function hapus_foto($id)
    {
        $pengeluaranModel = new PengeluaranModel();
        $item = $pengeluaranModel->find($id);

        if (!$item) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $index = (int) $this->request->getPost('idx');
        $fotos = $this->parseFoto($item['foto_bukti']);

        if (!isset($fotos[$index])) {
            return redirect()->back()->with('error', 'Foto tidak ditemukan.');
        }

        $filename = $fotos[$index];
        $path = FCPATH . 'uploads/' . $filename;
        if (file_exists($path)) {
            unlink($path);
        }

        array_splice($fotos, $index, 1);
        $pengeluaranModel->update($id, [
            'foto_bukti' => !empty($fotos) ? json_encode($fotos) : null,
        ]);

        return redirect()->back()->with('success', 'Foto berhasil dihapus.');
    }

    public function update($id)
    {
        $pengeluaranModel = new PengeluaranModel();
        $item = $pengeluaranModel->find($id);

        if (!$item) {
            return redirect()->to('/pengeluaran')->with('error', 'Data pengeluaran tidak ditemukan.');
        }

        $kategori_id = $this->request->getPost('kategori_id');
        $tanggal     = $this->request->getPost('tanggal');
        $jumlah      = $this->request->getPost('jumlah');
        $keterangan  = $this->request->getPost('keterangan');

        if (empty($kategori_id) || empty($tanggal) || empty($jumlah) || empty($keterangan)) {
            return redirect()->back()->with('error', 'Semua field wajib diisi.');
        }

        $kategoriModel = new KategoriPengeluaranModel();
        $kategori = $kategoriModel->find($kategori_id);
        $isSosial = ($kategori && strtolower($kategori['nama']) === 'sosial');

        $existingFotos = $this->parseFoto($item['foto_bukti']);
        $newFiles = $this->request->getFileMultiple('foto_bukti');
        $addedNames = [];

        if ($newFiles) {
            foreach ($newFiles as $f) {
                if ($f === null || !$f->isValid() || $f->getError() === UPLOAD_ERR_NO_FILE) {
                    continue;
                }
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($f->getClientMimeType(), $allowedTypes)) {
                    return redirect()->back()->with('error', 'File harus berupa gambar (JPG/PNG).')->withInput();
                }
                if ($f->getSize() > 2 * 1024 * 1024) {
                    return redirect()->back()->with('error', 'Ukuran foto maksimal 2MB per file.')->withInput();
                }
                $name = $f->getRandomName();
                $f->move(FCPATH . 'uploads', $name);
                $addedNames[] = $name;
            }
        }

        $allFotos = array_merge($existingFotos, $addedNames);

        if ($isSosial && empty($allFotos)) {
            return redirect()->back()->with('error', 'Kategori Sosial wajib menyertakan bukti foto.')->withInput();
        }

        $pengeluaranModel->update($id, [
            'kategori_id' => $kategori_id,
            'tanggal'     => $tanggal,
            'jumlah'      => $jumlah,
            'keterangan'  => $keterangan,
            'foto_bukti'  => !empty($allFotos) ? json_encode($allFotos) : null,
        ]);

        return redirect()->to('/pengeluaran')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function tambah()
    {
        $kategori_id = $this->request->getPost('kategori_id');
        $tanggal     = $this->request->getPost('tanggal');
        $jumlah      = $this->request->getPost('jumlah');
        $keterangan  = $this->request->getPost('keterangan');

        if (empty($kategori_id) || empty($tanggal) || empty($jumlah) || empty($keterangan)) {
            return redirect()->back()->with('error', 'Semua field wajib diisi.');
        }

        $kategoriModel = new KategoriPengeluaranModel();
        $kategori = $kategoriModel->find($kategori_id);
        $isSosial = ($kategori && strtolower($kategori['nama']) === 'sosial');

        $newFiles = $this->request->getFileMultiple('foto_bukti');
        $fotoNames = [];

        if ($newFiles) {
            foreach ($newFiles as $f) {
                if ($f === null || !$f->isValid() || $f->getError() === UPLOAD_ERR_NO_FILE) {
                    continue;
                }
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!in_array($f->getClientMimeType(), $allowedTypes)) {
                    return redirect()->back()->with('error', 'File harus berupa gambar (JPG/PNG).')->withInput();
                }
                if ($f->getSize() > 2 * 1024 * 1024) {
                    return redirect()->back()->with('error', 'Ukuran foto maksimal 2MB per file.')->withInput();
                }
                $name = $f->getRandomName();
                $f->move(FCPATH . 'uploads', $name);
                $fotoNames[] = $name;
            }
        }

        if ($isSosial && empty($fotoNames)) {
            return redirect()->back()->with('error', 'Kategori Sosial wajib menyertakan bukti foto.')->withInput();
        }

        $pengeluaranModel = new PengeluaranModel();
        $pengeluaranModel->insert([
            'kategori_id' => $kategori_id,
            'tanggal'     => $tanggal,
            'jumlah'      => $jumlah,
            'keterangan'  => $keterangan,
            'foto_bukti'  => !empty($fotoNames) ? json_encode($fotoNames) : null,
        ]);

        return redirect()->to('/pengeluaran')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function hapus($id)
    {
        $pengeluaranModel = new PengeluaranModel();
        $item = $pengeluaranModel->find($id);

        if ($item && !empty($item['foto_bukti'])) {
            $fotos = $this->parseFoto($item['foto_bukti']);
            foreach ($fotos as $filename) {
                $path = FCPATH . 'uploads/' . $filename;
                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }

        $pengeluaranModel->delete($id);
        return redirect()->to('/pengeluaran')->with('success', 'Pengeluaran berhasil dihapus.');
    }

    private function parseFoto($fotoBukti): array
    {
        if (empty($fotoBukti)) {
            return [];
        }
        $decoded = json_decode($fotoBukti, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        return [$fotoBukti];
    }
}
