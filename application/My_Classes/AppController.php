<?php

namespace MYClasses;

use MYClasses\Auth\AuthManager;
use MYClasses\Auth\AuthorizesRequests;
use MYClasses\Http\Request;
use MYClasses\Http\Response;
use MYClasses\Providers\Layouts;
use MYClasses\Providers\ToolKit;

abstract class AppController extends \CI_Controller
{
    use ToolKit, Layouts, AuthorizesRequests;

    public $request;
    public $requestData;
    public $response;
    public $auth;

    public function __construct()
    {
        parent::__construct();
        $this->auth = new AuthManager();
        $this->request = new Request();
        $this->response = new Response();
        $this->requestData = $this->request->input();
    }

    final public function view($page, $title = null)
    {
        $this->webLayout($title)->publish($page);
    }

    final public function admin_view($page, $title = null)
    {
        $this->adminLayout($title)->publish($page);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->response->success('logout successful.', null, 'welcome/login');
    }
}