<?php defined('BASEPATH') OR exit('No direct script access allowed');

    "

    ### Make school.full_name indexable for URL re-routing
	ALTER TABLE `school` ADD INDEX ( `full_name` ) ;

    ";

