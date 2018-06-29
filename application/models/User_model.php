<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model
{
    protected $before_get = [];
    protected $before_create = ['removeUnknownColumn', 'hashPassword', 'revertViewToTable'];
    protected $before_update = ['removeUnknownColumn', 'hashPassword', 'revertViewToTable'];

    public function __construct()
    {
        parent::__construct();
    }
}