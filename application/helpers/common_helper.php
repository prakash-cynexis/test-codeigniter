<?php
if (!function_exists('arrayRemoveZero')) {
    /**
     * @param array $array
     * @return array
     */
    function arrayRemoveZero(array $array)
    {
        if (empty($array)) return $array;
        foreach ($array as $key => $value) {
            if ($value === 0 || $value === '0') unset($array[$key]);
            if (is_array($value)) $array[$key] = arrayRemoveZero($value);
        }
        return $array;
    }
}

if (!function_exists('dd')) {
    /**
     * @param $x
     * @param bool $exit
     */
    function dd($x, $exit = true)
    {
        if (is_array($x) || is_object($x)) {
            echo "<pre>";
            print_r($x);
            echo "</pre><hr/>";
        } else {
            var_dump($x);
            echo "<hr/>";
        }
        if (boolVal($exit)) exit();
    }
}

if (!function_exists('apiSuccess')) {
    /**
     * @param $message
     * @param null $data
     * @param int $http_status
     */
    function apiSuccess($message, $data = null, $http_status = 200)
    {
        header('Content-Type: application/json', true, $http_status);
        die(json_encode(successResponse($message, $data)));
    }
}

if (!function_exists('apiError')) {
    /**
     * @param $message
     * @param int $http_status
     */
    function apiError($message, $http_status = 400)
    {
        header('Content-Type: application/json', true, $http_status);
        die(json_encode(errorResponse($message)));
    }
}

if (!function_exists('errorResponse')) {
    /**
     * @param $message
     * @return array
     */
    function errorResponse($message)
    {
        $response = ['error' => true];
        $response['message'] = !is_array($message) ? [$message] : $message;
        return $response;
    }
}

if (!function_exists('successResponse')) {
    /**
     * @param $message
     * @param null $data
     * @return array
     */
    function successResponse($message, $data = null)
    {
        $response = ['error' => false];
        $response['message'] = !is_array($message) ? [$message] : $message;
        if (!is_null($data)) $response['data'] = $data;
        return $response;
    }
}

if (!function_exists('back')) {
    function back()
    {
        if (isAjaxRequest() || isAppRequest()) apiError('You are not authorized to access', 401);
        $url = null;
        if (isset($_SERVER['HTTP_REFERER'])) $url = $_SERVER['HTTP_REFERER'];
        if (!empty($url)) redirect($url);
        redirect(base_url());
    }
}

if (!function_exists('randomString')) {
    /**
     * @param int $length
     * @return string
     */
    function randomString($length = 25)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('toastrJS')) {
    function toastrJS()
    {
        return '<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
                <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">';
    }
}

if (!function_exists('htmlFlash')) {
    /**
     * @param string $type
     * @return string
     */
    function htmlFlash($type = 'alert')
    {
        $CI = &get_instance();
        $flash = $CI->session->flashdata($type);
        if (!$flash) return false;

        if (!is_array($flash['message'])) {
            return "<div class='{$flash['class']}-alert'><i class='fa fa-check-circle' aria-hidden='true'></i>{$flash['message']}</div>";
        }

        $msg = "<div class='{$flash['class']}-alert'>";
        foreach ($flash['message'] as $flash_message) {
            $msg .= "<i class='fa fa-check-circle' aria-hidden='true'></i>{$flash_message}<br/>";
        }
        return $msg . "</div>";
    }
}

if (!function_exists('jqueryFlash')) {
    /**
     * @param string $type
     * @param string $time
     * @return string
     */
    function jqueryFlash($type = 'alert', $time = '0')
    {
        $CI = &get_instance();
        $flash = $CI->session->flashdata($type);
        if (!$flash) return false;

        if (is_array($flash['message'])) {
            $msg = '<script type="text/javascript">';
            foreach ($flash['message'] as $message) {
                $msg .= "setTimeout(function() {
                            toastr.options = {
                                closeButton: true,
                                progressBar: false,
                                showMethod: 'slideDown',
                                positionClass: 'toast-bottom-right',
                                extendedTimeOut: '0',
                                hideMethod: 'fadeOut',
                                timeOut: {$time}
                            };
                            toastr.{$flash['class']}('{$message}');
    
                        }, 1300);";
            }
            return $msg . '</script>';
        } else {
            $msg = '<script type="text/javascript">';
            $msg .= "setTimeout(function() {
                        toastr.options = {
                            closeButton: true,
                            progressBar: true,
                            showMethod: 'slideDown',
                            positionClass: 'toast-bottom-right',
                            extendedTimeOut: '0',
                            hideMethod: 'fadeOut',
                            timeOut: {$time}
                        };
                        toastr.{$flash['class']}('{$flash['message']}');

                    }, 1300);";
            return $msg . '</script>';
        }
    }
}

if (!function_exists('success')) {
    /**
     * @param $message
     * @param bool $redirect
     * @param null $data
     */
    function success($message, $redirect = true, $data = null)
    {
        $type = 'alert';
        $CI = &get_instance();
        $CI->session->set_flashdata($type, ['class' => 'success', 'message' => $message]);
        if ($data) $CI->session->set_flashdata('data', $data);

        if (is_string($redirect) && $redirect != '' && $redirect != null) redirect($redirect);
        if ($redirect === true) back();
    }
}

if (!function_exists('warning')) {
    /**
     * @param $message
     * @param bool $redirect
     * @param null $data
     */
    function warning($message, $redirect = true, $data = null)
    {
        $type = 'alert';
        $CI = &get_instance();
        $CI->session->set_flashdata($type, ['class' => 'warning', 'message' => $message]);
        if ($data) $CI->session->set_flashdata('data', $data);

        if (is_string($redirect) && $redirect != '' && $redirect != null) redirect($redirect);
        if ($redirect === true) back();
    }
}

if (!function_exists('error')) {
    /**
     * @param $message
     * @param bool $redirect
     * @param null $data
     */
    function error($message, $redirect = true, $data = null)
    {
        $type = 'alert';
        $CI = &get_instance();
        $CI->session->set_flashdata($type, ['class' => 'error', 'message' => $message]);
        if ($data) $CI->session->set_flashdata('data', $data);

        if (is_string($redirect) && $redirect != '' && $redirect != null) redirect($redirect);
        if ($redirect === true) back();
    }
}

if (!function_exists('getFlashValue')) {
    /**
     * @param $field
     * @param string $type
     * @return null
     */
    function getFlashValue($field, $type = 'data')
    {
        $data = get_instance()->session->flashdata($type);
        if (empty($data)) return null;
        return (isset($data[$field])) ? $data[$field] : null;
    }
}

// --------------------------Assets function----------------------------------------------//
if (!function_exists('assetUrl')) {
    /**
     * @param $file_name
     * @return string
     */
    function assetUrl($file_name)
    {
        return base_url() . ASSET_PATH . $file_name;
    }
}

if (!function_exists('uploadUrl')) {
    /**
     * @param $file_name
     * @return string
     */
    function uploadUrl($file_name)
    {
        return base_url() . UPLOAD_PATH . $file_name;
    }
}

if (!function_exists('downloadUrl')) {
    /**
     * @param $file_name
     * @return string
     */
    function downloadUrl($file_name)
    {
        return base_url() . DOWNLOAD_PATH . $file_name;
    }
}

if (!function_exists('adminUrl')) {
    /**
     * @param $uri
     * @return string
     */
    function adminUrl($uri)
    {
        return base_url() . ADMIN . $uri;
    }
}

// --------------------------Assets function----------------------------------------------//

if (!function_exists('jquery')) {
    /**
     * @param string $version
     * @return string
     */
    function jquery($version = '2.2.4')
    {
        // Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline
        $out = "<script src='//ajax.googleapis.com/ajax/libs/jquery/{$version}/jquery.min.js'></script>";
        return $out;
    }
}

if (!function_exists('arrayToExcel')) {
    /**
     * @param $header
     * @param $data
     * @param string $fileName
     * @return string
     */
    function arrayToExcel(array $header, array $data, $fileName = "export_excel")
    {
        $CI = &get_instance();
        $CI->load->library('XLSXWriter');
        $writer = new XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $header);
        $writer->writeSheet($data);
        $fileName = DOWNLOAD_PATH . $fileName . '.xlsx';
        $writer->writeToFile($fileName);
        return base_url($fileName);
    }
}

if (!function_exists('trimming')) {
    /**
     * @param $array
     * @return mixed
     */
    function trimming($array)
    {
        $blacklist = ['search', 'submit', 'save', 'generate', 'create', 'upload', 'login', 'forgot'];

        if (!is_array($array)) {
            return trim($array);
        }

        foreach ($blacklist as $key) {
            unset($array[$key]);
        }
        return array_map('trimming', $array);
    }
}

if (!function_exists('aOnclick')) {
    /**
     * @param $title
     * @param $function
     * @param string|array $param
     * @param string $attributes
     * @return string
     */
    function aOnclick($title, $function, $param, $attributes = null)
    {
        if (!empty($attributes)) {
            $attributes = _stringify_attributes($attributes);
        }
        if (is_array($param)) $param = implode("','", $param);
        return "<a href=\"javascript:void(0);\" onclick=\"{$function}('{$param}')\" $attributes>{$title}</a>";
    }
}

if (!function_exists('aVoid')) {
    /**
     * @param $title
     * @param string $id
     * @param string $class
     * @param string $attributes
     * @return string
     */
    function aVoid($title, $class = null, $id = null, $attributes = null)
    {
        $aVoid = null;
        if (!empty($id)) $aVoid .= ' id="' . $id . '"';
        if (!empty($class)) $aVoid .= ' class="' . $class . '"';
        if (!empty($attributes)) $aVoid .= _stringify_attributes($attributes);

        if (empty($aVoid)) return "<a href=\"javascript:void(0);\">{$title}</a >";
        return "<a href=\"javascript:void(0);\" " . trim($aVoid) . ">{$title}</a >";
    }
}

if (!function_exists('removePassedArrayKeys')) {
    /**
     * @param array $array
     * @param array $keysToRemove
     * @return array
     */
    function removePassedArrayKeys(array $array, array $keysToRemove)
    {
        if (!$array) return $array;
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = removePassedArrayKeys($value, $keysToRemove);
                $array[$key] = $value;
                continue;
            }
            if (in_array($key, $keysToRemove)) unset($array[$key]);
        }
        return $array;
    }
}

if (!function_exists('allowPassedArrayKeys')) {
    /**
     * @param array $array
     * @param array $keysToAllow
     * @return array
     */
    function allowPassedArrayKeys(array $array, array $keysToAllow)
    {
        if (!$array) return $array;
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = allowPassedArrayKeys($value, $keysToAllow);
                $array[$key] = $value;
                continue;
            }
            if (!in_array($key, $keysToAllow)) unset($array[$key]);
        }
        return $array;
    }
}

if (!function_exists('isJson')) {
    /**
     * @param $string
     * @return array|mixed
     */
    function isJson($string)
    {
        if (!is_string($string)) return false;
        $result = json_decode($string, true);
        if (json_last_error()) return false;
        return $result;
    }
}

if (!function_exists('removeArrayKeys')) {
    /**
     * @param array $array
     * @return array
     */
    function removeArrayKeys(array $array)
    {
        $valueArray = [];
        foreach ($array as $Key => $value) {
            $valueArray[] = $value;
        }
        return $valueArray;
    }
}

if (!function_exists('isSingleItemArray')) {
    /**
     * @param array $array
     * @return bool
     */
    function isSingleItemArray(array $array)
    {
        if (count($array) > 1) {
            return false;
        }
        return true;
    }
}

if (!function_exists('formatExceptionAsDataArray')) {
    /**
     * @param array $array
     * @return array|bool|mixed
     */
    function formatExceptionAsDataArray($array)
    {
        if (empty($array)) return $array;
        $array = removeArrayKeys($array);
        if (isSingleItemArray($array)) {
            return $array[0];
        }
        return $array;
    }
}

if (!function_exists('typeCast')) {
    /**
     * @param array $array
     * @return array|bool
     */
    function typeCast($array)
    {
        if (!is_array($array)) return $array;
        return array_map(function ($element) {
            if (is_numeric($element) && !strpos($element, '.')) {
                $length = strlen((string)$element);
                return ($length > 9) ? floatval($element) : (int)$element;
            }
            return $element;
        }, $array);
    }
}

if (!function_exists('omitNullKeys')) {
    /**
     * @param array $data
     * @param bool $trimming
     * @return array|mixed
     */
    function omitNullKeys(array $data, $trimming = false)
    {
        if (is_bool($trimming) && $trimming === true) {
            $data = trimming($data);
        }
        if (is_null($data) && empty($data)) return false;
        foreach ($data as $key => $value) {
            if (is_array($data[$key])) {
                $data[$key] = omitNullKeys($data[$key]);
            } else {
                if (!is_bool($data[$key]) && !is_numeric($data[$key]) && (is_null($data[$key]) || $data[$key] == null || strtolower($data[$key]) == strtolower('NULL') || $data[$key] == '')) {
                    unset($data[$key]);
                }
            }
        }
        return $data;
    }
}

if (!function_exists('age')) {
    /**
     * @param $date
     * @return bool|int
     */
    function age($date)
    {
        if (!isValidDate($date)) return false;
        return (date('Y') - date('Y', strtotime($date)));
    }
}

if (!function_exists('isValidTimeStamp')) {
    /**
     * @param $timestamp
     * @return bool
     */
    function isValidTimeStamp($timestamp)
    {
        $format = 'Y-m-d H:i:s';
        $d = DateTime::createFromFormat($format, $timestamp);
        return $d && $d->format($format) == $timestamp;
    }
}

if (!function_exists('isValidDate')) {
    /**
     * @param $date
     * @return bool
     */
    function isValidDate($date)
    {
        $format = 'Y-m-d';
        $d = DateTime::createFromFormat($format, $date);
        return ($d && $d->format($format) == $date);
    }
}

if (!function_exists('validVariableName')) {
    /**
     * @param $spacedString
     * @return string
     */
    function validVariableName($spacedString)
    {
        return strtolower(str_replace(' ', '_', strtolower($spacedString)));
    }
}

if (!function_exists('variableToStr')) {
    /**
     * @param $variable
     * @return string
     */
    function variableToStr($variable)
    {
        return ucfirst(strtolower(str_replace('_', ' ', strtolower($variable))));
    }
}

if (!function_exists('isAjaxRequest')) {
    /**
     * @return bool
     */
    function isAjaxRequest()
    {
        $header = get_instance()->input->request_headers();
        return isset($header['X-Requested-With']) && $header['X-Requested-With'] === 'XMLHttpRequest';
    }
}

if (!function_exists('isAppRequest')) {
    /**
     * @return bool
     */
    function isAppRequest()
    {
        $header = get_instance()->input->request_headers();
        return isset($header['Response-Type']) && $header['Response-Type'] === 'Json';
    }
}

if (!function_exists('booleanIntValue')) {
    /**
     * @param integer|$int
     * @return bool
     */
    function booleanIntValue($int)
    {
        switch ($int) {
            case 0:
                return (bool)$int = false;
            case 1:
                return (bool)$int = true;
            default:
                return false;
        }
    }
}
if (!function_exists('intBooleanValue')) {
    /**
     * @param bool|$boolean
     * @return bool|int
     */
    function intBooleanValue($boolean)
    {
        switch ($boolean) {
            case false:
                return (int)$boolean = 0;
            case true:
                return (int)$boolean = 1;
            default:
                return false;
        }
    }
}

if (!function_exists('validImage')) {
    /**
     * @param string $name
     * @return bool|string
     */
    function validImage($name)
    {
        if (empty($_FILES[$name]['name'])) return false;
        return true;
    }
}

if (!function_exists('doUpload')) {
    /**
     * @param $image
     * @param int $no
     * @return bool|string
     */
    function doUpload($image, $no = 1)
    {
        if (empty($_FILES[$image]['name'])) return false;
        $name = round(microtime(true) * $no) . '.' . pathinfo($_FILES[$image]['name'], PATHINFO_EXTENSION);
        if (!move_uploaded_file($_FILES[$image]['tmp_name'], UPLOAD_PATH . $name)) return false;
        return $name;
    }
}

if (!function_exists('uriStringIS')) {
    /**
     * @param int $segment
     * @return mixed|string
     */
    function uriStringIS($segment = null)
    {
        if (is_numeric($segment)) {
            return get_instance()->uri->segment($segment);
        }
        return get_instance()->uri->uri_string();
    }
}

if (!function_exists('input')) {
    /**
     * @param $key
     * @return string
     */
    function input($key)
    {
        return trim(get_instance()->input->get_post($key, true));
    }
}

if (!function_exists('today')) {
    /**
     * @return false|string
     */
    function today()
    {
        return date('Y-m-d');
    }
}

if (!function_exists('jsonDie')) {
    /**
     * @param $data
     * @param int $http_status
     */
    function jsonDie($data, $http_status = 200)
    {
        header('Content-Type: application/json', true, $http_status);
        $data = (!is_array($data)) ? ['data' => $data] : $data;
        die(json_encode($data));
    }
}

if (!function_exists('isInt')) {
    /**
     * @param $str
     * @return bool|float|int
     */
    function isInt($str)
    {
        $int = is_numeric($str) && floatval($str) - $str === 0;
        if (!$int) return false;
        $length = strlen((string)$str);
        return ($length > 9) ? floatval($str) : (int)$str;
    }
}

if (!function_exists('isPost')) {
    /**
     * @return bool
     */
    function isPost()
    {
        return isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) === "post";
    }
}
if (!function_exists('isGet')) {
    /**
     * @return bool
     */
    function isGet()
    {
        return isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) === "get";
    }
}

if (!function_exists('log_activity')) {
    /**
     * @param $data
     * @param string $tag
     */
    function log_activity($data, $tag = 'data')
    {
        $tag_name = strtolower(str_replace(' ', '_', strtolower($tag)));
        log_message('testing', print_r([$tag_name => $data], true));
    }
}

if (!function_exists('getStylesheet')) {
    function getStylesheet()
    {
        $style_sheet = get_instance()->style_sheet;
        if (empty($style_sheet)) return null;
        foreach ($style_sheet as $index => $item) {
            echo $item . PHP_EOL;
        }
    }
}
if (!function_exists('getJavascript')) {
    function getJavascript()
    {
        $java_script = get_instance()->java_script;
        if (empty($java_script)) return null;
        foreach ($java_script as $index => $item) {
            echo $item . PHP_EOL;
        }
    }
}
if (!function_exists('getMetaData')) {
    function getMetaData()
    {
        $meta_data = get_instance()->meta_data;
        if (empty($meta_data)) return null;
        foreach ($meta_data as $index => $item) {
            echo $item . PHP_EOL;
        }
    }
}

if (!function_exists('blank')) {
    /**
     * @param $value
     * @return bool
     */
    function blank($value)
    {
        if (is_null($value)) return true;
        if (is_string($value)) return trim($value) === '';
        if (is_numeric($value) && (int)$value === 0) return true;
        if (is_bool($value) && (bool)$value === false) return true;
        if ($value instanceof Countable) return count($value) === 0;
        return empty($value);
    }
}

if (!function_exists('compress')) {
    /**
     * @param $source
     * @param $destination
     * @param $quality
     * @return mixed
     */
    function compress($source, $destination, $quality)
    {
        $image = null;
        $info = getimagesize($source);

        switch ($info['mime']) :
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source);
                break;
            case 'image/jpg':
                $image = imagecreatefromjpeg($source);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($source);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source);
                break;
        endswitch;
        if (empty($image)) return $source;

        imagejpeg($image, $destination, $quality);
        return $destination;
    }
}

/*if (!function_exists('app')) {
    function app($abstract, $parameters = [])
    {
        $obj = null;
        $container = new Container();
        try {
            $obj = $container->get($abstract, $parameters);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
        return $obj;
    }
}*/