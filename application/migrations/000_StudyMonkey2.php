<?php defined('BASEPATH') OR exit('No direct script access allowed');

    "

    ############################################################################
    ### Make school names indexable for URL routing, clean up, add new schools
    ############################################################################

    ALTER TABLE `school` ADD INDEX ( `full_name` ) ;
    ALTER TABLE `school`
        ADD `province` VARCHAR( 25 ) NOT NULL DEFAULT '' AFTER `full_name`,
        DROP `description`,
        DROP `total_courses`,
        DROP `total_professors`,
        DROP `total_users`,
        DROP `total_notes`,
        DROP `total_course_professor_reviews`,
        DROP `domain_name`,
        DROP `domain_suffix`;
    UPDATE `2011_studymonkey`.`school` SET `province` = 'Ontario';
    INSERT INTO `school` (`id`, `date_modified`, `date_created`, `full_name`, `province`) VALUES
    (8, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'University of Alberta', 'Alberta'),
    (9, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'University of Calgary', 'Alberta'),
    (10, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'University of Lethbridge', 'Alberta'),
    (11, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'Simon Fraser University', 'British Columbia'),
    (12, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'University of British Columbia', 'British Columbia'),
    (13, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'University of Victoria', 'British Columbia'),
    (14, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'University of Manitoba', 'Manitoba'),
    (15, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'University of Winnipeg', 'Manitoba'),
    (16, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'Mount Allison University', 'New Brunswick'),
    (17, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'University of New Brunswick', 'New Brunswick'),
    (18, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'Dalhousie University', 'Nova Scotia'),
    (19, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'Brock University', 'Ontario'),
    (20, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'Carleton University', 'Ontario'),
    (21, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'McMaster University', 'Ontario'),
    (22, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'Queen''s University', 'Ontario'),
    (23, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'University of Ontario Institute of Technology', 'Ontario'),
    (24, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'University of Windsor', 'Ontario'),
    (25, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'Bishop''s University', 'Quebec'),
    (26, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'Concordia University', 'Quebec'),
    (27, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'McGill University', 'Quebec'),
    (28, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'University of Regina', 'Saskatchewan'),
    (29, '0000-00-00 00:00:00', '2011-10-15 21:30:00', 'University of Saskatchewan', 'Saskatchewan');



    ############################################################################
    ### Drop tables we no longer need
    ############################################################################

    DROP TABLE `user_password_reset`;
    DROP TABLE `transaction_paypal`;
    DROP TABLE `course_enrollment`;
    DROP TABLE `logging`;
    DROP TABLE `subscription`;
    DROP TABLE `email_history`;
    DROP TABLE `transaction`;
    DROP TABLE `transaction_option`;
    DROP TABLE `transaction_message`;
    DROP TABLE `note`;
    DROP TABLE `note_review`;
    DROP TABLE `program`;
    DROP TABLE `referral_method`;
    ALTER TABLE `course`
        DROP `total_professors`,
        DROP `total_notes`;
    ALTER TABLE `professor`
        DROP `total_courses`;
    ALTER TABLE `course_professor`
        DROP `total_reviews`;

    ############################################################################
    ### Move username from user to reviews
    ############################################################################

    ALTER TABLE `course_professor_review` ADD `username` VARCHAR( 25 ) NULL DEFAULT NULL AFTER `user_id`;

    UPDATE `course_professor_review` review, `user` user
    SET review.`username` = user.`username`
    WHERE review.`user_id` = user.`id`
        AND review.`anonymous` <> 1;

    UPDATE `course_professor_review` review, `user` user
    SET review.`username` = 'anonymous'
    WHERE review.`anonymous` = 1;

    ############################################################################
    ### Move Gender from user to reviews
    ############################################################################

    ALTER TABLE `course_professor_review` ADD `gender` ENUM( 'M', 'F' ) NULL DEFAULT NULL AFTER `username`;

    UPDATE `course_professor_review` review, `user` user
    SET review.`gender` = user.`gender`
    WHERE review.`user_id` = user.`id`;

    ############################################################################
    ### Drop user stuff now that it's useless
    ############################################################################

    ALTER TABLE `course_professor_review`
        DROP `user_id`,
        DROP `anonymous`;
    DROP TABLE `user`;

    ############################################################################
    ### Normalize professor names, course codes and school names to uri-safe
    ############################################################################

    ALTER TABLE `school`
        ADD `uri` VARCHAR( 50 ) NULL DEFAULT NULL AFTER `full_name`;

    UPDATE `school`
    SET `uri` = LOWER( REPLACE( REPLACE( `full_name`, '\'', '' ), ' ' , '-' ) );

    ALTER TABLE `professor`
        ADD `uri` VARCHAR( 50 ) NULL DEFAULT NULL AFTER `last_name`;

    UPDATE `professor`
    SET `uri` = LOWER( CONCAT( REPLACE( REPLACE( TRIM( `first_name` ), '\'', '' ), ' ' , '-' ), '_', REPLACE( REPLACE( TRIM( `last_name` ), '\'', '' ), ' ' , '-' ) ) );

    ";

