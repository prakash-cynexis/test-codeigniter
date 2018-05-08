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
        if (!static::$input) response()->error(Response::DEFAULT_ERROR);
    }

    protected static function validates($data = null)
    {
        static::initialize();
        $class = get_called_class();
        if (!is_null($data)) static::$input = $data;

        static::$CI->form_validation->set_data(static::$input);
        static::$CI->form_validation->set_rules($class::rules());
        if (!static::$CI->form_validation->run()) response()->error(formatExceptionAsDataArray(static::$CI->form_validation->error_array()), static::$input);
        return static::$input;
    }
}