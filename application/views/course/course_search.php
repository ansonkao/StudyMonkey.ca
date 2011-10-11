<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Course Search
 */

$search_placeholder = "Type a course code or name...";
$search_box_value = empty( $previous_query )? $search_placeholder : $previous_query;

?>
<script>
    $(document).ready(function(){

        // Search Bar AJAX
        $("form[name=course_search]").submit(function(){
            if( $("input[name=search]").val() != "<?=$search_placeholder?>" && $.trim($("input[name=search]").val()) != "" )
            {
                $("#search_result").fadeTo( 0, 0.5 );
                $("#magnifying_glass").hide();
                $("#loading_gif").show();
                $.ajax({
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(data){
                        
                        if( data.substring(0, 8) == "REDIRECT" )
                        {
                            window.location = "/<?php echo string2uri( $school['full_name'] ); ?>/courses/" + data.substring(9);
                        }
                        else
                        {
                            $("#loading_gif").hide();
                            $("#magnifying_glass").show();
                            $("#search_result").html(data);
                            $("#search_result").fadeTo( 0, 1.0 );
                        }
                    }
                });
            }
            return false;
        });

        // Letter Search AJAX
        $("form[name=letter_search]").submit(function(){
            $("#search_box")
                .val($(this).children().first().val())
                .css({"fontStyle":"normal", "color":"rgb(0,0,0)"});
            $("#search_result").fadeTo( 0, 0.5 );
            $("#magnifying_glass").hide();
            $("#loading_gif").show();
            $.ajax({
                type: "POST",
                data: $(this).serialize(),
                success: function(data){
                    $("#loading_gif").hide();
                    $("#magnifying_glass").show();
                    $("#search_result").html(data);
                    $("#search_result").fadeTo( 0, 1.0 );
                }
            });
            return false;
        });

        // PLACE HOLDER for SEARCH BAR
        $('#search_box').focus(function(){
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

        // Correct placeholder styling if browser pre-populates value from previous page load
        if( $('#search_box').val() != "<?=$search_placeholder?>" )
        {
            $('#search_box').css({"fontStyle":"normal", "color":"rgb(0,0,0)"});
        }

    });
</script>
<div class="left_column" style="text-align: center;">

    <form name="course_search" method="post">
        <table border="0" cellspacing="8" cellpadding="0" style="margin: 0 auto;">
            <tr>
                <td valign="right">
                    <img src="/image/courses_medium.png" />
                </td>
                <td valign="center" align="left">
                    <h1 style="margin: 0; font: 32px 'Oswald', arial; color: #121; padding: 0 20px 0 0;">
                        Course Reviews
                    </h1>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2">
                    <span style="font: normal 16px arial;">at <?php echo $school['full_name']; ?></span>
                    &nbsp;
                    <a href="/" style="font-size: 11px;">change schools <div class="triangle"></div></a>
                </td>
            </tr>
        </table>
        <table border="0" cellspacing="10" cellpadding="0" style="margin: 0 auto;">
            <tr>
                <td valign="center">
                    <input id="search_box" class="placeholder huge" type="text" name="search" value="<?=$search_box_value?>" />
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

    <div style="padding: 0 0 20px 20px; text-align: left;">
<?php foreach( range('A', 'Z') as $letter ) { ?>
        <form name="letter_search" method="post">
            <input type="hidden" name="search" value="<?=$letter?>" />
            <input type="submit" value="<?=$letter?>" />
        </form>
<?php } ?>
    </div>

    <div id="search_result">
<?php echo $search_result; ?>
    </div>

    <h2 style="margin-top: 64px; font: italic 16px arial;">
        Can't find your course?
        <a href="#add-course" class="add_course_lightbox">
            Add it!
        </a>
    </h2>
<!----------------------------- START ADD COURSE ------------------------------>
<?php $this->load->view("course/course_create"); ?>
<!------------------------------ END ADD COURSE ------------------------------->

</div>



<div class="right_column">

<!------------------------ START RECTANGLE BANNER AD -------------------------->
<?php $this->load->view("banners/notesolution_rectangle"); ?>
<!------------------------- END RECTANGLE BANNER AD --------------------------->

    <h2 style="font: bold 14px arial; padding: 20px 0 0px; margin: 0px;">
        Popular courses
    </h2>
    <div style="padding-bottom: 15px; color: #888;">
        at <?=$school['full_name']?>
    </div>

    <table border="0" cellspacing="0" cellpadding="0">
<?php foreach( $popular_courses as $popular_course ) { ?>
        <tr>
            <td align="left" valign="top" style="height: 45px;">
                <a href="<?php echo site_url().string2uri($school['full_name'])."/courses/".string2uri($popular_course['course_code']); ?>">
                    <img src="<?php echo site_url()."image/icon/courses.png"; ?>" style="margin-right: 5px;" />
                </a>
            </td>
            <td align="left" valign="top" style="padding-top: 5px;">
                <a href="<?php echo site_url().string2uri($school['full_name'])."/courses/".string2uri($popular_course['course_code']); ?>">
                    <strong><?php echo $popular_course['course_code']; ?></strong>
                </a>
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

    <h2 style="font: bold 14px arial; padding: 20px 0 0px; margin: 0px;">
        Top-rated courses
    </h2>
    <div style="padding-bottom: 15px; color: #888;">
        at <?=$school['full_name']?>
    </div>

    <table border="0" cellspacing="0" cellpadding="0">
<?php foreach( $top_rated_courses as $top_rated_course ) { ?>
        <tr>
            <td align="left" valign="top" style="height: 45px;">
                <a href="<?php echo site_url().string2uri($school['full_name'])."/courses/".string2uri($top_rated_course['course_code']); ?>">
                    <img src="<?php echo site_url()."image/icon/courses.png"; ?>" style="margin-right: 5px;" />
                </a>
            </td>
            <td align="left" valign="top" style="padding-top: 5px;">
                <a href="<?php echo site_url().string2uri($school['full_name'])."/courses/".string2uri($top_rated_course['course_code']); ?>">
                    <strong><?php echo $top_rated_course['course_code']; ?></strong>
                </a>
                <div class="transparent" style="padding-top: 2px;">
<?php   if ($top_rated_course['total_reviews'] > 0) { ?>
                    <div style="margin-right: 5px; height: 12px; width: 60px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll top; text-align: left; float: left;">
                        <div style="height: 12px; width: <?php if ($top_rated_course['overall_rating']) { echo round($top_rated_course['overall_rating'] * 60.0 / 5.0); } else { echo "0"; } ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll left bottom;">
                            &nbsp;
                        </div>
                    </div>
                    <div style="float: left; font: normal 10px arial;"><?php
                        switch( $top_rated_course['total_reviews'] )
                        {
                            case 0:
                                echo "No reviews yet";
                                break;
                            case 1:
                                echo "1 review";
                                break;
                            default:
                                echo "{$top_rated_course['total_reviews']} reviews";
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

</div>
<?php

/* End of file course_search.php */
/* Location: ./application/views/course/course_search.php */