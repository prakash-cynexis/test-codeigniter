<?php

namespace MYClasses\Providers;

class NotificationProvider implements NotifyInterface
{
    private static $FCM_PATH = 'https://fcm.googleapis.com/fcm/send';
    private static $API_SERVER_KEY = '';
    private static $iOS_SERVER_KEY = '';

    public function send(array $userInfo)
    {
        if($userInfo['device_type'] && $userInfo['device_token'])
        switch ($userInfo['device_type']):
            case 'iOS':
                return self::iOS($userInfo['device_token'], $userInfo['message']);
            case 'android':
                return self::android($userInfo['device_token'], $userInfo['message']);
            default:
                return false;
        endswitch;
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
            $sent = !blank($result['success']);
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
            $sent = !blank($result['success']);
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
            "body" => "Prakash Test Hello",
            'sound' => "default",
            "badge" => 5,
            'color' => "#203E78"
        ];
    }
}