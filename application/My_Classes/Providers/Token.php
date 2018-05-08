<?php

namespace MYClasses\Providers;

use MYClasses\Http\Response;
use MYClasses\Providers\AESProvider;

class Token
{
    private $aes;

    public function __construct()
    {
        $this->aes = new AESProvider();
    }

    public function get()
    {
        if (!$token = self::isExists()) apiError('Auth-Token is not exists.', Response::HTTP_UNAUTHORIZED);

        $token = $this->aes->decrypt($token);
        if (!$token) apiError('encrypted data is invalid.');
        if (!self::validate($token)) apiError('Invalid user data.', Response::HTTP_NOT_FOUND);
        return $token;
    }

    public function set($data)
    {
        $token = $this->aes->encrypt(json_encode($data));
        if (!$token) apiError('invalid data for token.');

        return $token;
    }

    private function validate($token)
    {
        $keys = ['id', 'role', 'email', 'logged_in', 'user_name'];
        foreach ($keys as $index => $key) {
            if (!isset($token[$key]) && empty($token[$key])) return false;
        }
        return true;
    }

    private function isExists()
    {
        $headers = get_instance()->input->request_headers();
        return (array_key_exists('Auth-Token', $headers)
            && !empty($headers['Auth-Token'])) ? $headers['Auth-Token'] : false;
    }
}