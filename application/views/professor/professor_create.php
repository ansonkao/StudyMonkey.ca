<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Professor Create
 */

$add_first_name_placeholder = "e.g. Albert";
$add_last_name_placeholder = "e.g. Einstein";
$add_department_placeholder = "e.g. Physics";

?>
<script>
    $(document).ready(function(){

        // Lightbox it up!
        $("a.add_professor_lightbox").colorbox({
            inline: true,
            initialWidth: 64,
            initialHeight: 64,
            opacity: 0.64,
            overlayClose: false
        });

        // PLACE HOLDERS for ADD PROFESSOR
        $('#add_first_name').focus(function(){
            if($(this).val() == "<?=$add_first_name_placeholder?>")
            {
                $(this).val("");
                $(this).css({"fontStyle":"normal", "color":"rgb(0,0,0)"});
            }
        }).blur(function(){
            if($(this).val() == "")
            {
                $(this).val("<?=$add_first_name_placeholder?>");
                $(this).css({"fontStyle":"italic", "color":"rgb(119,136,119)"});
            }
        });
        $('#add_last_name').focus(function(){
            if($(this).val() == "<?=$add_last_name_placeholder?>")
            {
                $(this).val("");
                $(this).css({"fontStyle":"normal", "color":"rgb(0,0,0)"});
            }
        }).blur(function(){
            if($(this).val() == "")
            {
                $(this).val("<?=$add_last_name_placeholder?>");
                $(this).css({"fontStyle":"italic", "color":"rgb(119,136,119)"});
            }
        });
        $('#add_department').focus(function(){
            if($(this).val() == "<?=$add_department_placeholder?>")
            {
                $(this).val("");
                $(this).css({"fontStyle":"normal", "color":"rgb(0,0,0)"});
            }
        }).blur(function(){
            if($(this).val() == "")
            {
                $(this).val("<?=$add_department_placeholder?>");
                $(this).css({"fontStyle":"italic", "color":"rgb(119,136,119)"});
            }
        });

        // Correct placeholder styling if browser pre-populates value from previous page load
        if( $('#add_first_name').val() != "<?=$add_first_name_placeholder?>" )
        {
            $('#add_first_name').css({"fontStyle":"normal", "color":"rgb(0,0,0)"});
        }
        if( $('#add_last_name').val() != "<?=$add_last_name_placeholder?>" )
        {
            $('#add_last_name').css({"fontStyle":"normal", "color":"rgb(0,0,0)"});
        }
        if( $('#add_department').val() != "<?=$add_department_placeholder?>" )
        {
            $('#add_department').css({"fontStyle":"normal", "color":"rgb(0,0,0)"});
        }

        // Add Professor AJAX
        $("form[name=add_professor]").submit( function(){

            // Validate empty fields
            if( $("#add_first_name").val() == "<?=$add_first_name_placeholder?>" )
            {
                new_speech_bubble( "Please enter a first name for your professor." );
                return false;
            }
            if( $("#add_last_name").val() == "<?=$add_last_name_placeholder?>" )
            {
                new_speech_bubble( "Please enter a last name for your professor." );
                return false;
            }
            if( $("#add_department").val() == "<?=$add_department_placeholder?>" )
            {
                new_speech_bubble( "Please enter a department for your professor." );
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
                        new_speech_bubble("You've added Professor " + $("#add_first_name").val() + " " + $("#add_last_name").val() + "!");
                        $("a.review_lightbox_link").click();
<?php } else { ?>
                        window.location = "/<?php echo string2uri( $school['full_name'] ); ?>/professors/" + data.substring(9);
<?php } ?>
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
<div id="add-professor" style="border: 3px solid #000; width: 320px; padding: 20px; background-color: #FFF;">
    <h2 style="text-align: center; margin: 0 0 10px;">
        Add a professor
    </h2>
    <form name="add_professor" method="post" action="/<?php echo string2uri( $school['full_name'] ); ?>/professors/create">
        <table border="0" cellspacing="0" cellpadding="5" style="margin: auto;">
            <tr>
                <td valign="top" align="right">
                    <label for="first_name">First Name</label>
                </td>
                <td valign="top">
                    <input id="add_first_name" class="placeholder" type="text" name="first_name" value="<?=$add_first_name_placeholder?>" style="width: 200px;" />
                </td>
            </tr>
            <tr>
                <td valign="top" align="right">
                    <label for="last_name">Last Name</label>
                </td>
                <td valign="top">
                    <input id="add_last_name" class="placeholder" type="text" name="last_name" value="<?=$add_last_name_placeholder?>" style="width: 200px;" />
                </td>
            </tr>
            <tr>
                <td valign="top" align="right">
                    <label for="department">Department</label>
                </td>
                <td valign="top">
                    <input id="add_department" class="placeholder" type="text" name="department" value="<?=$add_department_placeholder?>" style="width: 200px;" />
                </td>
            </tr>
            <tr>
                <td valign="top" align="right">
                    <label>Gender</label>
                </td>
                <td valign="top" align="left">
                    <input type="radio" name="gender" id="gender_m" value="M" />
                    <label for="gender_m">Male</label>
                    <input type="radio" name="gender" id="gender_f" value="F" />
                    <label for="gender_f">Female</label>
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
                                <img src="/captcha" alt="Captcha!" style="border: 1px solid #000; -moz-border-radius: 5px;" />
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
                    <input type="submit" value="Add professor &#187;" />
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

/* End of file professor_create.php */
/* Location: ./application/views/professor/professor_create.php */