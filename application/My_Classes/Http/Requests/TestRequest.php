<?php

namespace MYClasses\Http\Requests;

use MYClasses\Providers\ValidatorProvider;

class TestRequest extends ValidatorProvider
{
    public static function validate($data = null)
    {
        $data = static::validates($data);
        return $data;
    }

    public static function rules()
    {
        static::$validation_rules = [
            ['field' => 'email', 'rules' => 'required']
        ];

        if (empty(static::$input['field_two'])) {
            static::$validation_rules[] = ['field' => 'password', 'rules' => 'required'];
        }

        return static::$validation_rules;
    }
}