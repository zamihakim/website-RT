<?php

namespace App\Controllers;

use App\Models\WargaModel;
use App\Models\PembayaranModel;
use App\Models\PengaturanIuranModel;

class Pembayaran extends BaseController
{
    public function index(): string
    {
        $auth = $this->getAuthData();
        $year = $this->request->getGet('year') ?: date('Y');

        $wargaModel      = new WargaModel();
        $pembayaranModel = new PembayaranModel();
        $iuranModel      = new PengaturanIuranModel();

        $semuaWarga = $wargaModel->where('status', 'aktif')->orderBy('no_rumah', 'ASC')->findAll();
        $iuran      = $iuranModel->getBerjalan();

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

        $dataList = [];
        $no = 1;
        foreach ($semuaWarga as $w) {
            $rows = [];
            for ($m = 1; $m <= 12; $m++) {
                $pb = $bulanan[$m][$w['id']] ?? null;
                $rows[$m] = $pb;
            }
            $dataList[] = [
                'no'     => $no++,
                'id'     => $w['id'],
                'nama'   => $w['nama'],
                'rumah'  => $w['no_rumah'],
                'bulan'  => $rows,
            ];
        }

        $data = [
            'title'      => 'Monitoring Pembayaran',
            'page_title' => 'Monitoring Pembayaran Iuran',
            'active'     => 'pembayaran',
            'role'       => $auth['role'],
            'nama'       => $auth['nama'],
            'year'       => (int)$year,
            'data_list'  => $dataList,
            'iuran'      => $iuran,
            'success'    => session('success'),
            'error'      => session('error'),
        ];

        return view('pages/pembayaran', $data);
    }

    public function tambah()
    {
        $warga_id      = $this->request->getPost('warga_id');
        $periode       = $this->request->getPost('periode');
        $tanggal_bayar = $this->request->getPost('tanggal_bayar');
        $metode        = $this->request->getPost('metode');
        $catatan       = $this->request->getPost('catatan');

        if (empty($warga_id) || empty($periode) || empty($tanggal_bayar) || empty($metode)) {
            return redirect()->back()->with('error', 'Semua field wajib diisi.');
        }

        $iuranModel = new PengaturanIuranModel();
        $iuran = $iuranModel->getByPeriode($periode);
        $nominal = (float)($iuran['nominal'] ?? 50000);

        $pembayaranModel = new PembayaranModel();
        $existing = $pembayaranModel->where('warga_id', $warga_id)->where('periode', $periode)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Warga ini sudah memiliki pembayaran untuk periode tersebut.');
        }

        $pembayaranModel->insert([
            'warga_id'      => $warga_id,
            'iuran_id'      => $iuran['id'] ?? 1,
            'periode'       => $periode,
            'nominal'       => $nominal,
            'tanggal_bayar' => $tanggal_bayar,
            'metode'        => $metode,
            'catatan'       => $catatan ?: null,
            'status'        => 'lunas',
        ]);

        return redirect()->to('/pembayaran')->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function hapus($id)
    {
        $pembayaranModel = new PembayaranModel();
        $pb = $pembayaranModel->find($id);
        $pembayaranModel->delete($id);
        $year = $pb ? substr($pb['periode'], 0, 4) : date('Y');
        return redirect()->to('/pembayaran?year=' . $year)->with('success', 'Data pembayaran berhasil dihapus.');
    }

    public function setuju($id)
    {
        $pembayaranModel = new PembayaranModel();
        $pembayaran = $pembayaranModel->find($id);

        if (!$pembayaran) {
            return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $pembayaranModel->update($id, [
            'status'       => 'lunas',
            'catatan_tolak' => null,
        ]);

        $year = substr($pembayaran['periode'], 0, 4);
        return redirect()->to('/pembayaran?year=' . $year)->with('success', 'Pembayaran berhasil disetujui.');
    }

    public function tolak($id)
    {
        $pembayaranModel = new PembayaranModel();
        $pembayaran = $pembayaranModel->find($id);

        if (!$pembayaran) {
            return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $catatan = $this->request->getPost('catatan_tolak');
        if (empty($catatan)) {
            return redirect()->back()->with('error', 'Alasan penolakan wajib diisi.');
        }

        $pembayaranModel->update($id, [
            'status'        => 'ditolak',
            'catatan_tolak' => $catatan,
        ]);

        $year = substr($pembayaran['periode'], 0, 4);
        return redirect()->to('/pembayaran?year=' . $year)->with('success', 'Pembayaran ditolak. Warga akan diminta mengulangi pembayaran.');
    }

    public function riwayat($id)
    {
        $auth = $this->getAuthData();
        $year = $this->request->getGet('year') ?: date('Y');

        $wargaModel      = new WargaModel();
        $pembayaranModel = new PembayaranModel();

        $warga = $wargaModel->find($id);
        if (!$warga) {
            return redirect()->back()->with('error', 'Data warga tidak ditemukan.');
        }

        $pembayaran = $pembayaranModel->where('warga_id', $id)->findAll();
        $map = [];
        foreach ($pembayaran as $pb) {
            $y = substr($pb['periode'], 0, 4);
            $m = (int)substr($pb['periode'], 5, 2);
            $map[$y][$m] = $pb;
        }

        $data = [
            'title'      => 'Riwayat Pembayaran',
            'page_title' => 'Riwayat Pembayaran ' . $warga['nama'],
            'active'     => 'pembayaran',
            'role'       => $auth['role'],
            'nama'       => $auth['nama'],
            'warga'      => $warga,
            'year'       => (int)$year,
            'riwayat'    => $map,
            'success'    => session('success'),
            'error'      => session('error'),
        ];

        return view('pages/pembayaran_riwayat', $data);
    }
}
