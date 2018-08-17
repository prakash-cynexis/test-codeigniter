<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->request->is_admin;
    }

    public function index()
    {
        $this->backend_view('dashboard');
    }
}