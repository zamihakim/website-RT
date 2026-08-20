<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserManagement extends BaseController
{
    public function index(): string
    {
        $auth = $this->getAuthData();

        $userModel = new UserModel();
        $users = $userModel->orderBy('role', 'ASC')->orderBy('nama', 'ASC')->findAll();

        $data = [
            'title'      => 'Kelola Akun',
            'page_title' => 'Manajemen Akun Users',
            'active'     => 'users',
            'role'       => $auth['role'],
            'nama'       => $auth['nama'],
            'users'      => $users,
            'success'    => session('success'),
            'error'      => session('error'),
        ];

        return view('pages/users', $data);
    }

    public function tambah()
    {
        $nama     = $this->request->getPost('nama');
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role     = $this->request->getPost('role');

        if (empty($nama) || empty($email) || empty($password) || empty($role)) {
            return redirect()->back()->with('error', 'Semua field wajib diisi.');
        }

        $userModel = new UserModel();
        $existing = $userModel->findByEmail($email);
        if ($existing) {
            return redirect()->back()->with('error', 'Email sudah terdaftar.');
        }

        $userModel->createUser([
            'nama'     => $nama,
            'email'    => $email,
            'password' => $password,
            'role'     => $role,
        ]);

        return redirect()->to('/users')->with('success', 'Akun berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return redirect()->to('/users')->with('error', 'Akun tidak ditemukan.');
        }

        $auth = $this->getAuthData();

        $data = [
            'title'      => 'Edit Akun',
            'page_title' => 'Edit Akun User',
            'active'     => 'users',
            'role'       => $auth['role'],
            'nama'       => $auth['nama'],
            'user'       => $user,
        ];

        return view('pages/users_edit', $data);
    }

    public function update($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return redirect()->to('/users')->with('error', 'Akun tidak ditemukan.');
        }

        $nama  = $this->request->getPost('nama');
        $email = $this->request->getPost('email');
        $role  = $this->request->getPost('role');
        $aktif = $this->request->getPost('aktif') ?? 1;

        if (empty($nama) || empty($email) || empty($role)) {
            return redirect()->back()->with('error', 'Nama, email, dan role wajib diisi.');
        }

        $existing = $userModel->where('email', $email)->where('id !=', $id)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Email sudah digunakan akun lain.');
        }

        $updateData = [
            'nama'  => $nama,
            'email' => $email,
            'role'  => $role,
            'aktif' => $aktif,
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $userModel->update($id, $updateData);

        return redirect()->to('/users')->with('success', 'Akun berhasil diperbarui.');
    }

    public function hapus($id)
    {
        $userModel = new UserModel();
        $userModel->delete($id);
        return redirect()->to('/users')->with('success', 'Akun berhasil dihapus.');
    }
}
