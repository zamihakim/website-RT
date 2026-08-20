<?php

namespace App\Controllers;

use App\Models\WargaModel;
use App\Models\UserModel;

class DataWarga extends BaseController
{
    public function index(): string
    {
        $auth = $this->getAuthData();

        $wargaModel  = new WargaModel();
        $daftarWarga = $wargaModel->orderBy('no_rumah', 'ASC')->findAll();

        $userModel = new UserModel();
        foreach ($daftarWarga as &$w) {
            $w['user_email'] = null;
            $w['user_role'] = null;
            if (!empty($w['user_id'])) {
                $user = $userModel->find($w['user_id']);
                if ($user) {
                    $w['user_email'] = $user['email'];
                    $w['user_role'] = $user['role'];
                }
            }
        }

        $data = [
            'title'      => 'Kelola Warga',
            'page_title' => 'Data Warga RT',
            'active'     => 'warga',
            'role'       => $auth['role'],
            'nama'       => $auth['nama'],
            'daftar_warga' => $daftarWarga,
            'success'    => session('success'),
            'error'      => session('error'),
        ];

        return view('pages/warga', $data);
    }

    public function tambah()
    {
        $nama    = $this->request->getPost('nama');
        $noRumah = $this->request->getPost('no_rumah');
        $alamat  = $this->request->getPost('alamat');
        $noHp    = $this->request->getPost('no_hp');
        $email   = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role    = $this->request->getPost('role') ?: 'warga';

        if (empty($nama) || empty($noRumah)) {
            return redirect()->back()->with('error', 'Nama dan No. Rumah wajib diisi.');
        }

        $wargaModel = new WargaModel();
        $existing = $wargaModel->where('no_rumah', $noRumah)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'No. Rumah sudah terdaftar.');
        }

        $userId = null;
        if (!empty($email)) {
            if (empty($password)) {
                return redirect()->back()->with('error', 'Password wajib diisi jika membuat akun login.');
            }
            $userModel = new UserModel();
            $existingUser = $userModel->findByEmail($email);
            if ($existingUser) {
                return redirect()->back()->with('error', 'Email sudah terdaftar.');
            }
            $userId = $userModel->createUser([
                'nama'     => $nama,
                'email'    => $email,
                'password' => $password,
                'role'     => $role,
            ]);
        }

        $wargaModel->insert([
            'user_id'  => $userId,
            'nama'     => $nama,
            'no_rumah' => $noRumah,
            'alamat'   => $alamat,
            'no_hp'    => $noHp,
        ]);

        return redirect()->to('/warga')->with('success', 'Warga berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $auth = $this->getAuthData();
        $wargaModel = new WargaModel();
        $warga = $wargaModel->find($id);

        if (!$warga) {
            return redirect()->to('/warga')->with('error', 'Data warga tidak ditemukan.');
        }

        $user = null;
        if (!empty($warga['user_id'])) {
            $userModel = new UserModel();
            $user = $userModel->find($warga['user_id']);
        }

        $data = [
            'title'      => 'Edit Warga',
            'page_title' => 'Edit Data Warga',
            'active'     => 'warga',
            'role'       => $auth['role'],
            'nama'       => $auth['nama'],
            'warga'      => $warga,
            'user'       => $user,
        ];

        return view('pages/warga_edit', $data);
    }

    public function update($id)
    {
        $wargaModel = new WargaModel();
        $warga = $wargaModel->find($id);

        if (!$warga) {
            return redirect()->to('/warga')->with('error', 'Data warga tidak ditemukan.');
        }

        $nama    = $this->request->getPost('nama');
        $noRumah = $this->request->getPost('no_rumah');
        $alamat  = $this->request->getPost('alamat');
        $noHp    = $this->request->getPost('no_hp');
        $status  = $this->request->getPost('status');
        $email   = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role    = $this->request->getPost('role') ?: 'warga';

        if (empty($nama) || empty($noRumah)) {
            return redirect()->back()->with('error', 'Nama dan No. Rumah wajib diisi.');
        }

        $existing = $wargaModel->where('no_rumah', $noRumah)->where('id !=', $id)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'No. Rumah sudah digunakan warga lain.');
        }

        $wargaModel->update($id, [
            'nama'     => $nama,
            'no_rumah' => $noRumah,
            'alamat'   => $alamat,
            'no_hp'    => $noHp,
            'status'   => $status,
        ]);

        $userModel = new UserModel();
        $existingUser = $warga['user_id'] ? $userModel->find($warga['user_id']) : null;

        if (!empty($email)) {
            $emailExists = $userModel->where('email', $email)->where('id !=', ($existingUser['id'] ?? 0))->first();
            if ($emailExists) {
                return redirect()->back()->with('error', 'Email sudah digunakan akun lain.');
            }

            $updateData = [
                'nama'  => $nama,
                'email' => $email,
                'role'  => $role,
            ];

            if (!empty($password)) {
                $updateData['password'] = password_hash($password, PASSWORD_BCRYPT);
            }

            if ($existingUser) {
                $userModel->update($existingUser['id'], $updateData);
            } else {
                if (empty($password)) {
                    return redirect()->back()->with('error', 'Password wajib diisi saat membuat akun baru.');
                }
                $userId = $userModel->createUser([
                    'nama'     => $nama,
                    'email'    => $email,
                    'password' => $password,
                    'role'     => $role,
                ]);
                $wargaModel->update($id, ['user_id' => $userId]);
            }
        } elseif ($existingUser) {
            $userModel->update($existingUser['id'], [
                'nama' => $nama,
                'role' => $role,
            ]);
            if (!empty($password)) {
                $userModel->update($existingUser['id'], [
                    'password' => password_hash($password, PASSWORD_BCRYPT),
                ]);
            }
        }

        return redirect()->to('/warga')->with('success', 'Data warga berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $wargaModel = new WargaModel();
        $warga = $wargaModel->find($id);

        if ($warga && !empty($warga['user_id'])) {
            $userModel = new UserModel();
            $userModel->delete($warga['user_id']);
        }

        $wargaModel->delete($id);
        return redirect()->to('/warga')->with('success', 'Data warga berhasil dihapus.');
    }
}
