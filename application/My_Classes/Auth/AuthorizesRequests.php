<?php

namespace MYClasses\Auth;

trait AuthorizesRequests
{
    private $authUser = false;
    private $roles = ['Admin', 'User'];

    /**
     * @param null $roles
     * @return bool
     */
    final public function authorized($roles = null)
    {
        $this->CI_GetInstance();

        if (request()->isWeb()) {
            if (!empty(getCurrentUserRole()) && !empty(getCurrentUser())) {
                $this->authUser = true;

                if ($roles) {
                    if (is_string($roles)) $roles = [$roles];
                    $this->authUser = in_array(getCurrentUserRole(), $roles);
                }
            }
        }

        // The code for all app request are authenticate.
        if (request()->isApp()) {
            if (!in_array($this->CI->router->method, $this->CI->_skip_auth_methods)) {
                $this->CI->authToken = is_authenticated();
                $this->authUser = true;

                if ($roles) {
                    if (is_string($roles)) $roles = [$roles];
                    $this->authUser = in_array($this->CI->authToken['role'], $roles);
                }
            } else {
                $this->authUser = true;
            }
        }

        return $this->authUser;
    }
}