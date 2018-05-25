<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once CORE . 'MY_Model.php';

class User_model extends MY_Model
{
    protected $before_get = [];
    protected $before_create = ['hashPassword', 'revertViewToTable'];
    protected $before_update = ['hashPassword', 'revertViewToTable'];

    public function __construct()
    {
        parent::__construct();
    }
}