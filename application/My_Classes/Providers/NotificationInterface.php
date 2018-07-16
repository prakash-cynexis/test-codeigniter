<?php

namespace MYClasses\Providers;

interface NotificationInterface
{
    public function send();

    public function message($message);

    public function device_type($device_type);

    public function device_token($device_token);
}