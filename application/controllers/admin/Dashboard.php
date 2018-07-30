<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->request->authorize(['admin']);
    }

    public function index()
    {
        $this->backend_view('dashboard');
    }
}