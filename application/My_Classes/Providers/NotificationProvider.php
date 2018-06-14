<?php

namespace MYClasses\Providers;

class NotificationProvider
{
    private static $FCM_PATH = 'https://fcm.googleapis.com/fcm/send';
    private $message;
    private $device_type;
    private $device_token;
    private static $API_SERVER_KEY = '';
    private static $iOS_SERVER_KEY = '';

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
        $fields = ['to' => $token, 'priority' => 'high', 'notification' => self::iPhoneFormat($message)];
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
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            $result = curl_exec($ch); // Execute post
            if ($result === FALSE) return false;

            curl_close($ch); // Close connection
            $result = isJson($result);
            if (!$result) return false;
            $sent = !empty($result['success']);
        } catch (\Exception $exception) {
            log_activity($exception->getMessage(), 'iOS Notification Error:-');
        } finally {
            if (!$sent) return false;
        }
        return true;
    }

    private static function android($token, $message)
    {
        $sent = false;
        $headers = [
            'Authorization:key=' . self::$API_SERVER_KEY,
            'Content-Type:application/json'
        ];

        $fields['data'] = $message;
        $fields['registration_ids'] = [$token];

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
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            $result = curl_exec($ch); // Execute post
            if ($result === FALSE) return false;

            curl_close($ch); // Close connection
            $result = isJson($result);
            if (!$result) return false;
            $sent = !empty($result['success']);
        } catch (\Exception $exception) {
            log_activity($exception->getMessage(), 'Android Notification Error:-');
        } finally {
            if (!$sent) return false;
        }
        return true;
    }

    private static function iPhoneFormat($message)
    {
        return [
            'data' => $message,
            "body" => $message['subject'],
            'sound' => "default",
            "badge" => 5,
            'color' => "#203E78"
        ];
    }
}