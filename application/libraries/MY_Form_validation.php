<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

    function __construct($config = []) {
        parent::__construct($config);
    }

    function valid_values($value, $stringValues) {
        $list = explode(',', $stringValues);
        if (!in_array($value, $list)) {
            $this->CI->form_validation->set_message('valid_values', 'The {field} field\'s valid values are: ' . $stringValues);
            return false;
        }
        return true;
    }

    function invalid_values($value, $stringValues) {
        $list = explode(',', $stringValues);
        if (in_array($value, $list)) {
            $this->CI->form_validation->set_message('invalid_values', 'The {field} field\'s invalid values are' . $stringValues);
            return false;
        }
        return true;
    }

    function valid_timestamp($dateString) {
        if (!isValidTimeStamp($dateString)) {
            $this->CI->form_validation->set_message('valid_timestamp', '{field} must be a valid timestamp, e.g. ' . date('Y-m-d H:i:s'));
            return false;
        }
        return true;
    }

    function valid_date($dateString) {
        if (!isValidDate($dateString)) {
            $this->CI->form_validation->set_message('valid_date', '{field} must be a valid value, e.g. ' . date('Y-m-d'));
            return false;
        }
        return true;
    }

    function valid_latitude($lat) {
        if (!$data = preg_match('/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/', $lat)) {
            log_activity($data, 'valid_latitude');
            $this->CI->form_validation->set_message('valid_latitude', '{field} must be a valid value, e.g. 00.000000');
            return false;
        }
        return true;
    }

    function valid_longitude($long) {
        if (!$data = preg_match('/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/', $long)) {
            log_activity($data, 'valid_longitude');
            $this->CI->form_validation->set_message('valid_longitude', '{field} must be a valid value, e.g. 00.000000');
            return false;
        }
        return true;
    }
}