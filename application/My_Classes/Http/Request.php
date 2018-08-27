<?php

namespace MyClasses\Http;

/**
 * @property bool is_admin
 * @property bool is_user
 */
class Request
{
    protected $CI;
    protected $_requestData;
    protected $authUser = false;

    public function __construct()
    {
        $data = [];
        $this->CI = &get_instance();
        if ($this->CI->input->server('REQUEST_METHOD') === 'GET') {
            $data = $this->CI->input->get(null, true);
        } elseif ($this->CI->input->server('REQUEST_METHOD') === 'POST') {
            $data = $this->CI->input->post(null, true);
        }
        $data = array_merge($data, $this->filterFiles());
        $this->_requestData = omitNullKeys($data);
        log_activity($this->_requestData, 'request data'); // Remove in production
        $this->CI->requestData = $this->_requestData;
    }

    public function input($key = null)
    {
        if (is_string($key)) return (!empty($this->_requestData[$key])) ? $this->_requestData[$key] : [];
        return !empty($this->_requestData) ? $this->_requestData : [];
    }

    public function isApp()
    {
        $header = get_instance()->input->request_headers();
        return isset($header['Response-Type']) && $header['Response-Type'] === 'application/json';
    }

    public function isWeb()
    {
        return (new \CI_User_agent())->is_browser();
    }

    public function validate(array $rules, array $array = [])
    {
        $data = null;
        $redirect = true;
        if (isset($array['data'])) $data = $array['data'];
        if (isset($array['redirect'])) $redirect = $array['redirect'];

        if (is_null($data)) $data = $this->input();
        if (is_null($redirect)) $redirect = true;

        if (empty($data)) response()->error(Response::DEFAULT_ERROR);

        $this->CI->form_validation->set_data($data);
        $this->CI->form_validation->set_rules($rules);
        if (!$this->CI->form_validation->run()) response()->form_validation_exception(['data' => $data, 'redirect' => $redirect]);
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
            $this->authUser = true;

            if (!in_array($this->CI->router->method, $this->CI->_skip_auth_methods)) {
                $this->CI->authToken = is_authenticated();
                $this->authUser = true;

                if ($roles) {
                    if (is_string($roles)) $roles = [$roles];
                    $this->authUser = in_array($this->CI->authToken['role'], $roles);
                }
            }
        }

        if (!$this->authUser) response()->error('Authentication required', ['http_status' => Response::HTTP_UNAUTHORIZED]);
        return $this->authUser;
    }

    public function files()
    {
        return $this->filterFiles();
    }

    protected function filterFiles()
    {
        $files = [];
        if (empty($_FILES)) return $files;

        foreach ($_FILES as $key => $file) {
            $files[$key] = $this->CI->security->xss_clean($file['name'], true);;
        }

        return $files;
    }

    public function __get($role)
    {
        $role = str_replace('_', ' ', $role);
        $role = trim(trim($role, 'is'));
        return $this->authorize(ucfirst($role));
    }
}