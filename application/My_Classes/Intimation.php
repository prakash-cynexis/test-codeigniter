<?php

namespace MyClasses;

use MyClasses\Providers\EmailProvider;
use MyClasses\Providers\EmailTemplateProvider;
use MyClasses\Providers\FCMProvider;
use MyClasses\Providers\Notification;

class Intimation {

    const WELCOME_EMAIL = 'WELCOME_EMAIL';
    private static $CI;
    private static $email;
    private static $send_by = [];
    private static $eventType;
    private static $usersToNotify;
    private static $notification;
    private static $template_name;
    private static $notificationData;

    public static function emit($data, $eventType, array $send_by) {
        $result = [];
        if (!$data) response()->error('data not found.');
        if (!$eventType) response()->error('A valid event type must be passed e.g WELCOME_EMAIL..');
        self::initialize();
        self::$eventType = $eventType;

        self::$send_by = $send_by;
        self::$email = (in_array('email', array_map('strtolower', $send_by))) ? true : false;
        self::$notification = (in_array('notification', array_map('strtolower', $send_by))) ? true : false;

        if (self::$email) {
            self::$template_name = strtolower($eventType) . '.php';
            $data['subject'] = variableToStr($eventType);
            $result['email'] = self::emailSend($data);
        }

        if (self::$notification) {
            switch ($eventType):
                case self::WELCOME_EMAIL:
                    self::setNotificationData('Welcome email.', $data['id']);
                    self::$usersToNotify = self::$CI->User_model->get_many_by(['id' => $data['id']]);
                    break;
            endswitch;

            if (empty(self::$usersToNotify)) {
                log_activity(self::$usersToNotify, 'users To Notify data');
                return $result['notification'] = false;
            }
            foreach (self::$usersToNotify as $i => $user):
                if (is_null($user['id'])) continue;
                if (is_null($user['device_type']) || is_null($user['device_token'])) log_activity('Unable notify user with ID ' . $user['id'] . '. device_token/device_type is NULL. For case ID: ' . $data['id'], 'Unable notify');
                $result['notification'][$i] = self::notify($user, self::$notificationData);
            endforeach;
        }

        return $result;
    }

    private static function initialize() {
        self::$CI = &get_instance();
        self::$CI->load->model("User_model");
        self::$usersToNotify = self::$notificationData = [];
        self::$eventType = self::$template_name = null;
    }

    private static function emailSend($data) {
        if (empty(self::$template_name)) response()->error('Email Template name can not be null.');
        $emailTemplate = new EmailTemplateProvider(self::$template_name);
        $data['name'] = $data['user_name'];

        $emailTemplate->setData($data);
        $content = $emailTemplate->output();

        $email = new EmailProvider();
        $email->to($data['email']);
        $email->subject($data['subject']);
        $email->html($content);
        $done = $email->send();
        return $done;
    }

    private static function setNotificationData($message, $data) {
        if (!is_array($data)) $data = ['id' => $data];
        self::$notificationData['type'] = self::$eventType;
        self::$notificationData['message'] = $message;
        self::$notificationData = array_merge(self::$notificationData, $data);
        log_activity(self::$notificationData, 'notification data'); // test only, remove for production
        return typeCast(self::$notificationData);
    }

    private static function notify($user, $data) {
        $notifyContent = new Notification(new FCMProvider());
        $notifyContent->device_type($user['device_type']);
        $notifyContent->device_token($user['device_token']);
        $notifyContent->message($data);
        $done = $notifyContent->send();
        return $done;
    }
}