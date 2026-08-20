<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        if ($this->session->get('user_id')) {
            $role = $this->session->get('role');
            return redirect()->to($role === 'pengurus' ? '/' : '/warga/tagihan');
        }

        $data = [
            'title'  => 'Login',
            'role'   => $this->request->getGet('role'),
        ];

        return view('auth/login', $data);
    }

    public function attemptLogin()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role     = $this->request->getPost('role');

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('errors', 'Email atau password salah.');
        }

        if (!$user['aktif']) {
            return redirect()->back()
                ->withInput()
                ->with('errors', 'Akun tidak aktif.');
        }

        if ($user['role'] !== $role) {
            $label = $role === 'pengurus' ? 'Pengurus RT' : 'Warga';
            return redirect()->back()
                ->withInput()
                ->with('errors', "Anda tidak memiliki akses sebagai {$label}.");
        }

        $this->session->set([
            'user_id' => $user['id'],
            'nama'    => $user['nama'],
            'email'   => $user['email'],
            'role'    => $user['role'],
        ]);

        if ($role === 'pengurus') {
            return redirect()->to('/');
        }
        return redirect()->to('/warga/tagihan');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }
}
