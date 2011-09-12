<?php
/**
 * NOTIFICATION - HELPER CLASS
 *
 * Messages that pop up in the monkey's speech bubble.
 * You can specify the specific message string, as well as a message type.
 * Various types each having an associated icon and design.
 */

class Notification
{
    public $message;
    public $type;

    function error($error_message)
    {
        $new_notification = new Notification();
        $new_notification->message = $error_message;
        $new_notification->type = 'ERROR';
        return $new_notification;
    }

    function success($success_message)
    {
        $new_notification = new Notification();
        $new_notification->message = $success_message;
        $new_notification->type = 'SUCCESS';
        return $new_notification;
    }
}