<?php

use MYClasses\Http\Response;

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("User_model");
    }

    public function index()
    {
        $this->load->view('welcome_message');
    }

    public function login()
    {
        if (isPost()) {
            $user = $this->request->validate([
                ['field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email'],
                ['field' => 'password', 'label' => 'Password', 'rules' => 'required'],
            ]);

            $loginRequestComplete = $this->auth->login($user);
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

    public function logout()
    {
        parent::logout();
    }
}
