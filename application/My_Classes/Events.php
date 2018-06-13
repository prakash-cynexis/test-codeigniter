<?php

namespace MYClasses;

use MYClasses\Providers\EmailProvider;
use MYClasses\Providers\EmailTemplateProvider;
use MYClasses\Providers\NotificationProvider;

class Events
{
    const WELCOME_EMAIL = 'WELCOME_EMAIL';

    private static $CI;
    private static $event_type;
    private static $send_by = [];
    private static $email;
    private static $notification;

    private static function initialize()
    {
        self::$CI = &get_instance();
    }

    public static function emit($data, $event_type, array $send_by)
    {
        self::$event_type = $event_type;
        self::$send_by = $send_by;
        self::$email = (in_array('Email', $send_by)) ? true : false;
        self::$notification = (in_array('Notification', $send_by)) ? true : false;
        self::initialize();
        $result = [];

        foreach (self::events() as $event => $message):
            if ($event != self::$event_type) continue;

            if (self::$email) {
                $data['subject'] = COMPANY_NAME . ': ' . $message;
                $result['email'] = self::emailSend($data);
            }
            if (self::$notification) {
                $data['message'] = $message;
                $result['notification'] = self::notificationSend($data);
            }
        endforeach;

        return $result;
    }

    private static function emailSend($data)
    {
        $emailTemplate = new EmailTemplateProvider($data['template_name']);
        $setData['name'] = $data['user_name'];
        if (!empty($data['action_url'])) $setData['action_url'] = $data['action_url'];

        $emailTemplate->setData($setData);
        $content = $emailTemplate->output();

        $email = new EmailProvider();
        $email->to($data['email']);
        $email->subject($data['subject']);
        $email->html($content);
        $done = $email->send();

        if (!$done) {
            log_activity([
                'email' => $data['email'],
                'message' => 'Unable to send email'
            ], 'emailSend');
        }
        return $done;
    }

    private static function notificationSend($data)
    {
        $notifyContent = new NotificationProvider();
        $notifyContent->device_type($data['device_type']);
        $notifyContent->device_token($data['device_token']);
        $notifyContent->message($data['message']);
        $done = $notifyContent->send();

        if (!$done) {
            log_activity([
                'device_type' => $data['device_type'],
                'device_token' => $data['device_token'],
                'message' => 'Unable notify user'
            ], 'notificationSend');
        }
        return $done;
    }

    private static function events()
    {
        return [
            'WELCOME_EMAIL' => 'Welcome User.',
        ];
    }
}