<?php

namespace MYClasses\Providers;

use MYClasses\Http\Response;

abstract class ValidatorProvider implements ValidatorInterface
{
    protected static $CI;
    protected static $input;
    protected static $validation_rules = [];

    protected static function initialize()
    {
        static::$CI = &get_instance();
        static::$input = request()->input();
    }

    protected static function validates($data = null, $redirect = true)
    {
        static::initialize();
        $class = get_called_class();
        if (is_null($data)) $data = static::$input;

        static::$CI->form_validation->set_data($data);
        static::$CI->form_validation->set_rules($class::rules());
        if (!static::$CI->form_validation->run()) response()->form_validation_exception($data, $redirect);
        return $data;
    }
}