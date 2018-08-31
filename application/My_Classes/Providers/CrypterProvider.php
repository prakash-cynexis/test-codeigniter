<?php

namespace MyClasses\Providers;

use MyClasses\Http\Response;
use RNCryptor\RNCryptor\Decryptor;
use RNCryptor\RNCryptor\Encryptor;

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
        $cryptor = new Encryptor;
        $encrypted = null;
        if (empty($value)) $this->response->error('Can not allow null or empty field.');
        try {
            $encrypted = $cryptor->encrypt(json_encode($value), $this->encryption_key);
        } catch (\Exception $e) {
            log_activity($e->getMessage(), 'encrypt:-');
        } finally {
            if (!$encrypted) $this->response->error('decrypted data is invalid.');
        }
        return rawurlencode($encrypted);
    }

    public function decrypt($value)
    {
        $deCryptor = new Decryptor;
        $decrypted = null;
        if (empty($value)) $this->response->error('encrypted data is empty.');
        try {
            $decrypted = $deCryptor->decrypt(rawurldecode($value), $this->encryption_key);
        } catch (\Exception $e) {
            log_activity($e->getMessage(), 'decrypt:-');
        } finally {
            if (!$decrypted) $this->response->error('encrypted data is invalid.');
        }
        $encrypted = isJson($decrypted);
        if (!$encrypted) $this->response->error('encrypted data is invalid.');
        return $encrypted;
    }
}