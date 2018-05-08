<?php

namespace MYClasses\Auth;

use MYClasses\Http\Response;
use MYClasses\Providers\Token;
use Carbon\Carbon;

class AuthManager implements AuthInterface
{
    private $CI;
    private $response;
    private $token;
    private static $AUTH_ROLES = ['Admin', 'User'];

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->response = new Response();
        $this->token = new Token();
    }

    public function login($userData)
    {
        $user = $this->CI->User_model->get_by(['email' => $userData['email']]);
        if (!$user) return false;

        if (!password_verify($userData['password'], $user['password'])) return false;
        if (!booleanIntValue($user['is_active'])) $this->response->error(Response::LOGIN_NOT_APPROVED);

        $user_details = $this->CI->User_model->get_by(['email' => $user['email']]);

        if (!$user_details) return false;
        if (!in_array($user_details['role'], self::$AUTH_ROLES)) $this->response->error("'" . ucfirst($user_details['role']) . "' role is not permitted to log in.");

        $user = [
            'id' => $user_details['id'],
            'role' => $user_details['role'],
            'email' => $user_details['email'],
            'logged_in' => true,
            'user_name' => $user_details['user_name'],
            'created_at' => Carbon::now()->toDateTimeString()
        ];

        if (isAppRequest()) {
            $authToken = $this->token->set($user);
            apiSuccess('Authentication successful.',
                ['user' => $user, 'Auth-Token' => $authToken]
            );
        }
        $this->CI->session->set_userdata($user);
        if (!$this->CI->session->userdata('logged_in')) $this->response->error('Session is not working on Server side. Please try after some time.');

        return true;
    }

    public function user()
    {
        $user = get_instance()->session->userdata();
        $user = removePassedArrayKeys($user, ['__ci_last_regenerate']);
        if (isAppRequest() && blank($user)) $user = $this->token->get();

        return !empty($user) ? $user : false;
    }

    public function userID()
    {
        $user_id = get_instance()->session->userdata('id');
        if (isAppRequest() && blank($user_id)) $user_id = $this->token->get()['id'];

        return !empty($user_id) ? $user_id : false;
    }

    public function userName()
    {
        $user_name = get_instance()->session->userdata('user_name');
        if (isAppRequest() && blank($user_name)) $user_name = $this->token->get()['user_name'];

        return !empty($user_name) ? $user_name : false;
    }

    public function userRole()
    {
        $role = get_instance()->session->userdata('role');
        if (isAppRequest() && blank($role)) $role = $this->token->get()['role'];

        return !empty($role) ? $role : false;
    }

    public function userEmail()
    {
        $email = get_instance()->session->userdata('email');
        if (isAppRequest() && blank($email)) $email = $this->token->get()['email'];

        return !empty($email) ? $email : false;
    }
}