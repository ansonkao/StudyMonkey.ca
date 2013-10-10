<?php
/**
 * EMAIL LIBRARY
 *
 * For PostMarkAPP - extending Znarkus' opensource class on github
 */

// POST MARK
require_once("../postmark-php/Postmark.php");

class Email extends Mail_Postmark
{
    public function __construct() {
        // Set the class to return exceptions via the send() function
        $this->debug(self::DEBUG_RETURN);
    }

    private function _from_account($type)
    {
        switch ($type)
        {
            case "confirmation":
                $this->from("confirmation@studymonkey.ca", "StudyMonkey");
                break;
            case "announcement":
                $this->from("announcement@studymonkey.ca", "StudyMonkey");
                break;
            case "notification":
                $this->from("notifications@studymonkey.ca", "StudyMonkey");
                break;
        }
    }

    /* This function accepts a string to be included as the message body
     * of an html email template.  The argument string is not processed,
     * and nl2br() should be performed BEFORE passing the argument into the
     * function if necessary
     */
    private static function _wrap_with_html_template($html_message_body)
    {
        return "<table cellspacing='0' cellpadding='0' width='600' style='width: 600px;'>
                    <tr>
                        <td align='left' valign='center' style='padding: 5px 10px;'>
                            <a href='http://www.studymonkey.ca/' style='border: 0px;'>
                                <img src='http://www.studymonkey.ca/_include/layout/image/email/logo_email_20101021.gif' style='border: 0px;' />
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td align='left' valign='center' style='padding: 5px 10px 0px; font-family: arial;'>
                            {$html_message_body}
                        </td>
                    </tr>
                </table>";
    }

    public static function contact_us($subject_topics, $subject, $name, $email, $message)
    {
        $new_email = new self();
        $new_email->tag("Contact Us Form");

        $new_email->_from_account("notification");
        $new_email->replyTo($email, $name);
        $new_email->to($subject."@studymonkey.ca", "Administrator");
        $new_email->subject($subject_topics[$subject] . " (" . $name . ")");
        $new_message = $message . "\n\nRespond to {$name} at {$email}";

        $new_email->messageHtml(self::_wrap_with_html_template(nl2br($new_message)));
        $new_email->messageHtml(nl2br($new_message));
        $new_email->messagePlain(strip_tags($new_message));

        return $new_email->send();
    }
}