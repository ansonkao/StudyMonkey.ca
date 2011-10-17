<?php
/**
 * LAYOUT - MAIN
 *
 * This is the main layout of the website.  This template holds together
 * almost every page in the site.
 *
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

    <title>
        <?php echo empty($page_title)? "" : "{$page_title} - "; echo empty($page_subtitle)? "" : "{$page_subtitle} - "; echo empty($page_subtitle2)? "" : "{$page_subtitle2} - "; ?>StudyMonkey.ca
    </title>
    <meta charset="utf-8">

    <script  type="text/javascript" src="/js/jquery-1.6.3.min.js"></script>
    <script  type="text/javascript" src="/js/jquery.color.js"></script>
    <link href="/css/home.css?v=<?=VERSION?>" rel="stylesheet" type="text/css" />
    <link href="/css/element_styles.css?v=<?=VERSION?>" rel="stylesheet" type="text/css" />
    <link rel="icon" type="image/x-icon" href="/favicon.ico"/>
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>

    <!-- ======================= START GOOGLE ANALYTICS ======================== -->
    <script type="text/javascript">
        // TODO: Do this in detail
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-17694691-2']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    </script>
    <!-- ======================== END GOOGLE ANALYTICS ========================= -->

    <script>
        $(document).ready(function(){

            setTimeout
                ( function()
                    { new_speech_bubble( "Try me! Enter the name of your college or university in the search bar." );
                    }
                , 8000
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

                <div id="header_login">
                    <a href="/blog/studymonkey-partnership" style="color: #000;">Looking for our old login box? &#187;</a>
                </div>

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

            <div style="margin: 36px auto; width: 420px;">

                <table border="0" cellspacing="0" cellpadding="0" style="float: left; width: 200px;">
                    <tr>
                        <td align="left" colspan="2">
                            <h2 style="font: bold 14px arial; padding: 0 0 10px; margin: 0px; text-align: left;">
                                Popular courses
                            </h2>
                        </td>
                    </tr>
<?php
        foreach( $popular_courses as $popular_course )
        {
            $school = $popular_schools[$popular_course['school_id']];
?>
                    <tr>
                        <td align="left" valign="top" style="width: 36px; height: 60px;">
                            <a href="<?php echo site_url( $school['uri']."/courses/".string2uri($popular_course['course_code']) ); ?>">
                                <img src="<?php echo site_url()."image/icon/courses.png"; ?>" style="margin-right: 5px;" />
                            </a>
                        </td>
                        <td align="left" valign="top" style="padding-top: 5px;">
                            <a href="<?php echo site_url( $school['uri']."/courses/".string2uri($popular_course['course_code']) ); ?>">
                                <strong><?php echo $popular_course['course_code']; ?></strong>
                            </a>
                            <br/>
                            <span style="font-size: 11px; color: #888;"><?php echo $school['full_name']; ?></span>
                            <div class="transparent" style="padding-top: 2px;">
<?php   if ($popular_course['total_reviews'] > 0) { ?>
                                <div style="margin-right: 5px; height: 12px; width: 60px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll top; text-align: left; float: left;">
                                    <div style="height: 12px; width: <?php if ($popular_course['overall_rating']) { echo round($popular_course['overall_rating'] * 60.0 / 5.0); } else { echo "0"; } ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll left bottom;">
                                        &nbsp;
                                    </div>
                                </div>
                                <div style="float: left; font: normal 10px arial;"><?php
                                    switch( $popular_course['total_reviews'] )
                                    {
                                        case 0:
                                            echo "No reviews yet";
                                            break;
                                        case 1:
                                            echo "1 review";
                                            break;
                                        default:
                                            echo "{$popular_course['total_reviews']} reviews";
                                            break;
                                    }
                                ?></div>
<?php   } else { ?>
                                <span style="float: left; font: normal 11px arial;">
                                    No reviews yet
                                </span>
<?php   } ?>
                            </div>
                        </td>
                    </tr>
<?php } ?>
                </table>

                <table border="0" cellspacing="0" cellpadding="0" style="float: left; margin-left: 10px; width: 200px;">
                    <tr>
                        <td align="left" colspan="2">
                            <h2 style="font: bold 14px arial; padding: 0 0 10px; margin: 0px; text-align: left;">
                                Popular professors
                            </h2>
                        </td>
                    </tr>
<?php
        foreach( $popular_professors as $popular_professor )
        {
            $school = $popular_schools[$popular_professor['school_id']];
?>
                    <tr>
                        <td align="left" valign="top" style="width: 36px; height: 60px;">
                            <a href="<?php echo site_url( $school['uri']."/professors/".$popular_professor['uri'] ); ?>">
                                <img src="<?php echo site_url()."image/icon/professors.png"; ?>" style="margin-right: 5px;" />
                            </a>
                        </td>
                        <td align="left" valign="top" style="padding-top: 5px;">
                            <a href="<?php echo site_url( $school['uri']."/professors/".$popular_professor['uri'] ); ?>">
                                <strong><?php echo $popular_professor['last_name'].", ".$popular_professor['first_name']; ?></strong>
                            </a>
                            <br/>
                            <span style="font-size: 11px; color: #888;"><?php echo $school['full_name']; ?></span>
                            <div class="transparent" style="padding-top: 2px;">
<?php   if ($popular_professor['total_reviews'] > 0) { ?>
                                <div style="margin-right: 5px; height: 12px; width: 60px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll top; text-align: left; float: left;">
                                    <div style="height: 12px; width: <?php if ($popular_professor['overall_rating']) { echo round($popular_professor['overall_rating'] * 60.0 / 5.0); } else { echo "0"; } ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll left bottom;">
                                        &nbsp;
                                    </div>
                                </div>
                                <div style="float: left; font: normal 10px arial;"><?php
                                    switch( $popular_professor['total_reviews'] )
                                    {
                                        case 0:
                                            echo "No ratings yet";
                                            break;
                                        case 1:
                                            echo "1 rating";
                                            break;
                                        default:
                                            echo "{$popular_professor['total_reviews']} ratings";
                                            break;
                                    }
                                ?></div>
<?php   } else { ?>
                                <span style="float: left; font: normal 11px arial;">
                                    No ratings yet
                                </span>
<?php   } ?>
                            </div>
                        </td>
                    </tr>
<?php } ?>
                </table>

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

<!-- =========================== START MASCOT ============================== -->
<?php $this->load->view( '_mascot' ); ?>
<!-- ============================ END MASCOT =============================== -->

</body>
</html>
<?php

/* End of file home.php */
/* Location: ./application/views/info/home.php */