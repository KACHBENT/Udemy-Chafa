<?php
namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use InvalidArgumentException;

class JWTService
{
    private string $secret;
    private string $algo;
    private string $iss;
    private string $aud;
    private int $accessTtl;
    private int $refreshTtl;
    private int $leeway;

    public function __construct()
    {
        // .env -> usa env() de CI4 (quita comillas automáticamente)
        $this->secret    = (string) env('JWT_SECRET', 'dev_secret_change_me');
        $this->algo      = (string) env('JWT_ALGO', 'HS256');
        $this->iss       = (string) env('JWT_ISS', base_url()); // emisor esperado
        $this->aud       = (string) env('JWT_AUD', base_url()); // audiencia esperada
        $this->accessTtl = (int) env('JWT_ACCESS_TTL', 1800);
        $this->refreshTtl= (int) env('JWT_REFRESH_TTL', 1209600);
        $this->leeway    = (int) env('JWT_LEEWAY', 0);

        if ($this->secret === '' || strlen($this->secret) < 32) {
            throw new InvalidArgumentException('JWT_SECRET must be at least 32 chars.');
        }
    }

    /** Emite access token firmado */
    public function issueAccessToken(array $user, int $tokenVersion = 0): string
    {
        $now = time();
        $exp = $now + $this->accessTtl;

        $payload = [
            'iss'   => $this->iss,
            'aud'   => $this->aud,
            'iat'   => $now,
            'nbf'   => $now,
            'exp'   => $exp,
            'sub'   => (string) ($user['usuarioId'] ?? ''),
            'name'  => (string) ($user['usuario_Nombre'] ?? ''),
            'tv'    => $tokenVersion,
            'roles' => $user['roles'] ?? [],
        ];

        return JWT::encode($payload, $this->secret, $this->algo);
    }

    /** Verifica firma/exp y valida iss/aud; devuelve payload (stdClass) */
    public function verifyAccessToken(string $jwt): \stdClass
    {
        if ($this->leeway > 0) {
            JWT::$leeway = $this->leeway;
        }

        $payload = JWT::decode($jwt, new Key($this->secret, $this->algo)); // exp/nbf/iat ya se chequean

        // Validación de issuer/audience (exacta)
        if (!isset($payload->iss) || (string) $payload->iss !== $this->iss) {
            throw new \RuntimeException('Issuer inválido');
        }
        if (!isset($payload->aud) || (string) $payload->aud !== $this->aud) {
            throw new \RuntimeException('Audience inválida');
        }

        return $payload;
    }

    /** Emite refresh token opaco (no JWT) */
    public function issueRefreshToken(): array
    {
        $now   = time();
        $raw   = random_bytes(32);
        $token = bin2hex($raw);

        return [
            'token'     => $token,
            'issuedAt'  => date('Y-m-d H:i:s', $now),
            'expiresAt' => date('Y-m-d H:i:s', $now + $this->refreshTtl),
        ];
    }
}
