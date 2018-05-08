<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'BASE_Model.php';

class MY_Model extends BASE_Model
{
    protected $table_original;
    protected $table_view;

    protected $before_get = ['changeTableToView'];
    protected $before_create = ['revertViewToTable'];
    protected $before_update = ['revertViewToTable'];

    public function __construct()
    {
        parent::__construct();
        $this->table_original = $this->_table;
        $this->table_view = 'v_' . $this->table_original;
    }

    final public function changeTableToView($data)
    {
        $this->_table = $this->table_view;
        return $data;
    }

    final public function revertViewToTable($data)
    {
        $this->_table = $this->table_original;
        return $data;
    }
}