<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('auth_logged_in') || !$session->get('usuario')) {
            return redirect()
                ->to(site_url('auth/login'))
                ->with('toast_error', 'Debes iniciar sesión primero.');
        }

        $requiredRoles = $arguments ?? [];
        if (empty($requiredRoles)) {
            return;
        }

     
        $usuario   = $session->get('usuario');
        $userRoles = $usuario['roles'] ?? [];

        if (!is_array($userRoles)) {
            $userRoles = [$userRoles];
        }

    
        $hasRole = false;
        foreach ($requiredRoles as $r) {
            if (in_array($r, $userRoles, true)) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
        
            return redirect()
                ->back()
                ->with('toast_error', 'No tienes permisos para acceder a esta sección.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
