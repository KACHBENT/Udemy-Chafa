<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

class JWT extends BaseConfig
{
    public string $secret;
    public string $iss;
    public string $aud;
    public int    $accessTtl;
    public int    $refreshTtl;
    public string $algo;

    public function __construct()
    {
        parent::__construct();
        $this->secret    = getenv('JWT_SECRET');
        $this->iss       = getenv('JWT_ISS') ?: base_url();
        $this->aud       = getenv('JWT_AUD') ?: base_url();
        $this->accessTtl = (int)(getenv('JWT_ACCESS_TTL') ?: 900);
        $this->refreshTtl= (int)(getenv('JWT_REFRESH_TTL') ?: 1209600);
        $this->algo      = getenv('JWT_ALGO') ?: 'HS256';
    }
}