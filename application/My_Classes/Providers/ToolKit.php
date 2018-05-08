<?php

namespace MYClasses\Providers;

trait ToolKit
{
    private $CI;

    final public function CI_GetInstance()
    {
        $this->CI = &get_instance();
    }
}