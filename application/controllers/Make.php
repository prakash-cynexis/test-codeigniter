<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Make extends MY_Controller
{
    protected $_file = null;
    protected $_resource_path = APPPATH . '/My_Classes/Resources/';
    protected $_openingTag = '{{';
    protected $_closingTag = '}}';
    protected $_class_name;

    public function __construct()
    {
        parent::__construct();
        $this->_class_name = $this->_openingTag . 'class_Name' . $this->_closingTag;
    }

    public function table($table)
    {
        if (empty($table)) exit('Please provide table name.');
        $this->load->dbforge();
        $fields = [
            "id INT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY",
            "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL",
            "updated_at TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP",
        ];
        if ($table === 'users') {
            $fields = [
                "id INT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY",
                "user_name VARCHAR(50) NOT NULL",
                "email VARCHAR(100) NOT NULL",
                "password VARCHAR(100) NOT NULL",
                "role_id INT(20) UNSIGNED NOT NULL",
                "is_active ENUM('0','1') DEFAULT '0'",
                "auth_token TEXT DEFAULT NULL",
                "device_id TEXT DEFAULT NULL",
                "device_type ENUM('android','iOS') NULL DEFAULT NULL",
                "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL",
                "updated_at TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP",
                "FOREIGN KEY (role_id) REFERENCES roles(id)",
            ];
        }

        if ($table === 'roles') {
            $fields = [
                "id INT(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY",
                "role VARCHAR(20) NOT NULL",
                "department VARCHAR(50) NOT NULL",
                "description TEXT NOT NULL",
                "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL",
                "updated_at TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP",
            ];
        }

        $this->dbforge->drop_table($table, true);
        $done = $this->dbforge->add_field($fields)->create_table($table);
        if ($done) exit($table . ' table created.');
        exit('Some problem occurred.');
    }

    public function request($fileName)
    {
        if (is_cli()) {
            $file = $this->_resource_path . 'formatOfRequestClass.txt';

            if (!file_exists($file)) exit("Formatted file not found. please check core files.");

            $this->_file = file_get_contents($file);
            $text = str_replace($this->_class_name, $fileName, $this->_file);

            $fileName = APPPATH . "/My_Classes/http/requests/{$fileName}.php";
            $this->createFile($text, $fileName);
        }
        exit("No direct script access allowed");
    }

    public function controller($fileName)
    {
        if (is_cli()) {
            $file = $this->_resource_path . 'formatOfController.txt';

            if (!file_exists($file)) exit("Formatted file not found. please check core files.");

            $this->_file = file_get_contents($file);
            $text = str_replace($this->_class_name, $fileName, $this->_file);

            $fileName = APPPATH . "/controllers/{$fileName}.php";
            $this->createFile($text, $fileName);
        }
        exit("No direct script access allowed");
    }

    public function model($fileName)
    {
        if (is_cli()) {
            $file = $this->_resource_path . 'formatOfModel.txt';

            if (!file_exists($file)) exit("Formatted file not found. please check core files.");

            $this->_file = file_get_contents($file);
            $text = str_replace($this->_class_name, $fileName, $this->_file);

            $fileName = APPPATH . "/models/{$fileName}_model.php";
            $this->createFile($text, $fileName);
        }
        exit("No direct script access allowed");
    }

    public function web_view($fileName)
    {
        if (is_cli()) {
            $file = $this->_resource_path . 'formatOfView.txt';

            if (!file_exists($file)) exit("Formatted file not found. please check core files.");

            $text = $this->_file = file_get_contents($file);

            $fileName = APPPATH . "/views/layout/web/pages/{$fileName}.php";
            $this->createFile($text, $fileName);
        }
        exit("No direct script access allowed");
    }

    public function admin_view($fileName)
    {
        if (is_cli()) {
            $file = $this->_resource_path . 'formatOfView.txt';

            if (!file_exists($file)) exit("Formatted file not found. please check core files.");

            $text = $this->_file = file_get_contents($file);

            $fileName = APPPATH . "/views/layout/backend/pages/{$fileName}.php";
            $this->createFile($text, $fileName);
        }
        exit("No direct script access allowed");
    }

    private function createFile($content, $fileName)
    {
        $this->load->helper('file');

        if (!write_file($fileName, $content)) {
            exit('Unable to write the file');
        }
        exit('File written!');
    }
}