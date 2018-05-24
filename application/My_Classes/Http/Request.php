<?php

namespace MYClasses\Http;

class Request
{
    protected $CI;
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

    public function setRequestData($requestData)
    {
        unset($this->CI->requestData);
        $this->CI->requestData = $requestData;
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

    public function validate(array $rules, $redirect = null, $data = null)
    {
        if (is_null($data)) $data = $this->input();

        $this->CI->form_validation->set_data($data);
        $this->CI->form_validation->set_rules($rules);
        if (!$this->CI->form_validation->run()) response()->form_validation_exception($data, $redirect);
        return $data;
    }
}