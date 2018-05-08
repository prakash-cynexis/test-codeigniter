<?php

namespace MYClasses\Providers;

use RNCryptor\RNCryptor\Decryptor;
use RNCryptor\RNCryptor\Encryptor;

class AESProvider
{
    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    public function encrypt($value)
    {
        $cryptor = new Encryptor;
        $encryption_key = $this->CI->config->item('encryption_key');
        $encrypted = '';
        if (blank($value)) apiError('Can not allow null or empty field.', 400);
        try {
            $encrypted = $cryptor->encrypt($value, $encryption_key);
        } catch (\Exception $e) {
            log_activity($e->getMessage(), 'encrypt:-');
        } finally {
            if (!$encrypted) apiError('decrypted data is invalid.');;
        }
        return rawurlencode($encrypted);
    }

    public function decrypt($value)
    {
        $deCryptor = new Decryptor;
        $encryption_key = $this->CI->config->item('encryption_key');
        $decrypted = '';
        if (blank($value)) apiError('Auth-Token is empty.');
        try {
            $decrypted = $deCryptor->decrypt(rawurldecode($value), $encryption_key);
        } catch (\Exception $e) {
            log_activity($e->getMessage(), 'decrypt:-');
        } finally {
            if (!$decrypted) apiError('encrypted data is invalid.');
        }
        $encrypted = isJson($decrypted);
        if (!$encrypted) apiError('Auth-Token is invalid.');
        return $encrypted;
    }
}