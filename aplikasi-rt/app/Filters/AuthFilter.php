<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();

        if (!$session->get('user_id')) {
            return redirect()->to('/login');
        }

        if ($arguments !== null) {
            $role = $session->get('role');
            if (!in_array($role, $arguments)) {
                return redirect()->to('/login');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
