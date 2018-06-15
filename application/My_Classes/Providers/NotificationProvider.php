<?php

namespace MYClasses\Providers;

class NotificationProvider
{
    private static $FCM_PATH = 'https://fcm.googleapis.com/fcm/send';
    private $message;
    private $device_type;
    private $device_token;
    private static $iOS_SERVER_KEY = '';
    private static $ANDROID_SERVER_KEY = '';

    public function send()
    {
        switch ($this->device_type):
            case 'iOS':
                return self::iOS($this->device_token, $this->message);
            case 'android':
                return self::android($this->device_token, $this->message);
            default:
                return false;
        endswitch;
    }

    public function message($message)
    {
        $this->message = $message;
        return $this;
    }

    public function device_type($device_type)
    {
        $this->device_type = $device_type;
        return $this;
    }

    public function device_token($device_token)
    {
        $this->device_token = $device_token;
        return $this;
    }

    private static function iOS($token, $message)
    {
        $sent = false;
        $headers = [
            'Authorization:key=' . self::$iOS_SERVER_KEY,
            'Content-Type:application/json'
        ];
        $fields = ['to' => trim($token), 'priority' => 'high', 'notification' => self::iPhoneFormat($message)];
        $json_encode_fields = json_encode($fields);
        log_activity($json_encode_fields, 'ios json encode fields');
        try {
            // Open connection
            $ch = curl_init();
            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, self::$FCM_PATH);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_encode_fields);

            $result = curl_exec($ch); // Execute post
            if ($result === FALSE) return false;

            curl_close($ch); // Close connection
            $result = isJson($result);
            if (isset($result['failure']) && $result['failure'] == '1') {
                log_activity($result, 'ios notification not send');
                return false;
            }
            $sent = !empty($result['success']);
        } catch (\Exception $exception) {
            log_activity($exception->getMessage(), 'iphone Notification Error:-');
            return false;
        } finally {
            if (!$sent) return false;
        }
        return true;
    }

    private static function android($token, $message)
    {
        $sent = false;
        $headers = [
            'Authorization:key=' . self::$ANDROID_SERVER_KEY,
            'Content-Type:application/json'
        ];

        $fields['data'] = $message;
        $fields['registration_ids'] = [trim($token)];
        $json_encode_fields = json_encode($fields);
        log_activity($json_encode_fields, 'android json encode fields');
        try {
            // Open connection
            $ch = curl_init();
            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, self::$FCM_PATH);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_encode_fields);

            $result = curl_exec($ch); // Execute post
            if ($result === FALSE) return false;

            curl_close($ch); // Close connection
            $result = isJson($result);
            if (isset($result['failure']) && $result['failure'] == '1') {
                log_activity($result, 'android notification not send');
                return false;
            }
            $sent = !empty($result['success']);
        } catch (\Exception $exception) {
            log_activity($exception->getMessage(), 'Android Notification Error:-');
            return false;
        } finally {
            if (!$sent) return false;
        }
        return true;
    }

    private static function iPhoneFormat($message)
    {
        $iPhoneFormatMessage = array_merge($message, ["body" => 'Grab-A-Gram']);
        return $iPhoneFormatMessage;
    }
}