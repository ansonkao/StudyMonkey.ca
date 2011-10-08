<?php defined('BASEPATH') OR exit('No direct script access allowed');

    "

    ### Make school.full_name indexable for URL re-routing
	ALTER TABLE `school` ADD INDEX ( `full_name` ) ;

    ### Drop tables we no longer need
    DROP TABLE `user_password_reset`;
    DROP TABLE `transaction_paypal`;
    DROP TABLE `course_enrollment`;
    DROP TABLE `logging`;
    DROP TABLE `subscription`;
    DROP TABLE `transaction_option`;
    DROP TABLE `transaction_message`;
    DROP TABLE `note`;
    DROP TABLE `note_review`;
    DROP TABLE `program`;
    DROP TABLE `referral_method`;


    ";

