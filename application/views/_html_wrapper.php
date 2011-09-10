<?php
/**
 * HTML WRAPPER
 * This file has the opening and closing tags for an html document,
 * meta stuff and includes in the html head.
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo empty($page_title)? "" : "{$page_title} - "; ?>StudyMonkey.ca</title>
    <meta charset="utf-8">
    <link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700&v2' rel='stylesheet' type='text/css' />
    <link href="/css/layout.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div id="header">
        <div id="header_content">
            <img id="header_logo" src="/image/logo_header.png" alt="reDISCOVERING.me" />
            <input id="header_button" type="text" value="Search..." />
        </div>
    </div>
    <div id="main">
        <div id="navigation">
            <ul>
                <li class="selected">Home</li>
                <li>Schools</li>
                <li>Courses</li>
            </ul>
        </div>
        <div id="content">
            <div id="content_header">
                Browse Courses
            </div>
            <div id="content_body">
            <?php echo $page_content; ?>
            </div>
            <div id="footer">
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
        </div>
    </div>
</body>
</html>
<?php

/* End of file _footer.php */
/* Location: ./application/views/_footer.php */