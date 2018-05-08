<?php

namespace MYClasses\Providers;

interface NotifyInterface
{
    public function send(array $userInfo);
}