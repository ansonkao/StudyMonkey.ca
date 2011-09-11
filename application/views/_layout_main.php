<?php
/**
 * LAYOUT - MAIN
 *
 * This is the main layout of the website.  This template holds together
 * almost every page in the site.
 */

$navigation_items = array();
$navigation_items['Home']       = "/";
//$navigation_items['Schools']    = "/schools";
$navigation_items['Courses']    = "/courses";
$navigation_items['Professors'] = "/professors";
$navigation_items['Learn more'] = "/learn-more";

function str2uri($string)
{
    return str_replace(" ", "-", strtolower($string));
}

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo empty($page_title)? "" : "{$page_title} - "; ?>StudyMonkey.ca</title>
    <meta charset="utf-8">
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <link href="/css/layout.css" rel="stylesheet" type="text/css" />
    <link rel="icon" type="image/x-icon" href="/favicon.ico"/>
</head>
<body>
    <div id="header">
        <div id="header_content">
            <img id="header_logo" src="/image/logo_header.png" alt="StudyMonkey.ca" />
            <input id="header_button" type="text" value="Search..." />
            <div id="navigation">
<!------------------------------ START WIDGET --------------------------------->
<?php $this->load->view('_calendar');?>
<!------------------------------- END WIDGET ---------------------------------->
                <ul>
<?php foreach ($navigation_items as $title => $link) { ?>
                    <li>
                        <a class="<?php echo ($page_title == $title)? "selected" : "normal"; ?>" href="<?=$link?>">
                            <img src="/image/<?php echo str2uri($title); ?>.png" />
                            <span><?=$title?></span>
                        </a>
                    </li>
<?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <div id="wrapper">
        <div id="content">
            <div id="content_header" style="line-height: 0.8em;">
                <span>
                    BROWSE COURSES
                </span>
                <br/>
                <span style="font: bold 12px tahoma, arial;">
                    Home &#187; University of Waterloo &#187; PSYCH253
                </span>
            </div>
            <div id="content_body">
<!------------------------------ START CONTENT -------------------------------->
<?php echo $page_content . "\n";?>
<!------------------------------- END CONTENT --------------------------------->
            </div>
        </div>
        <div id="wrapper_end"></div>
    </div>
    <div id="footer">
        <img id="mascot" src="/image/mascot.png" />
        <ul id="footer_menu">
            <li><a href="/">Home</a></li>
            <li><a href="/">About</a></li>
            <li><a href="/">Terms</a></li>
            <li><a href="/">Privacy</a></li>
            <li><a href="/">Contact</a></li>
        </ul>
        <div id="footer_copyright">
            Copyright &copy; 2011
        </div>
    </div>
</body>
</html>
<?php

/* End of file _layout_main.php */
/* Location: ./application/views/_layout_main.php */