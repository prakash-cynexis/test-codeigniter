<?php

namespace MyClasses\Providers;

use Firebase\JWT\JWT;
use MyClasses\Http\Response;

class CrypterProvider
{
    private $response;
    private $encryption_key;

    public function __construct()
    {
        $this->response = new Response();
        $this->encryption_key = ENCRYPTION_KEY;
    }

    public function encrypt($value)
    {
        $value['exp'] = $value['created_at'] + 3600;
        $encrypted = null;
        if (empty($value)) $this->response->error('Can not allow null or empty field.');
        try {
            $encrypted = JWT::encode($value, $this->encryption_key);
        } catch (\Exception $e) {
            log_activity($e->getMessage(), 'encrypt:-');
        } finally {
            if (!$encrypted) $this->response->error('decrypted data is invalid.');
        }
        return $encrypted;
    }

    public function decrypt($value)
    {
        $decrypted = null;
        if (empty($value)) $this->response->error('encrypted data is empty.');
        try {
            $decrypted = JWT::decode($value, $this->encryption_key, ['HS256']);
        } catch (\Exception $e) {
            log_activity($e->getMessage(), 'decrypt:-');
            if ($e->getMessage() === 'Expired token') $this->response->error('Expired token.');
        } finally {
            if (!$decrypted) $this->response->error('encrypted data is invalid.');
        }
        if (!$decrypted) $this->response->error('encrypted data is invalid.');
        $decrypted = json_decode(json_encode($decrypted), true);
        return $decrypted;
    }
}