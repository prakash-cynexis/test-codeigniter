<?php

namespace MyClasses\Http\Requests;

use MyClasses\Providers\ValidatorProvider;

class NewRegistrationRequest extends ValidatorProvider
{
    public static function validate($data = null)
    {
        $data = static::form_validation($data);
        return $data;
    }

    public static function rules()
    {
        static::$validation_rules = [
            ['field' => 'field_one', 'rules' => 'required']
        ];

        if (empty(static::$input['field_two'])) {
            static::$validation_rules[] = ['field' => 'field_two', 'rules' => 'required'];
        }

        return static::$validation_rules;
    }
}