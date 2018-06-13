<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Carbon\Carbon;
use MYClasses\Http\Response;
use MYClasses\Providers\Token;

class Login extends MY_Controller
{
    private $AUTH_ROLES = ['Admin', 'User'];

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (isPost()) {
            $request = $this->request->validate([
                ['field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email'],
                ['field' => 'password', 'label' => 'Password', 'rules' => 'required']
            ]);

            $loginRequestComplete = $this->login($request);
            if (!$loginRequestComplete) $this->response->error(Response::INVALID_CREDENTIALS);

            switch (getCurrentUserRole()):
                case 'Admin':
                    $this->response->success('Welcome ' . variableToStr(getCurrentUserName()), null, 'dashboard');
                    break;
                case 'User':
                    $this->response->success('Welcome ' . variableToStr(getCurrentUserName()), null, 'dashboard');
                    break;
                default:
                    $this->logout();
                    break;
            endswitch;
        }
        $this->view('login');
    }

    private function login($request)
    {
        $this->load->model("User_model");
        $user = $this->User_model->get_by(['email' => $request['email']]);
        if (!$user) return false;

        if (!password_verify($request['password'], $user['password'])) return false;
        if (!booleanIntValue($user['is_active'])) $this->response->error(Response::LOGIN_NOT_APPROVED);

        $user_details = $this->User_model->get_by(['email' => $user['email']]);

        if (!$user_details) return false;
        if (!in_array($user_details['role'], $this->AUTH_ROLES)) $this->response->error("'" . ucfirst($user_details['role']) . "' role is not permitted to log in.");

        $user = [
            'id' => $user_details['id'],
            'role' => $user_details['role'],
            'email' => $user_details['email'],
            'logged_in' => true,
            'user_name' => $user_details['user_name'],
            'created_at' => Carbon::now()->toDateTimeString()
        ];

        if (isAppRequest()) {
            $authToken = (new Token())->set($user);
            $this->response->success('Authentication successful.',
                ['user' => $user, 'Auth-Token' => $authToken]
            );
        }
        $this->session->set_userdata($user);
        if (!$this->session->userdata('logged_in')) $this->response->error('Session is not working on Server side. Please try after some time.');

        return true;
    }
}