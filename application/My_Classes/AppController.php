<?php

namespace MYClasses;

use MYClasses\Http\Request;
use MYClasses\Http\Response;

abstract class AppController extends \CI_Controller
{
    public $request;
    public $requestData;
    public $response;
    public $auth;

    public function __construct()
    {
        parent::__construct();
        $this->request = new Request();
        $this->response = new Response();
        $this->requestData = $this->request->input();
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->response->success('logout successful.', ['redirect' => 'login']);
    }
}