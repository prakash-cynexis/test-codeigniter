<?php

namespace MYClasses\Providers;


class DataTableProvider
{
    private static $CI;
    private static $data;
    private static $query;

    private static function initialize()
    {
        self::$CI = &get_instance();
        self::$data = $_POST;
    }

    public static function userLists()
    {
        self::initialize();
        self::$CI->db->select('id, user_name, email, role, id as action');
        self::$CI->db->from('users');
        self::$CI->db->get();
        self::$query = self::$CI->db->last_query();

        return self::$query;
    }

}