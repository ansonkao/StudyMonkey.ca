<?php
/**
 * LAYOUT - MAIN
 *
 * This is the main layout of the website.  This template holds together
 * almost every page in the site.
 */

$search_placeholder = "Type the name of your school...";

$footer_items = array();
$footer_items['Terms']      = "/terms";
$footer_items['Privacy']    = "/privacy";
$footer_items['Contact']    = "/contact";

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo empty($page_title)? "" : "{$page_title} - "; echo empty($page_subtitle)? "" : "{$page_subtitle} - "; echo empty($page_subtitle2)? "" : "{$page_subtitle2} - "; ?>StudyMonkey.ca</title>
    <meta charset="utf-8">
    <script  type="text/javascript" src="/js/jquery-1.6.3.min.js"></script>
    <script  type="text/javascript" src="/js/jquery.color.js"></script>
    <link href="/css/home.css" rel="stylesheet" type="text/css" />
    <link href="/css/element_styles.css" rel="stylesheet" type="text/css" />
    <link rel="icon" type="image/x-icon" href="/favicon.ico"/>
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <script>
        $(document).ready(function(){

            setTimeout
                ( function()
                    { new_speech_bubble( "Try me! Enter the name of your college or university in the search bar." );
                    }
                , 3000
                );

            // School Search - AJAX
            $("form[name=school_search]").submit(function(){
                if( $("input[name=search]").val() != "<?=$search_placeholder?>" && $.trim($("input[name=search]").val()) != "" )
                {
                    $("#search_result").slideUp(100);
                    $("#magnifying_glass").hide();
                    $("#loading_gif").show();
                    $.ajax({
                        type: "POST",
                        data: $(this).serialize(),
                        success: function( data ){
                            if( data.substring(0, 8) == "REDIRECT" )
                            {
                                window.location = "/" + data.substring(9) + "/courses";
                            }
                            else
                            {
                                $("#loading_gif").hide();
                                $("#magnifying_glass").show();
                                $("#search_result").html(data);
                                $("#search_result").slideDown();
                            }
                        }
                    });
                }
                return false;
            })

            // Place holders for the search box
            $('input[name=search]').focus(function(){
                if($(this).val() == "<?=$search_placeholder?>")
                {
                    $(this).val("");
                    $(this).css({"fontStyle":"normal", "color":"rgb(0,0,0)"});
                }
            }).blur(function(){
                if($(this).val() == "")
                {
                    $(this).val("<?=$search_placeholder?>");
                    $(this).css({"fontStyle":"italic", "color":"rgb(119,136,119)"});
                }
            });

        });
    </script>
</head>
<body>
    <div id="content_wrapper">
        <div id="content">

            <div id="content_body_top">

                <img src="/image/logo_home.png" alt="StudyMonkey.ca" />

                <table border="0" cellpadding="0" cellspacing="0" style="margin: 20px auto 0;">
                    <tr>
                        <td valign="center">
                            <img src="/image/courses_medium.png" alt="Course Reviews" />
                            &nbsp; &nbsp;
                        </td>
                        <td valign="center" align="left">
                            <h1 style="margin: 0px;">Course Reviews</h1>
                        </td>
                        <td rowspan="3" style="width: 40px;">
                            &nbsp; <!-- SPACER -->
                        </td>
                        <td valign="center">
                            <img src="/image/professors_medium.png" alt="Professor Ratings" />
                            &nbsp; &nbsp;
                        </td>
                        <td valign="center" align="left">
                            <h1 style="margin: 0px;">Professor Ratings</h1>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            &nbsp; <!-- SPACER -->
                        </td>
                        <td valign="top" align="left">
                            <p style="font-size: 14px;">
                                Picking courses for next semester?
                                <br/>
                                Find out what others say are the
                                <br/>
                                easiest and the best to take.
                            </p>
                        </td>
                        <td>
                            &nbsp; <!-- SPACER -->
                        </td>
                        <td valign="top" align="left">
                            <p style="font-size: 14px;">
                                Who are the best (and worst)
                                <br/>
                                professors at your school?
                                <br/>
                                Get the inside scoop here!
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" colspan="2">
                            <img src="/image/arrow_a.png" alt="Arrow" />
                        </td>
                        <td align="center" colspan="2">
                            <img src="/image/arrow_b.png" alt="Arrow" />
                        </td>
                    </tr>
                </table>

            </div>

        </div>

        <div id="content_body_bottom">

            <form name="school_search" method="post">
                <table border="0" cellspacing="10" cellpadding="0" style="margin: 25px auto 0;">
                    <tr>
                        <td valign="center">
                            <input id="search_box" class="placeholder huge" type="text" name="search" value="<?=$search_placeholder?>" />
                        </td>
                        <td valign="center">
                            <button class="huge" type="submit">
                                <img id="magnifying_glass" src="/image/icon/search_large.png" alt="Search" />
                                <img id="loading_gif" src="/image/icon/loading.gif" alt="Search" style="display: none;" />
                            </button>
                        </td>
                    </tr>
                </table>
            </form>

            <div style="font: italic 12px arial; color: #777; text-align: center;">
                <?=$total_reviews?> student opinions at <?=$total_schools?> Canadian schools so far!
            </div>

            <div id="search_result">
<?php echo $search_result; ?>
            </div>

        </div>

        <div id="content_end"></div>
    </div>

    <div id="footer">
        <ul>
<?php foreach ($footer_items as $title => $link) { ?>
            <li><a href="<?=$link?>"><?=$title?></a> </li>
<?php } ?>
        </ul>
        <div style="color: #888; font-size: 11px;">
            &copy; 2011 StudyMonkey Inc.
        </div>
    </div>

<!------------------------------ START MASCOT --------------------------------->
<?php $this->load->view( '_mascot' ); ?>
<!------------------------------- END MASCOT ---------------------------------->

</body>
</html>
<?php

/* End of file home.php */
/* Location: ./application/views/info/home.php */