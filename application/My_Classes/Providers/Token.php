<?php

namespace MYClasses\Providers;

use MYClasses\Http\Response;

class Token
{
    private $crypter;
    private $response;

    public function __construct()
    {
        $this->crypter = new CrypterProvider();
        $this->response = new Response();
    }

    public function get()
    {
        if (!$token = self::isExists()) $this->response->error('Auth-Token is not exists.', ['http_status' => Response::HTTP_UNAUTHORIZED]);

        $token = $this->crypter->decrypt($token);
        if (!$token) $this->response->error('encrypted data is invalid.');
        if (!self::validate($token)) $this->response->error('Invalid user data.');
        return $token;
    }

    public function set($data)
    {
        $token = $this->crypter->encrypt(json_encode($data));
        if (!$token) $this->response->error('invalid data for token.');

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