<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Course Create
 */

$add_course_code_placeholder = "e.g. PSYCH101";
$add_course_title_placeholder = "e.g. Introduction to Psychology";

?>
<script>
    $(document).ready(function(){

        // Lightbox it up!
        $("a.add_course_lightbox").colorbox({
            inline: true,
            initialWidth: 64,
            initialHeight: 64,
            opacity: 0.64,
            overlayClose: false
        });

        // PLACE HOLDERS
        $('#add_course_code').focus(function(){
            if($(this).val() == "<?=$add_course_code_placeholder?>")
            {
                $(this).val("");
                $(this).css({"fontStyle":"normal", "color":"rgb(0,0,0)"});
            }
        }).blur(function(){
            if($(this).val() == "")
            {
                $(this).val("<?=$add_course_code_placeholder?>");
                $(this).css({"fontStyle":"italic", "color":"rgb(119,136,119)"});
            }
        });
        $('#add_course_title').focus(function(){
            if($(this).val() == "<?=$add_course_title_placeholder?>")
            {
                $(this).val("");
                $(this).css({"fontStyle":"normal", "color":"rgb(0,0,0)"});
            }
        }).blur(function(){
            if($(this).val() == "")
            {
                $(this).val("<?=$add_course_title_placeholder?>");
                $(this).css({"fontStyle":"italic", "color":"rgb(119,136,119)"});
            }
        });

        // Correct placeholder styling if browser pre-populates value from previous page load
        if( $('#add_course_code').val() != "<?=$add_course_code_placeholder?>" )
        {
            $('#add_course_code').css({"fontStyle":"normal", "color":"rgb(0,0,0)"});
        }
        if( $('#add_course_title').val() != "<?=$add_course_title_placeholder?>" )
        {
            $('#add_course_title').css({"fontStyle":"normal", "color":"rgb(0,0,0)"});
        }


        // Add Course AJAX
        $("form[name=add_course]").submit( function(){

            // Validate empty fields
            if( $("#add_course_code").val() == "<?=$add_course_code_placeholder?>" )
            {
                new_speech_bubble( "Please enter a course code." );
                return false;
            }
            if( $("#add_course_title").val() == "<?=$add_course_title_placeholder?>" )
            {
                new_speech_bubble( "Please enter a title for your course." );
                return false;
            }

            // AJAX that shit!
            var loading_icon = $(this).find(".loading");
            loading_icon.show();
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(data){

                    if( data.substring(0, 8) == "REDIRECT" )
                    {
<?php if( isset( $course_or_professor ) ) { ?>
                        new_speech_bubble("You've added " + $("#add_course_code").val() + "!");
                        $.colorbox({
                            href: "#course_professor_review",
                            inline: true,
                            initialWidth: 64,
                            initialHeight: 64,
                            opacity: 0.64,
                            overlayClose: false
                        });
<?php } else { ?>
                        window.location = "/<?php echo string2uri( $school['full_name'] ); ?>/courses/" + data.substring(9);
<?php } ?>
                        loading_icon.hide();
                    }
                    else
                    {
                        new_speech_bubble( data.substring(6) );
                        loading_icon.hide();
                    }
                }
            });
            return false;
        })

    });
</script>
<div style="display: none;">
<div id="add-course" style="border: 3px solid #000; width: 320px; padding: 20px; background-color: #FFF;">
    <h2 style="text-align: center; margin: 0 0 10px;">
        Add a course
    </h2>
    <form name="add_course" method="post" action="/<?php echo string2uri( $school['full_name'] ); ?>/courses/create">
        <table border="0" cellspacing="0" cellpadding="5" style="margin: auto;">
            <tr>
                <td valign="top" align="right">
                    <label for="course_code">Course Code</label>
                </td>
                <td valign="top">
                    <input id="add_course_code" class="placeholder" type="text" name="course_code" value="<?=$add_course_code_placeholder?>" style="width: 200px;" />
                </td>
            </tr>
            <tr>
                <td valign="top" align="right">
                    <label for="course_title">Title</label>
                </td>
                <td valign="top">
                    <input id="add_course_title" class="placeholder" type="text" name="course_title" value="<?=$add_course_title_placeholder?>" style="width: 200px;" />
                </td>
            </tr>
            <tr>
                <td valign="top" align="right">
                    <label for="captcha">Math is Fun!</label>
                </td>
                <td valign="top" align="left">
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td valign="top" align="center" style="width: 100px;">
                                <img src="/captcha<?php echo "?=".time(); ?>" alt="Captcha!" style="border: 1px solid #000; -moz-border-radius: 5px;" />
                            </td>
                            <td valign="center" align="center" style="width: 44px;">
                                <span style="font: bold 24px arial;">
                                    =
                                </span>
                            </td>
                            <td valign="top" align="left">
                                <input type="text" id="captcha" name="captcha" value="" style="width: 55px; text-align: center;" />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td valign="top" align="right" colspan="2">
                    <img class="loading" src="/image/icon/loading.gif" alt="Loading..." />
                    <input type="submit" value="Add course &#187;" />
<?php if( isset( $course_or_professor ) ) { ?>
                    <input type="hidden" name="return" value="id" />
<?php } ?>
                </td>
            </tr>
        </table>
    </form>
</div>
</div>
<?php

/* End of file course_create.php */
/* Location: ./application/views/course/course_create.php */