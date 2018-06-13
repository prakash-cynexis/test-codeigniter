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

    public function request($fileName)
    {
        if (is_cli()) {
            $file = $this->_resource_path . 'formatOfRequestClass.txt';

            if (!file_exists($file)) die("Formatted file not found. please check core files.");

            $this->_file = file_get_contents($file);
            $text = str_replace($this->_class_name, $fileName, $this->_file);

            $fileName = APPPATH . "/My_Classes/http/requests/{$fileName}.php";
            $this->createFile($text, $fileName);
        }
        die("No direct script access allowed");
    }

    public function controller($fileName)
    {
        if (is_cli()) {
            $file = $this->_resource_path . 'formatOfController.txt';

            if (!file_exists($file)) die("Formatted file not found. please check core files.");

            $this->_file = file_get_contents($file);
            $text = str_replace($this->_class_name, $fileName, $this->_file);

            $fileName = APPPATH . "/controllers/{$fileName}.php";
            $this->createFile($text, $fileName);
        }
        die("No direct script access allowed");
    }

    public function model($fileName)
    {
        if (is_cli()) {
            $file = $this->_resource_path . 'formatOfModel.txt';

            if (!file_exists($file)) die("Formatted file not found. please check core files.");

            $this->_file = file_get_contents($file);
            $text = str_replace($this->_class_name, $fileName, $this->_file);

            $fileName = APPPATH . "/models/{$fileName}_model.php";
            $this->createFile($text, $fileName);
        }
        die("No direct script access allowed");
    }

    public function views($fileName)
    {
        if (is_cli()) {
            $file = $this->_resource_path . 'formatOfView.txt';

            if (!file_exists($file)) die("Formatted file not found. please check core files.");

            $text = $this->_file = file_get_contents($file);

            $fileName = APPPATH . "/views/layout/backend/pages/{$fileName}.php";
            $this->createFile($text, $fileName);
        }
        die("No direct script access allowed");
    }

    private function createFile($content, $fileName)
    {
        $this->load->helper('file');

        if (!write_file($fileName, $content)) {
            die('Unable to write the file');
        }
        die('File written!');
    }
}