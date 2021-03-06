<?php

namespace MyClasses\Http;

class Response {

// Note: Only the widely used HTTP status codes are documented

    // Informational

    const HTTP_CONTINUE = 100;
    const HTTP_SWITCHING_PROTOCOLS = 101;
    const HTTP_PROCESSING = 102; // RFC2518
    // Success

    /**
     * The request has succeeded
     */
    const HTTP_OK = 200;
    /**
     * The server successfully created a new resource
     */
    const HTTP_CREATED = 201;
    const HTTP_ACCEPTED = 202;
    const HTTP_NON_AUTHORITATIVE_INFORMATION = 203;
    /**
     * The server successfully processed the request, though no content is returned
     */
    const HTTP_NO_CONTENT = 204;
    const HTTP_RESET_CONTENT = 205;
    const HTTP_PARTIAL_CONTENT = 206;
    const HTTP_MULTI_STATUS = 207; // RFC4918
    const HTTP_ALREADY_REPORTED = 208; // RFC5842
    const HTTP_IM_USED = 226; // RFC3229
    // Redirection

    const HTTP_MULTIPLE_CHOICES = 300;
    const HTTP_MOVED_PERMANENTLY = 301;
    const HTTP_FOUND = 302;
    const HTTP_SEE_OTHER = 303;
    /**
     * The resource has not been modified since the last request
     */
    const HTTP_NOT_MODIFIED = 304;
    const HTTP_USE_PROXY = 305;
    const HTTP_RESERVED = 306;
    const HTTP_TEMPORARY_REDIRECT = 307;
    const HTTP_PERMANENTLY_REDIRECT = 308; // RFC7238
    // Client Error

    /**
     * The request cannot be fulfilled due to multiple errors
     */
    const HTTP_BAD_REQUEST = 400;
    /**
     * The user is unauthorized to access the requested resource
     */
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_PAYMENT_REQUIRED = 402;
    /**
     * The requested resource is unavailable at this present time
     */
    const HTTP_FORBIDDEN = 403;
    /**
     * The requested resource could not be found
     *
     * Note: This is sometimes used to mask if there was an UNAUTHORIZED (401) or
     * FORBIDDEN (403) error, for security reasons
     */
    const HTTP_NOT_FOUND = 404;
    /**
     * The request method is not supported by the following resource
     */
    const HTTP_METHOD_NOT_ALLOWED = 405;
    /**
     * The request was not acceptable
     */
    const HTTP_NOT_ACCEPTABLE = 406;
    const HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
    const HTTP_REQUEST_TIMEOUT = 408;
    /**
     * The request could not be completed due to a conflict with the current state
     * of the resource
     */
    const HTTP_CONFLICT = 409;
    const HTTP_GONE = 410;
    const HTTP_LENGTH_REQUIRED = 411;
    const HTTP_PRECONDITION_FAILED = 412;
    const HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
    const HTTP_REQUEST_URI_TOO_LONG = 414;
    const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
    const HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    const HTTP_EXPECTATION_FAILED = 417;
    const HTTP_I_AM_A_TEAPOT = 418; // RFC2324
    const HTTP_UNPROCESSABLE_ENTITY = 422; // RFC4918
    const HTTP_LOCKED = 423; // RFC4918
    const HTTP_FAILED_DEPENDENCY = 424; // RFC4918
    const HTTP_RESERVED_FOR_WEBDAV_ADVANCED_COLLECTIONS_EXPIRED_PROPOSAL = 425; // RFC2817
    const HTTP_UPGRADE_REQUIRED = 426; // RFC2817
    const HTTP_PRECONDITION_REQUIRED = 428; // RFC6585
    const HTTP_TOO_MANY_REQUESTS = 429; // RFC6585
    const HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431; // RFC6585
    // Server Error

    /**
     * The server encountered an unexpected error
     *
     * Note: This is a generic error message when no specific message
     * is suitable
     */
    const HTTP_INTERNAL_SERVER_ERROR = 500;
    /**
     * The server does not recognise the request method
     */
    const HTTP_NOT_IMPLEMENTED = 501;
    const HTTP_BAD_GATEWAY = 502;
    const HTTP_SERVICE_UNAVAILABLE = 503;
    const HTTP_GATEWAY_TIMEOUT = 504;
    const HTTP_VERSION_NOT_SUPPORTED = 505;
    const HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506; // RFC2295
    const HTTP_INSUFFICIENT_STORAGE = 507; // RFC4918
    const HTTP_LOOP_DETECTED = 508; // RFC5842
    const HTTP_NOT_EXTENDED = 510; // RFC2774
    const HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;
    const SUCCESS = 'success';
    const ERROR = 'error';
    const DATA_NOT_FOUND = 'Data not found in our record.';
    const LOGIN_NOT_APPROVED = 'Your Application Is Pending. Thank you for registering for an account with ' . COMPANY_NAME . '! Thank you for submitting all of your information. We are still reviewing your application. We will notify you of our decision once we have reviewed your information.';
    const DEFAULT_ERROR = 'Can not allow null or empty field.';
    const INVALID_CREDENTIALS = 'Invalid credentials please verify them and retry.';
    const INVALID_ACTIVATION_KEY = 'Activation key not valid.';
    const AUTH_TOKEN_NOT_EXISTS = 'Auth-Token not Exists.';
    const AUTH_TOKEN_EMPTY = 'Auth-Token is empty.';
    const INVALID_AUTH_TOKEN = 'Auth-Token is invalid.';
    const INSERTION_FAILED = 'Data not inserted in our record.';
    const INSERTION_SUCCESS = 'Data successfully inserted in our record.';
    const UPDATING_FAILED = 'Data updating failed.';
    const UPDATING_SUCCESS = 'Data successfully update.';
    private static $_data = null;
    private static $_status = null;
    private static $_message = null;
    private static $_redirect = null;
    private static $_http_status = null;
    private static $_response = null;

    public function success($message, array $array = []) {
        $data = null;
        $redirect = true;
        $http_status = Response::HTTP_OK;
        if (isset($array['data'])) $data = $array['data'];
        if (isset($array['redirect'])) $redirect = $array['redirect'];
        if (isset($array['http_status'])) $http_status = $array['http_status'];
        self::CreateResponse(self::SUCCESS, $message, $data, $http_status, $redirect);
    }

    /**
     * @param $status
     * @param $message
     * @param null $data
     * @param null $http_status
     * @param bool $redirect
     */
    private static function CreateResponse($status, $message, $data = null, $http_status = null, $redirect = true) {
        if (!empty($data)) self::$_data = typeCasting($data);
        if (!empty($status)) self::$_status = $status;
        if (!empty($message)) self::$_message = $message;
        if (!empty($redirect)) self::$_redirect = $redirect;
        if (!empty($http_status)) self::$_http_status = $http_status;

        if (isAjaxRequest() || isAppRequest()) self::jsonSerialize();
        self::htmlSerialize();
    }

    private static function jsonSerialize() {
        if (self::$_status == self::SUCCESS) self::$_response = successResponse(self::$_message);
        if (self::$_status == self::ERROR) self::$_response = errorResponse(self::$_message);

        if (!is_null(self::$_data)) self::$_response['data'] = self::$_data;
        log_activity(['http_response_code' => self::$_http_status, 'data' => json_encode(self::$_response)], 'response');
        sleep(2);
        jsonDie(self::$_response, self::$_http_status);
    }

    private static function htmlSerialize() {
        if (self::$_status == self::SUCCESS) success(self::$_message, self::$_redirect, self::$_data);
        if (self::$_status == self::ERROR) error(self::$_message, self::$_redirect, self::$_data);
    }

    public function form_validation_exception(array $array) {
        $data = null;
        $redirect = true;
        if (isset($array['data']) && !isAppRequest()) $data = $array['data'];
        if (isset($array['redirect'])) $redirect = $array['redirect'];

        $this->error(formatExceptionAsDataArray(get_instance()->form_validation->error_array()), ['data' => $data, 'redirect' => $redirect]);
    }

    public function error($message, array $array = []) {
        $data = null;
        $redirect = true;
        $http_status = Response::HTTP_OK;
        if (isset($array['data'])) $data = $array['data'];
        if (isset($array['redirect'])) $redirect = $array['redirect'];
        if (isset($array['http_status'])) $http_status = $array['http_status'];
        self::CreateResponse(self::ERROR, $message, $data, $http_status, $redirect);
    }
}