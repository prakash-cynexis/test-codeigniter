<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model {

    protected $protectedColumn = ['user_name', 'email', 'password', 'role_id', 'is_active', 'auth_token', 'deleted'];
    protected $before_create = ['removeUnknownColumn', 'hashPassword', 'revertViewToTable'];
    protected $before_update = ['removeUnknownColumn', 'hashPassword', 'revertViewToTable'];

    public function __construct() {
        parent::__construct();
    }
}