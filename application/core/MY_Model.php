<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MyClasses\Http\Response;

require_once 'BASE_Model.php';

class MY_Model extends BASE_Model
{
    protected $protectedColumn;
    protected $table_original;
    protected $table_view;
    protected $before_get = ['changeTableToView'];
    protected $before_create = ['removeUnknownColumn', 'revertViewToTable'];
    protected $before_update = ['removeUnknownColumn', 'revertViewToTable'];

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

    final protected function hashPassword($data)
    {
        if (!isset($data['password'])) return $data;
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        return $data;
    }

    protected function removeUnknownColumn($data)
    {
        if (empty($data)) response()->error(Response::DEFAULT_ERROR);
        $data = array_intersect_key($data, array_flip($this->protectedColumn));
        if (blank($data)) dd('after remove column data is empty.');
        return $data;
    }
}