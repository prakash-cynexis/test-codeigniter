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
    public $meta_data; // set in layout class

    /**
     * java_script is set from the Controller Side,
     * then this variable is take it to on the view
     * @var array $java_script
     */
    public $java_script; // set in layout class

    /**
     * style_sheet is set from the Controller Side,
     * then this variable is take it to on the view
     * @var array $style_sheet
     */
    public $style_sheet; // set in layout class

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

    public function __construct()
    {
        parent::__construct();
    }
}