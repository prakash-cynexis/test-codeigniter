<?php

namespace MYClasses;

use MYClasses\Providers\EmailProvider;
use MYClasses\Providers\EmailTemplateProvider;
use MYClasses\Providers\NotificationProvider;

class Events
{
    const WELCOME_EMAIL = 'WELCOME_EMAIL';

    private static $CI;
    private static $email;
    private static $send_by = [];
    private static $eventType;
    private static $usersToNotify;
    private static $notification;
    private static $notificationData;

    private static function initialize()
    {
        self::$CI = &get_instance();
        self::$CI->load->model("User_model");
        self::$usersToNotify = self::$notificationData = NULL;
        self::$eventType = NULL;
    }

    public static function emit($data, $eventType, array $send_by)
    {
        $result = [];
        if (!$data) response()->error('data not found.');
        if (!$eventType) response()->error('A valid event type must be passed e.g WELCOME_EMAIL..');
        self::initialize();
        self::$eventType = $eventType;

        self::$send_by = $send_by;
        self::$email = (in_array('Email', $send_by)) ? true : false;
        self::$notification = (in_array('Notification', $send_by)) ? true : false;

        if (self::$email) {
            $data['subject'] = variableToStr($eventType);
            $data['template_name'] = strtolower($eventType) . '.php';
            $result['email'] = self::emailSend($data);
        }

        if (self::$notification) {
            switch ($eventType):
                case self::WELCOME_EMAIL:
                    self::setNotificationData('Welcome email.', $data['id']);
                    self::$usersToNotify = self::$CI->User_model->get_many_by(['id' => $data['id']]);
                    break;
            endswitch;

            foreach (self::$usersToNotify as $i => $user):
                if (is_null($user['id'])) continue;
                if (is_null($user['device_type']) || is_null($user['device_token'])) log_activity('Unable notify user with ID ' . $user['id'] . '. device_token/device_type is NULL. For case ID: ' . $data['id'], 'Unable notify');
                $result['notification'][$i] = self::notify($user, self::$notificationData);
            endforeach;
        }

        return !empty($result) ? $result : false;
    }

    private static function notify($user, $data)
    {
        $notifyContent = new NotificationProvider();
        $notifyContent->device_type($user['device_type']);
        $notifyContent->device_token($user['device_token']);
        $notifyContent->message($data);
        $done = $notifyContent->send();
        return $done;
    }

    private static function emailSend($data)
    {
        if (empty($data['template_name'])) response()->error('Email Template name can not be null.');
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
            ], 'send email');
        }
        return $done;
    }

    private static function setNotificationData($message, $data)
    {
        if (!is_array($data)) $data = ['key' => $data];
        self::$notificationData['type'] = self::$eventType;
        self::$notificationData['message'] = $message;
        self::$notificationData = array_merge(self::$notificationData, $data);
        log_activity(self::$notificationData, 'notification data'); // test only, remove for production
        return self::$notificationData;
    }
}