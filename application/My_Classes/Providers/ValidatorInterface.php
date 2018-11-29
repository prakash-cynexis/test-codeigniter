<?php

namespace MyClasses\Providers;

interface ValidatorInterface {

    static function rules();

    static function validate($data = null);
}