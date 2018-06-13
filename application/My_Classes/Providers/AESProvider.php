<?php

namespace MYClasses\Providers;

use MYClasses\Http\Response;
use RNCryptor\RNCryptor\Decryptor;
use RNCryptor\RNCryptor\Encryptor;

class AESProvider
{
    private $CI;
    private $response;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->response = new Response();
    }

    public function encrypt($value)
    {
        $cryptor = new Encryptor;
        $encryption_key = $this->CI->config->item('encryption_key');
        $encrypted = '';
        if (blank($value)) $this->response->error('Can not allow null or empty field.', 400);
        try {
            $encrypted = $cryptor->encrypt($value, $encryption_key);
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
        $encryption_key = $this->CI->config->item('encryption_key');
        $decrypted = '';
        if (blank($value)) $this->response->error('encrypted data is empty.');
        try {
            $decrypted = $deCryptor->decrypt(rawurldecode($value), $encryption_key);
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