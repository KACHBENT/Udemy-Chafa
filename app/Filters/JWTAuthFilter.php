<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\JWTService;
use App\Models\UserModel;
use Config\Services;

class JWTAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
       

        $token = $this->extractToken($request);

        if (!$token) {
            return $this->unauthorized('Token faltante');
        }

        $jwtService = new JWTService();
        $userModel  = new UserModel();

        try {
         
            $payload = $jwtService->verifyAccessToken($token);
        } catch (\Throwable $e) {
            return $this->unauthorized('Token inválido o expirado');
        }

    
        $usuarioId = (int)($payload->sub ?? 0);
        $tv        = (int)($payload->tv ?? -1);

        if ($usuarioId <= 0) {
            return $this->unauthorized('Token sin sujeto');
        }

        $currentTv = (int) $userModel->getTokenVersion($usuarioId);
        if ($tv !== $currentTv) {
            return $this->unauthorized('Token invalidado (versión)');
        }

        return null; 
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nada
    }

    private function extractToken(RequestInterface $request): ?string
    {
        
        $auth = $request->getHeaderLine('Authorization');
        if (!$auth) {
            $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
            if (!$auth && function_exists('apache_request_headers')) {
                $headers = apache_request_headers();
                $auth = $headers['Authorization'] ?? $headers['authorization'] ?? '';
            }
        }
        if ($auth && preg_match('/Bearer\s+(\S+)/i', $auth, $m)) {
            return $m[1];
        }

     
        $cookie = $request->getCookie('accessToken');
        if ($cookie) {
            return $cookie;
        }

        $q = $request->getVar('access_token');
        if ($q) {
            return $q;
        }

        return null;
    }

    private function unauthorized(string $msg)
    {
        return Services::response()
            ->setStatusCode(401)
            ->setJSON(['error' => $msg]);
    }
}
