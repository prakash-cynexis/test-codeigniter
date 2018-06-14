<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MYClasses\AppController;

class MY_Controller extends AppController
{
    /**
     * Meta-Data is set from the Controller Side,
     * then this variable is take it to on the view
     * @var array $meta_data
     */
    public $meta_data = []; // set in layout class

    /**
     * java_script is set from the Controller Side,
     * then this variable is take it to on the view
     * @var array $java_script
     */
    public $java_script = []; // set in layout class

    /**
     * style_sheet is set from the Controller Side,
     * then this variable is take it to on the view
     * @var array $style_sheet
     */
    public $style_sheet = []; // set in layout class

    /**
     * This variable can be accessed in all the classes
     * when we send the request to the controller, then
     * this variable is set by the Authorization class.
     * @var array $authToken
     */
    public $authToken;

    /**
     * Through this variable, is set from the Controller Side,
     * then this variable is take it to on the view
     * @var array $data
     */
    public $data = [];

    /**
     * Through this variable, we pass the requested method bypass,
     * they can have access to Method Without Auth-Token.
     * @var array $_skip_auth_methods
     */
    public $_skip_auth_methods = [];
    private $offset;

    public function __construct()
    {
        parent::__construct();
        $this->setOffset();
    }

    public function setOffset($limit = 10)
    {
        $offset = 0;
        if (!empty($this->requestData['offset'])) $offset = $this->requestData['offset'];
        $this->offset = "LIMIT {$limit} OFFSET {$offset}";
    }

    public function getOffset()
    {
        return $this->offset;
    }

    final public function view($page, $title = null)
    {
        $folder = "web";
        $this->data['current_user'] = getCurrentUser();
        $this->data['title'] = (!empty($title)) ? $title : variableToStr($page);
        $pageLoad = $this->load->view("layout/{$folder}/pages/{$page}", $this->data, true);
        $this->loadView($pageLoad, $folder);
    }

    final public function backend_view($page, $title = null)
    {
        $folder = "backend";
        $this->data['current_user'] = getCurrentUser();
        $this->data['title'] = (!empty($title)) ? $title : variableToStr($page);
        $pageLoad = $this->load->view("layout/{$folder}/pages/{$page}", $this->data, true);
        $this->loadView($pageLoad, $folder);
    }

    final public function setJavaScript(array $files, $folder)
    {
        foreach ($files as $index => $file) {
            $file = assetUrl("{$folder}/js/{$file}");
            $this->java_script[] = "<script type=\"text/javascript\" src=\"$file\"></script>";
        }
        return $this;
    }

    final public function setStyleSheet(array $files, $folder)
    {
        foreach ($files as $index => $file) {
            $file = assetUrl("{$folder}/css/{$file}");
            $this->style_sheet[] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"$file\">";
        }
        return $this;
    }

    final public function setMetaData(array $data)
    {
        foreach ($data as $index => $item) {
            $this->meta_data[] = "<meta name=\"$index\" content=\"$item\">";
        }
        return $this;
    }

    private function loadView($content, $folder)
    {
        $view_data = ['content' => $content];
        $this->load->view("layout/{$folder}/layout.php", $view_data);
    }

    final public function extractInfo($data, $section, $role)
    {
        switch ($role) {
            case 'User':
                $section = $this->userValidColumns($section);
                break;
        }
        $section = array_intersect_key($data, array_flip($section));
        return $section;
    }

    private function userValidColumns($section)
    {
        if (ucfirst($section) == 'User') {
            return [
                'user_name', 'email', 'password', 'role', 'subscribe_to_email'
            ];
        }
        if (ucfirst($section) == 'Profile') {
            return [
                'user_id', 'photo', 'first_name', 'last_name', 'address_line_1', 'address_line_2',
                'city', 'state', 'zip', 'mobile_no', 'date_of_birth',
            ];
        }
    }
}