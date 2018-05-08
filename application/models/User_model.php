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

    public function formatData($data)
    {
        $data['created_at'] = false;
        return $data;
    }

    protected function hashPassword($data)
    {
        if (!isset($data['password'])) return $data;
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        return $data;
    }
}