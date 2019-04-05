<?php

namespace MyClasses\Providers;

class GCMProvider implements NotificationInterface {

    private $GCM_PATH = 'https://android.googleapis.com/gcm/send';
    private $message;
    private $device_type;
    private $device_token;
    private $AUTHORIZATION_KEY = '';

    public function send() {
        switch ($this->device_type):
            case 'android':
                return self::send_notification($this->device_token, $this->message);
            default:
                return false;
        endswitch;
    }

    /**
     * Sending Push Notification
     * @param $registration_ids
     * @param $message
     * @return bool
     */
    public function send_notification($registration_ids, $message) {
        $sent = false;
        $fields['data'] = $message;
        $fields['registration_ids'] = [$registration_ids];

        $headers = [
            'Authorization: key=' . $this->AUTHORIZATION_KEY,
            'Content-Type: application/json',
        ];
        try {
            // Open connection
            $ch = curl_init();

            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $this->GCM_PATH);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Disabling SSL Certificate support temporarily
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            // Execute post
            $result = curl_exec($ch);
            curl_close($ch);// Close connection

            $result = isJson($result);
            if (isset($result['failure']) && $result['failure'] == '1') {
                log_activity($result, 'gcm android notification not send');
                return false;
            }
            $sent = !empty($result['success']);
        } catch (\Exception $exception) {
            log_activity($exception->getMessage(), 'gcm android Notification Error:-');
            return false;
        } finally {
            if (!$sent) return false;
        }
        return true;
    }

    public function message($message) {
        $this->message = $message;
        return $this;
    }

    public function device_type($device_type) {
        $this->device_type = $device_type;
        return $this;
    }

    public function device_token($device_token) {
        $this->device_token = $device_token;
        return $this;
    }
}