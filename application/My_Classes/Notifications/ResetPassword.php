<?php

namespace MYClasses\Notifications;

use MYClasses\Providers\NotifyInterface;

class ResetPassword
{
    private $CI;
    private $notify;

    public function __construct(NotifyInterface $provider)
    {
        $this->CI = &get_instance();
        $this->notify = $provider;
    }

    public function send(array $userInfo)
    {
        return $this->notify->send($userInfo);
    }
}