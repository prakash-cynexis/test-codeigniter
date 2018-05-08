<?php

namespace MYClasses\Auth;

interface AuthInterface
{
    public function user();

    public function userID();

    public function userName();

    public function userRole();

    public function userEmail();
}