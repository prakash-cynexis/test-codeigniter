<?php

class MY_Exceptions extends CI_Exceptions {

    function show_error($heading, $message, $template = 'error_general', $status_code = 500) {
        log_message('error', print_r($message, TRUE));
        if (!is_array($message)) $message = [$message];
        header('Content-Type: application/json', true, $status_code);
        exit(json_encode(array(
            'error' => true,
            'message' => $message,
        )));
    }
}