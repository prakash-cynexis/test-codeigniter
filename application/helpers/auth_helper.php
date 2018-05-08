<?php

use MYClasses\Providers\Token;
use MYClasses\Providers\AESProvider;

if (!function_exists('getCurrentUser')) {
    /**
     * @return array|bool
     */
    function getCurrentUser()
    {
        $user = get_instance()->session->userdata();
        $user = removePassedArrayKeys($user, ['__ci_last_regenerate']);
        return !empty($user) ? $user : false;
    }
}

if (!function_exists('getCurrentUserID')) {
    /**
     * @return bool|mixed
     */
    function getCurrentUserID()
    {
        $user_id = get_instance()->session->userdata('id');
        return !empty($user_id) ? $user_id : false;
    }
}

if (!function_exists('getCurrentUserRole')) {
    /**
     * @return bool|mixed
     */
    function getCurrentUserRole()
    {
        $role = get_instance()->session->userdata('role');
        return !empty($role) ? $role : false;
    }
}

if (!function_exists('getCurrentUserName')) {
    /**
     * @return bool|mixed
     */
    function getCurrentUserName()
    {
        $user_name = get_instance()->session->userdata('user_name');
        return !empty($user_name) ? $user_name : false;
    }
}

if (!function_exists('is_authenticated')) {
    // Returns user token object or error
    /**
     * @return bool|false|string
     */
    function is_authenticated()
    {
        $authToken = (new Token())->get();
        // token from request not matching with the token in DB, meaning user is logged in via another device
        if (!validAuthToken($authToken)) apiError('You have been logged out from this device. Please login again.', 401); // 401 HTTPS status code = UNAUTHORIZED
        return $authToken;
    }
}

if (!function_exists('validAuthToken')) {
    /**
     * @param $authToken
     * @return bool
     */
    function validAuthToken($authToken)
    {
        $CI = &get_instance();
        $CI->load->model('User_model');
        $userID = $authToken['id'];

        $user = $CI->User_model->get($userID);
        if (!$user) apiError('No user found with ID: ' . $userID);

        return get_token();
        //return get_token() === $user['auth_token'];
    }
}

if (!function_exists('get_token')) {
    /**
     * @param null $key
     * @return bool|false|string
     */
    function get_token($key = null)
    {
        $aes = new AESProvider();
        $CI = &get_instance();
        $headers = $CI->input->request_headers();
        $auth_token = !empty($headers['Auth-Token']) ? $headers['Auth-Token'] : false;

        if (!$auth_token) apiError('Auth Token not exist.');
        if (empty($key)) return $auth_token;

        $auth_token = $aes->decrypt($auth_token);
        return (!empty($auth_token[$key])) ? $auth_token[$key] : false;
    }
}