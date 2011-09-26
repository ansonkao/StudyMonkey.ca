<?php
/**
 * LAYOUT - MAIN
 *
 * This is the main layout of the website.  This template holds together
 * almost every page in the site.
 */

$navigation_items = array();
//$navigation_items['Home']       = "/";
//$navigation_items['Schools']    = "/schools";
$navigation_items['Courses']    = "/courses";
$navigation_items['Professors'] = "/professors";
$navigation_items['Learn more'] = "/contact";

$footer_items = array();
$footer_items['Home']       = "/";
//$footer_items['About']      = "/about";
$footer_items['Terms']      = "/terms";
$footer_items['Privacy']    = "/privacy";
$footer_items['Contact']    = "/contact";

function str2uri($string)
{
    return str_replace(" ", "-", strtolower($string));
}

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo empty($page_title)? "" : "{$page_title} - "; echo empty($page_subtitle)? "" : "{$page_subtitle} - "; echo empty($page_subtitle2)? "" : "{$page_subtitle2} - "; ?>StudyMonkey.ca</title>
    <meta charset="utf-8">
    <script  type="text/javascript" src="/js/jquery-1.6.3.min.js"></script>
    <script  type="text/javascript" src="/js/jquery.color.js"></script>
    <link href="/css/layout.css" rel="stylesheet" type="text/css" />
    <link rel="icon" type="image/x-icon" href="/favicon.ico"/>
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <script>
        $(document).ready(function(){

            // TODO - standard loading animations on each form
            //$("#form_send").submit(function(){
                //$("#submit_loading_image").show();
            //});

            // SPEECH BUBBLE
            var speech_bubble_open = true;
            $("#speech_bubble").hide().fadeIn(750).fadeOut(8000);
            $("#speech_bubble").mouseover(function(){
                if (speech_bubble_open)
                    $(this).stop().css({opacity: 1}).fadeOut(8000);
            });
            $("#speech_bubble_close").click(function(){
                speech_bubble_open = false;
                $("#speech_bubble").stop().fadeOut(250);
                return false;
            });


        });
    </script>
</head>
<body>
    <div id="header">
        <div id="header_content">
            <a href="/">
                <img id="header_logo" src="/image/layout/logo-header.png" alt="StudyMonkey.ca" />
            </a>
            <input id="header_search" type="text" value="Search..." />
            <div id="header_login">
                <a href="/notesolution" style="color: #000;">Looking for our old login box? &#187;</a>
            </div>
            <div id="navigation">
<!------------------------------ START WIDGET --------------------------------->
<?php $this->load->view('_calendar');?>
<!------------------------------- END WIDGET ---------------------------------->
                <ul>
<?php foreach ($navigation_items as $title => $link) { ?>
                    <li>
                        <a class="<?php echo ($page_tab == $title)? "selected" : "normal"; ?>" href="<?=$link?>">
                            <img src="/image/icon/<?php echo str2uri($title); ?>.png" />
                            <span><?=$title?></span>
                        </a>
                    </li>
<?php } ?>
                </ul>
            </div>
        </div>
    </div>
    <div id="content_wrapper">
        <div id="content">
            <div id="content_header" style="line-height: 0.8em;">
                <div id="content_heading">
                    <div id="page_title"><?=$page_title?></div>
                    <div id="page_subtitle"><?=$page_subtitle?></div>
                    <div id="page_subtitle2"><?=$page_subtitle2?></div>
                </div>
            </div>
            <div id="content_body">
<!------------------------------ START CONTENT -------------------------------->
<?php echo $page_content;?>
<!------------------------------- END CONTENT --------------------------------->
            </div>
        </div>
        <div id="content_end"></div>
    </div>
    <div id="footer">
        <ul id="footer_menu">
<?php foreach ($footer_items as $title => $link) { ?>
            <li><a href="<?=$link?>"><?=$title?></a> </li>
<?php } ?>
        </ul>
        <div id="footer_side_rail">
            <a href="http://www.facebook.com/StudyMonkey"><img src="/image/icon/facebook.png" alt="Facebook" /></a>
            <a href="http://twitter.com/StudyMonkey"><img src="/image/icon/twitter.png" alt="Twitter" /></a>
            <br/>
            &copy; 2011 StudyMonkey Inc.
        </div>
    </div>
    <div id="toolbar_wrapper">
        <div id="toolbar">
            <img id="mascot" src="/image/layout/mascot.png" />
<?php if (!empty($notification)) { ?>
            <div id="speech_bubble">
                <a id="speech_bubble_close" href="#">&times;</a>
                <span><?php echo $notification->message; ?></span>
                <div id="speech_bubble_tail"></div>
                <div id="speech_bubble_tail_border"></div>
            </div>
<?php } ?>
        </div>
    </div>
</body>
</html>
<?php

/* End of file _layout_main.php */
/* Location: ./application/views/_layout_main.php */