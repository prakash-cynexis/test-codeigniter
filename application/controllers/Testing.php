<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MYClasses\Providers\DataTableProvider;
use MYClasses\Notifications\ResetPassword;
use MYClasses\Providers\EmailProvider;
use MYClasses\Providers\EmailTemplateProvider;
use MYClasses\Providers\NotificationProvider;
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Testing extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(["User_model"]);
    }

    public function testAuthAPi()
    {
        $this->authorized();
        echo "hello";
    }

    public function testPost()
    {
        if (isPost()) {
            $data = \MYClasses\Http\Requests\NewRegistrationRequest::validate();

            /*$data = $this->request->validate([
                ['field' => 'address_prof', 'rules' => 'required']
            ]);*/

            dd($data);
        }
    }

    public function password_reset()
    {
    }

    public function testTemplateEmail()
    {
        $templateName = 'password_reset.php';

        $emailTemplate = new EmailTemplateProvider($templateName);
        $emailTemplate->setData([
            'name' => 'Prakash Sharma',
            'action_url' => 'zxc',
            'support_url' => 'asd',
            'browser_name' => 'google chrome',
            'operating_system' => 'Window 10',
            'company_name' => 'Cynexis Media.',
            'company_address' => 'Indore. PIN 452010'
        ]);

        $content = $emailTemplate->output();

        //*************************************************//

        $userInfo = [
            'to' => 'prakash.cynexis@gmail.com',
            'password' => '123456',
        ];

        //$notifyContent = new NotificationProvider();

        $emailContent = new EmailProvider();
        $emailContent->subject('New Password Details.');
        $emailContent->line('asdasd asda sdas das d asd {{password_reset}}');
        $emailContent->action('password_reset', base_url('password_reset'));
        //$emailContent->html($content);

        $email = new resetPassword($emailContent);
        $email = $email->send($userInfo);
        dd($email);
    }

    public function pass()
    {
        echo password_hash('admin123', PASSWORD_BCRYPT);
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