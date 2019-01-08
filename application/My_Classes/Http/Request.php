<?php

namespace MyClasses\Http;

/**
 * @property bool is_admin
 * @property bool is_user
 */
class Request {

    protected $CI;
    protected $_files = [];
    protected $_inputs = [];
    protected $_inputStream = [];
    protected $_requestData;
    protected $authUser = false;

    public function __construct() {
        $data = [];
        $this->CI = &get_instance();

        switch ($this->CI->input->server('REQUEST_METHOD')) :
            case 'GET':
                $inputs = $this->getInputs();
                $data = array_merge($data, $inputs);
                break;
            case 'POST':
                $files = $this->getFiles();
                $inputs = $this->getInputs();
                $input_stream = $this->getInputStream();
                $data = array_merge($data, $files, $inputs, $input_stream);
                break;
        endswitch;

        $this->custom_log($data, 'before filter request');
        $this->_requestData = omitNullKeys($data); // filter for null and html values trimming
        $this->custom_log($data, 'after filter request');
        $this->CI->requestData = $this->_requestData;
    }

    public function getInputs() {
        if ($inputs = $this->CI->input->get(null, true)):
            $this->_inputs = $inputs;
        elseif ($inputs = $this->CI->input->post(null, true)):
            $this->_inputs = $inputs;
        endif;
        if (!empty($this->_inputs['request_data']) && ($request_data = isJson($this->_inputs['request_data']))) :
            $this->_inputs['request_data'] = $request_data;
        endif;
        if (!empty($this->_inputs['recurring_data']) && ($recurring_data = isJson($this->_inputs['recurring_data']))) :
            $this->_inputs['recurring_data'] = $recurring_data;
        endif;
        return $this->_inputs;
    }

    public function getFiles() {
        if (!empty($_FILES)) :
            foreach ($_FILES as $key => $file) :
                $this->_files[$key] = $file['name'];
            endforeach;
        endif;
        return $this->_files;
    }

    public function getInputStream() {
        if ($this->CI->input->get_request_header('Content-Type') === 'application/json') :
            $this->_inputStream = isJson(file_get_contents('php://input'));
            if (empty($this->_inputStream)) response()->error(Response::DEFAULT_ERROR);
        endif;
        return $this->_inputStream;
    }

    public function custom_log($data, $message) {
        log_activity(['url' => base_url() . $this->CI->router->class . '/' . $this->CI->router->method, 'method' => $this->CI->input->server('REQUEST_METHOD'), 'data' => $data], $message); // Remove in production
    }

    public function isPost() {
        if (!isPost()) response()->error('Only POST Method Is Allowed.');
        return $this;
    }

    public function isGet() {
        if (!isGet()) response()->error('Only GET Method Is Allowed.');
        return $this;
    }

    public function validate(array $rules, array $array = []) {
        $data = null;
        $redirect = true;
        if (isset($array['data'])) $data = $array['data'];
        if (isset($array['redirect'])) $redirect = $array['redirect'];

        if (is_null($data)) $data = $this->input();
        if (is_null($redirect)) $redirect = true;

        if (empty($data)) response()->error(Response::DEFAULT_ERROR);

        $this->CI->form_validation->set_data($data);
        $this->CI->form_validation->set_rules($rules);
        if (!$this->CI->form_validation->run()) :
            response()->form_validation_exception(['data' => $data, 'redirect' => $redirect]);
        endif;
        return $data;
    }

    public function input($key = null) {
        if (is_string($key)) return (!empty($this->_requestData[$key])) ? $this->_requestData[$key] : [];
        return !empty($this->_requestData) ? $this->_requestData : [];
    }

    public function __get($role) {
        $role = str_replace('_', ' ', $role);
        $role = trim(trim($role, 'is'));
        return $this->authorize(ucfirst($role));
    }

    final public function authorize($roles = null) {
        if ($this->isWeb()) :
            if (!empty(getCurrentUserRole()) && !empty(getCurrentUser())) :
                $this->authUser = true;

                if ($roles) :
                    if (is_string($roles)) $roles = [$roles];
                    $this->authUser = in_array(getCurrentUserRole(), $roles);
                endif;
            endif;
        endif;

        // The code for all app request are authenticate.
        if ($this->isApp()) :
            $this->authUser = true;

            if (!in_array($this->CI->router->method, $this->CI->_skip_auth_methods)) :
                $this->CI->authToken = is_authenticated();
                $this->authUser = true;

                if ($roles) :
                    if (is_string($roles)) $roles = [$roles];
                    $this->authUser = in_array($this->CI->authToken['role'], $roles);
                endif;
            endif;
        endif;

        if (!$this->authUser) response()->error('Authentication required', ['http_status' => Response::HTTP_UNAUTHORIZED]);
        return $this->authUser;
    }

    public function isWeb() {
        return (new \CI_User_agent())->is_browser();
    }

    public function isApp() {
        $header = get_instance()->input->request_headers();

        $isApp = false;
        if (isset($header['Response-Type']) && $header['Response-Type'] === "application/json") :
            $isApp = true;
        endif;
        if (isset($header['Content-Type']) && $header['Content-Type'] === "application/json") :
            $isApp = true;
        endif;
        return $isApp;
    }
}