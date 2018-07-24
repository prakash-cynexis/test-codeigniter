<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MYClasses\Events;
use MYClasses\Providers\DataTableProvider;
use MYClasses\Providers\EmailProvider;
use MYClasses\Providers\EmailTemplateProvider;
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Testing extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(["User_model"]);
    }

    public function test()
    {
        if (isPost()) {
            $data = $this->requestData;
            $data = $this->security->xss_clean($data);

            $data['role_id'] = 1;
            $this->load->model("User_model");
            $this->User_model->insert($data);
            dd($data);
        }
        $this->load->view("testing");
    }

    public function testInput()
    {
        $data = $this->requestData;
        dd($data);
    }

    public function testFile()
    {
        $data = $this->request->input();
        dd($data);
    }

    public function testPost()
    {
        echo VIEWPATH;
    }

    public function password_reset()
    {
    }

    public function testTemplateEmail()
    {
        $userInfo = [
            'email' => 'prakash.cynexis@gmail.com',
            'password' => '123456',
            'user_name' => 'prakash',
        ];

        $done = Events::emit($userInfo, Events::WELCOME_EMAIL, ['Email']);
        dd($done);
    }

    public function pass($data = null)
    {
        if (is_null($data)) $data = 'admin123';
        echo password_hash($data, PASSWORD_BCRYPT);
    }

    public function lists()
    {
        $userLists = new Datatables(new CodeigniterAdapter());
        $sql = DataTableProvider::userLists();

        $userLists->query($sql);

        $userLists->edit('action', function ($data) {
            return aVoid("Edit", 'btn btn-xs btn-primary', null, "data-user_id='{$data['id']}'");
        });
        die($userLists->generate());
    }

    public function dataTable()
    {
        $this->load->view("test_datatable");
    }

    public function logout()
    {
        parent::logout();
    }
}