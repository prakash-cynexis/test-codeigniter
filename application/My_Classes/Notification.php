<?php

namespace MYClasses;

use MYClasses\Providers\NotificationInterface;

class Notification
{
    private $notification;

    public function __construct(NotificationInterface $notification)
    {
        $this->notification = $notification;
    }

    public function send()
    {
        return $this->notification->send();
    }

    public function message($message)
    {
        return $this->notification->message($message);
    }

    public function device_type($device_type)
    {
        return $this->notification->device_type($device_type);
    }

    public function device_token($device_token)
    {
        return $this->notification->device_token($device_token);
    }
}