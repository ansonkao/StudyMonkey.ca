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

    ### Move username from user to reviews
    ALTER TABLE `course_professor_review` ADD `username` VARCHAR( 25 ) NULL DEFAULT NULL AFTER `user_id`;

    UPDATE `course_professor_review` review, `user` user
    SET review.`username` = user.`username`
    WHERE review.`user_id` = user.`id`
        AND review.`anonymous` <> 1;

    UPDATE `course_professor_review` review, `user` user
    SET review.`username` = 'anonymous'
    WHERE review.`anonymous` = 1;

    ### Move Gender from user to reviews
    ALTER TABLE `course_professor_review` ADD `gender` ENUM( 'M', 'F' ) NULL DEFAULT NULL AFTER `username`;

    UPDATE `course_professor_review` review, `user` user
    SET review.`gender` = user.`gender`
    WHERE review.`user_id` = user.`id`

    ### Drop user stuff now that it's useless
    ALTER TABLE `course_professor_review`
        DROP `user_id`,
        DROP `anonymous`;
    DROP TABLE `user`;

    ";

