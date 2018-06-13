<?php

namespace MYClasses\Http;

class Request
{
    protected $CI;
    protected $authUser = false;
    private $requestData;

    public function __construct()
    {
        $data = [];
        $this->CI = &get_instance();
        if ($this->CI->input->server('REQUEST_METHOD') === 'GET') {
            $data = $this->CI->input->get();
        } elseif ($this->CI->input->server('REQUEST_METHOD') === 'POST') {
            $data = $this->CI->input->post();
        }
        $this->requestData = omitNullKeys($data, true);
        $this->CI->requestData = $this->requestData;
    }

    public function input($key = null)
    {
        if (is_string($key)) return (!empty($this->requestData[$key])) ? $this->requestData[$key] : [];
        return !empty($this->requestData) ? $this->requestData : [];
    }

    public function isApp()
    {
        $header = get_instance()->input->request_headers();
        return isset($header['Response-Type']) && $header['Response-Type'] === 'Json';
    }

    public function isWeb()
    {
        return (new \CI_User_agent())->is_browser();
    }

    public function validate(array $rules, $data = null, $redirect = null)
    {
        if (is_null($data)) $data = $this->input();
        if (is_null($redirect)) $redirect = true;

        if (empty($data)) response()->error(Response::DEFAULT_ERROR);

        $this->CI->form_validation->set_data($data);
        $this->CI->form_validation->set_rules($rules);
        if (!$this->CI->form_validation->run()) response()->form_validation_exception($data, $redirect);
        return $data;
    }

    final public function authorize($roles = null)
    {
        if ($this->isWeb()) {
            if (!empty(getCurrentUserRole()) && !empty(getCurrentUser())) {
                $this->authUser = true;

                if ($roles) {
                    if (is_string($roles)) $roles = [$roles];
                    $this->authUser = in_array(getCurrentUserRole(), $roles);
                }
            }
        }

        // The code for all app request are authenticate.
        if ($this->isApp()) {
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

        if (!$this->authUser) response()->error('Authentication required', null, true, 401);
        return $this->authUser;
    }
}