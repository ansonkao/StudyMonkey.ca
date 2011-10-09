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
    private $type;

    private static function basic_constructor( $message, $type )
    {
        $new_notification = new Notification();
        $new_notification->message = $message;
        $new_notification->type = $type;
        return $new_notification;
    }

    public static function error( $message )
    {
        return self::basic_constructor( $message, 'ERROR' );
    }

    public static function success( $message = NULL )
    {
        return self::basic_constructor( $message, 'SUCCESS' );
    }

    public static function redirect( $message )
    {
        return self::basic_constructor( $message, 'REDIRECT' );
    }

    public static function info( $message )
    {
        return self::basic_constructor( $message, 'INFO' );
    }

    public function is_success()
    {
        return $this->type == 'SUCCESS';
    }

    public function to_AJAX()
    {
        return "{$this->type} {$this->message}";
    }

}