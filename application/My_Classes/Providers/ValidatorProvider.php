<?php

namespace MyClasses\Providers;

use MyClasses\Http\Response;

abstract class ValidatorProvider implements ValidatorInterface {

    protected static $CI;
    protected static $input;
    protected static $validation_rules = [];

    protected static function form_validation($data = null) {
        static::initialize();
        $class = get_called_class();
        if (!is_null($data)) static::$input = $data;

        if (!static::$input) response()->error(Response::DEFAULT_ERROR);

        static::$CI->form_validation->set_data(static::$input);
        static::$CI->form_validation->set_rules($class::rules());
        if (!static::$CI->form_validation->run()) response()->form_validation_exception(['data' => static::$input]);
        return static::$input;
    }

    protected static function initialize() {
        static::$CI = &get_instance();
        static::$input = get_instance()->requestData;
    }
}