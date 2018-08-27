<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use MyClasses\Http\Response;
use MyClasses\Providers\Token;

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
                    $this->response->success('Welcome ' . variableToStr(getCurrentUserName()), ['redirect' => 'admin/dashboard']);
                    break;
                //Create User controller if have any user role in this project.
                case 'User':
                    $this->response->success('Welcome ' . variableToStr(getCurrentUserName()), ['redirect' => 'user/dashboard']);
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
        if (!$user) $this->response->error('You have entered an invalid email address.');

        if (!password_verify($request['password'], $user['password'])) return false;
        if (!intToBoolean($user['is_active'])) $this->response->error(Response::LOGIN_NOT_APPROVED);

        if (!in_array($user['role'], $this->AUTH_ROLES)) $this->response->error("Role (" . ucfirst($user['role']) . ") is not permitted to log in.");

        $user_data = [
            'id' => $user['id'],
            'role' => $user['role'],
            'email' => $user['email'],
            'logged_in' => true,
            'user_name' => $user['user_name'],
            'created_at' => timeStamp()
        ];

        if (isAppRequest()) {
            $authToken = (new Token())->set($user_data);
            $updateToken = $this->User_model->update_by(['email' => $user['email']], ['auth_token' => $authToken]);
            if (!$updateToken) $this->response->error('Token not update in DB.');

            $this->response->success('Authentication successful.', [
                'data' => ['user' => $user_data, 'Auth-Token' => $authToken]
            ]);
        }
        $this->session->set_userdata($user_data);
        if (!$this->session->userdata('logged_in')) $this->response->error('Session is not working on Server side. Please try after some time.');

        return true;
    }
}