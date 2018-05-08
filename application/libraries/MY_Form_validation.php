<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
    public $CI;

    function __construct($config = array())
    {
        parent::__construct($config);
        $this->CI = &get_instance();
    }

    function valid_values($value, $stringValues)
    {
        $list = explode(',', $stringValues);
        if (!in_array($value, $list)) {
            $this->CI->form_validation->set_message('valid_values', 'The {field} field\'s valid values are: ' . $stringValues);
            return false;
        }
        return true;
    }

    function valid_timestamp($dateString)
    {
        if (!isValidTimeStamp($dateString)) {
            $this->CI->form_validation->set_message('valid_timestamp', '{field} must be a valid timestamp, e.g. ' . date('Y-m-d H:i:s'));
            return false;
        }
        return true;
    }

    function valid_date($dateString)
    {
        if (!isValidDate($dateString)) {
            $this->CI->form_validation->set_message('valid_date', '{field} must be a valid value, e.g. ' . date('Y-m-d'));
            return false;
        }
        return true;
    }
}