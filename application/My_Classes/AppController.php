<?php

namespace MyClasses;

use MyClasses\Http\Request;
use MyClasses\Http\Response;

abstract class AppController extends \CI_Controller
{
    public $request;
    public $response;
    public $requestData;

    public function __construct()
    {
        parent::__construct();
        $this->request = new Request();
        $this->response = new Response();
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->response->success('logout successful.', [
            'redirect' => 'login'
        ]);
    }
}