<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Course Search
 */

$search_placeholder = "Type a course code or name...";
?>
<script>
    $(document).ready(function(){

        $("form[name=course_search]").submit(function(){
            if( $("input[name=search]").val() != "<?=$search_placeholder?>" && $.trim($("input[name=search]").val()) != "" )
            {
                $("#search_result").slideUp(100);
                $("#magnifying_glass").hide();
                $("#loading_gif").show();
                $.ajax({
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(data){
                        $("#loading_gif").hide();
                        $("#magnifying_glass").show();
                        $("#search_result").html(data);
                        $("#search_result").slideDown();
                    }
                });
            }
            return false;
        })

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
<div style="padding: 20px 0; text-align: center;">

    <form name="course_search" method="post">
        <table border="0" cellspacing="8" cellpadding="0" style="margin: 0 auto;">
            <tr>
                <td valign="center">
                    <img src="/image/courses_medium.png" />
                </td>
                <td valign="center" align="left">
                    <h1 style="margin: 0; font: 32px 'Oswald', arial; color: #121; padding: 0 20px 0 5px;">
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
                    <input id="search_box" class="huge" type="text" name="search" value="<?=$search_placeholder?>" />
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

    <div id="search_result">
<?php echo $search_result; ?>
    </div>

    <table border="0" cellspacing="10" cellpadding="0" style="margin: 40px auto 0;">
        <tr>
            <td align="center" colspan="2">
                <h2 style="margin: 0;">Popular courses</h2>
                <div style="padding-bottom: 10px;">
                    at <?=$school['full_name']?>
                </div>
            </td>
        </tr>
<?php foreach( $popular_courses as $popular_course ) { ?>
        <tr>
            <td align="left">
                <div style="margin-left: 5px; height: 24px; width: 120px; background: transparent url('<?php echo site_url()."image/rating/star_rating.gif"; ?>') repeat-x scroll top; text-align: left;">
                    <div style="height: 24px; width: <?php if ($popular_course['overall_rating']) { echo round($popular_course['overall_rating'] * 120.0 / 5.0); } else { echo "0"; } ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating.gif"; ?>') repeat-x scroll left bottom;">
                        &nbsp;
                    </div>
                </div>
                <div style="padding-left: 5px; font-size: 11px;"><?php
                    switch( $popular_course['total_reviews'] )
                    {
                        case 0:
                            echo "No reviews yet";
                            break;
                        case 1:
                            echo "Based on 1 review";
                            break;
                        default:
                            echo "Based on {$popular_course['total_reviews']} reviews";
                            break;
                    }
                ?></div>
            </td>
            <td align="left" style="font-size: 14px;">
                <a href="<?php echo site_url().string2uri($school['full_name'])."/courses/".string2uri($popular_course['course_code']); ?>">
                    <strong><?php echo $popular_course['course_code']; ?></strong>
                </a>
                <div style="max-width: 320px;"><?php echo $popular_course['course_title']; ?></div>
                <div style="font-size: 11px; color: #888;"><?php echo $school['full_name']; ?></div>
            </td>
        </tr>
<?php } ?>
    </table>

</div>
<?php

/* End of file course_search.php */
/* Location: ./application/views/course/course_search.php */