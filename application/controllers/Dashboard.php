<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $authorized = $this->authorized();
        if (!$authorized) $this->response->error('Authentication required.');
    }

    public function index()
    {
        $this->admin_view('dashboard');
    }
}
