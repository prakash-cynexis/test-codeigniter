<?php

namespace MYClasses\Providers;

interface ValidatorInterface
{
    public static function rules();

    public static function validate($data = null);
}