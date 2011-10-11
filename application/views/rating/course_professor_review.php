<?php
////////////////////////////////////////////////////////////////////////////////
// STUDYMONKEY.CA | Course / Professor REVIEW
////////////////////////////////////////////////////////////////////////////////
/*
 * This is the content for the review lightbox.
 */
?>
<script>
    $(document).ready(function(){

        $("a.review_lightbox_link").colorbox({
            inline: true,
            initialWidth: 64,
            initialHeight: 64,
            opacity: 0.64,
            overlayClose: false
        });

        $("#review_text").focus(function(){
            if ($(this).val() == "Have your say! Write a comment...") {
                $(this).val("");
                $(this).css("fontStyle", "normal")
                $(this).css("color", "#000")
            }
        }).blur(function(){
            if ($(this).val() == "") {
                $(this).val("Have your say! Write a comment...");
                $(this).css("fontStyle", "italic")
                $(this).css("color", "#787")
            }
        });

        /* =====================================================================
         * AUTOCOMPLETE professor_name / course_name
         * Can be switched between either depending on where its included,
         * course.php or professor.php
         */
        var course_professor_name_value;
        $("#course_professor_name").autocomplete("/<?php echo string2uri( $school['full_name'] ); ?>/<?=$course_or_professor?>s/autocomplete",{
            minChars: 0,
            selectFirst: true,
            autoFill: false,
            mustMatch: false,
            width: "400",
            extraParams: {host: 'http'}
        }).result(function(event, data, formatted){ // Update course_professor_id
            if (data) {
                $(this).prev().val(data[1]);
                if ($(this).val().length > 38) {
                    $(this).val($(this).val().substr(0, 35) + "...");
                }
                $(this).css("color", "#777");
                $(this).css("background-image", "url('/image/icon/x.png')")
                $(this).css("background-repeat", "no-repeat");
                $(this).css("background-position", "center right");
                $(this).blur();
                event.stopPropogation();    // Stop propogation to avoid
                                            // focus on the textbox after
                                            // "disabled" appearance
            }
        }).focus(function(event){
            $(this).prev().val("");
            $(this).css("fontStyle", "normal")
            $(this).css("color", "#000")
            $(this).css("background-image", "")
            $(this).val("");
            $(this).select();
        }).blur(function(){
            if ($(this).prev().val() == "") {
                $(this).val("<?php
                                switch ($course_or_professor) {
                                    case 'professor':
                                        echo "Type a professor's name...";
                                        break;
                                    case 'course':
                                        echo "Type a course code...";
                                        break;                            }
                                ?>");
                $(this).css("fontStyle", "italic")
                $(this).css("color", "#787")
            }
        });

        // =====================================================================
        // CLIENT SIDE FORM VALIDATION
        $("#review_text").keyup(function(){
            if ($(this).val().length > 500) {
                $("#characters_remaining_value").html("<span style='color: #F00;'>" + (500 - $(this).val().length) + "</span>");
                if ($(this).is(".invalid") == false) {
                    $(this).addClass("invalid");
                }
            } else {
                $("#characters_remaining_value").html(500 - $(this).val().length);
                $(this).removeClass("invalid");
            }
        });

        $("form#form_review").submit(function(){
            if ($("#course_professor_id").val() == "") {
                new_speech_bubble("Please type and select a valid <?=$course_or_professor?> from the autocomplete results.");
                $('#course_professor_name').select();
                return false;
            }
<?php
    // SET THE ORDER OF THE QUESTIONS
    switch($course_or_professor) {
        case 'course':
            $question_order = array('professor', 'course');
            break;
        case 'professor':
            $question_order = array('course', 'professor');
            break;
    }

    foreach ($question_order as $i_course_or_professor) {
        switch($i_course_or_professor) {
            case 'course':
?>
            if ($("input[name=workload_rating]").prevAll().children().is(".star-rating-on") == false) {
                new_speech_bubble("Please rate the course workload.");
                return false;
            }
            if ($("input[name=easiness_rating]").prevAll().children().is(".star-rating-on") == false) {
                new_speech_bubble("Please rate the course easiness.");
                return false;
            }
            if ($("input[name=interest_rating]").prevAll().children().is(".star-rating-on") == false) {
                new_speech_bubble("Please rate the course interest level.");
                return false;
            }
<?php
                break;
            case 'professor':
?>
            if ($("input[name=knowledge_rating]").prevAll().children().is(".star-rating-on") == false) {
                new_speech_bubble("Please rate the professor's knowledge.");
                return false;
            }
            if ($("input[name=helpful_rating]").prevAll().children().is(".star-rating-on") == false) {
                new_speech_bubble("Please rate the professor helpfulness.");
                return false;
            }
            if ($("input[name=awesome_rating]").prevAll().children().is(".star-rating-on") == false) {
                new_speech_bubble("Please rate the professor awesomeness.");
                return false;
            }
<?php
                break;
        }
    }
?>
            if ($("select[name=attendance_rating]").val() == "") {
                new_speech_bubble("Please choose a response for attendance.");
                return false;
            }
            if ($("select[name=textbook_rating]").val() == "") {
                new_speech_bubble("Please choose a response for textbook.");
                return false;
            }
            if ($("#review_text").val() == "" || $("#review_text").val() == "Have your say! Write a comment...") {
                new_speech_bubble("Please leave a comment.");
                return false;
            }
            if ($("#review_text").val().length > 500) {
                new_speech_bubble("Your comment is " + $("#review_text").val().length + " characters long - please keep it within 500 characters.");
                return false;
            }
            if ($("input[name=overall_recommendation]").is(":checked") == false) {
                new_speech_bubble("Please choose [yes/no] for your overall recommendation.");
                return false;
            }

            // If we reach this point, form is valid and submitting
            return true;
        });
    });
</script>
<div style="display: none;">
<div id="course_professor_review" style="width: 360px; border: 3px solid #000; padding: 20px; background-color: #FFF; text-align: right;">

    <h2 style="margin: 0px 0px 10px; text-align: left;">Rate this <?php
                            switch ($course_or_professor) {
                                case 'professor':
                                    echo "Course";
                                    break;
                                case 'course':
                                    echo "Professor";
                                    break;
                            }
        ?>!</h2>
    <form action="/user/course_professor_review_handler.php" method="post" id="form_review">
        <div style="width: 340px; text-align: left;" id="review_buttons">
            <table border="0" cellspacing="0" cellpadding="2" class="list_review" width="100%">
                <tr>
                    <td align="center" valign="top" colspan="2">
                        <label for="course_professor_name" style="position: relative;">
                            <?php
                                switch ($course_or_professor) {
                                    case 'professor':
                                        echo "Which professor taught you this course?\n";
                                        break;
                                    case 'course':
                                        echo "Which course did you take with this professor?\n";
                                        break;
                                }
                            ?>
                        </label>
                        <br/>
                        <input type="hidden" value="" name="course_professor_id" id="course_professor_id" />
                        <input type="text" class="input_text_main" id="course_professor_name" name="course_professor_name" style="width: 250px; margin-top: 5px; font-style: italic; color: #787;" value="<?php
                            switch ($course_or_professor) {
                                case 'professor':
                                    echo "Type a professor's name...";
                                    break;
                                case 'course':
                                    echo "Type a course code...";
                                    break;                            }
                            ?>"/>
                        <div style="font: normal 11px arial; padding: 2px 0;">
                            Can't find your <?=$course_or_professor?>?
                            <a href="#add-<?=$course_or_professor?>" class="add_<?=$course_or_professor?>_lightbox">
                                Add+
                            </a>
                        </div>
                    </td>
                </tr>
<?php
    // SET THE ORDER OF THE QUESTIONS
    switch($course_or_professor) {
        case 'course':
            $question_order = array('professor', 'course');
            break;
        case 'professor':
            $question_order = array('course', 'professor');
            break;
    }

    foreach ($question_order as $i_course_or_professor) {
        switch($i_course_or_professor) {
            case 'course':
?>
                <tr>
                    <td align="right" valign="center">
                        <label for="workload_rating" >
                            Course Workload
                        </label>
                    </td>
                    <td align="left" valign="center">
                        <input type="radio" class="input_radio_rating star face" name="workload_rating" value="1"/>
                        <input type="radio" class="input_radio_rating star face" name="workload_rating" value="2"/>
                        <input type="radio" class="input_radio_rating star face" name="workload_rating" value="3"/>
                        <input type="radio" class="input_radio_rating star face" name="workload_rating" value="4"/>
                        <input type="radio" class="input_radio_rating star face" name="workload_rating" value="5"/>
                    </td>
                </tr>
                <tr>
                    <td align="right" valign="center">
                        <label for="easiness_rating" >
                            Course Easiness
                        </label>
                    </td>
                    <td align="left" valign="center">
                        <input type="radio" class="input_radio_rating star" name="easiness_rating" value="1"/>
                        <input type="radio" class="input_radio_rating star" name="easiness_rating" value="2"/>
                        <input type="radio" class="input_radio_rating star" name="easiness_rating" value="3"/>
                        <input type="radio" class="input_radio_rating star" name="easiness_rating" value="4"/>
                        <input type="radio" class="input_radio_rating star" name="easiness_rating" value="5"/>
                    </td>
                </tr>
                <tr>
                    <td align="right" valign="center">
                        <label for="interest_rating" >
                            Course Interest Level
                        </label>
                    </td>
                    <td align="left" valign="center">
                        <input type="radio" class="input_radio_rating star" name="interest_rating" value="1"/>
                        <input type="radio" class="input_radio_rating star" name="interest_rating" value="2"/>
                        <input type="radio" class="input_radio_rating star" name="interest_rating" value="3"/>
                        <input type="radio" class="input_radio_rating star" name="interest_rating" value="4"/>
                        <input type="radio" class="input_radio_rating star" name="interest_rating" value="5"/>
                    </td>
                </tr>
<?php
                break;
            case 'professor':
?>
                <tr>
                    <td align="right" valign="center">
                        <label for="knowledge_rating" >
                            Professor's Knowledge
                        </label>
                    </td>
                    <td align="left" valign="center">
                        <input type="radio" class="input_radio_rating star" name="knowledge_rating" value="1"/>
                        <input type="radio" class="input_radio_rating star" name="knowledge_rating" value="2"/>
                        <input type="radio" class="input_radio_rating star" name="knowledge_rating" value="3"/>
                        <input type="radio" class="input_radio_rating star" name="knowledge_rating" value="4"/>
                        <input type="radio" class="input_radio_rating star" name="knowledge_rating" value="5"/>
                    </td>
                </tr>
                <tr>
                    <td align="right" valign="center">
                        <label for="helpful_rating" >
                            Professor Helpfulness
                        </label>
                    </td>
                    <td align="left" valign="center">
                        <input type="radio" class="input_radio_rating star" name="helpful_rating" value="1"/>
                        <input type="radio" class="input_radio_rating star" name="helpful_rating" value="2"/>
                        <input type="radio" class="input_radio_rating star" name="helpful_rating" value="3"/>
                        <input type="radio" class="input_radio_rating star" name="helpful_rating" value="4"/>
                        <input type="radio" class="input_radio_rating star" name="helpful_rating" value="5"/>
                    </td>
                </tr>
                <tr>
                    <td align="right" valign="center">
                        <label for="awesome_rating">
                            Professor Awesomeness
                        </label>
                    </td>
                    <td align="left" valign="center">
                        <input type="radio" class="input_radio_rating star" name="awesome_rating" value="1"/>
                        <input type="radio" class="input_radio_rating star" name="awesome_rating" value="2"/>
                        <input type="radio" class="input_radio_rating star" name="awesome_rating" value="3"/>
                        <input type="radio" class="input_radio_rating star" name="awesome_rating" value="4"/>
                        <input type="radio" class="input_radio_rating star" name="awesome_rating" value="5"/>
                    </td>
                </tr>
<?php
                break;
        }
    }
?>
                <tr>
                    <td align="right" valign="center">
                        <label for="attendance_rating" >
                            Attendance
                        </label>
                    </td>
                    <td align="left" valign="center">
                        <select id="attendance_rating" name="attendance_rating" style="width: 180px;">
                            <option value="" id="attendance_rating_null">is...</option>
                            <option value="4" id="attendance_rating_4">Mandatory</option>
                            <option value="3" id="attendance_rating_3">Recommended</option>
                            <option value="2" id="attendance_rating_2">Not necessary</option>
                            <option value="1" id="attendance_rating_1">Useless (avoid going!)</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right" valign="center">
                        <label for="textbook_rating" >
                            Textbook
                        </label>
                    </td>
                    <td align="left" valign="center">
                        <select id="textbook_rating" name="textbook_rating" style="width: 180px;">
                            <option value="" id="textbook_rating_null">is...</option>
                            <option value="4" id="textbook_rating_4">Mandatory</option>
                            <option value="3" id="textbook_rating_3">Recommended</option>
                            <option value="2" id="textbook_rating_2">Not necessary</option>
                            <option value="1" id="textbook_rating_1">Useless (don't buy it!)</option>
                            <option value="0" id="textbook_rating_0">N/A</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <div style="width: 100%; text-align: left; margin-top: 15px;">
            <textarea class="input_text_main" id="review_text" name="review_text" style="width: 350px; height: 50px; resize: none; font-style: italic; color: #787;">Have your say! Write a comment...</textarea>
            <div id="characters_remaining" style="font: normal 11px arial; color: #888;">You have <span id="characters_remaining_value">500</span> characters remaining.</div>
        </div>
        <div id="div_recommendation" style="margin: 10px 0px 5px;">
            <label for="overall_recommendation">Overall, would you recommend this course?</label> &nbsp;
            <input type="radio" name="overall_recommendation" id="overall_recommendation_1" value="1"/> <label for="overall_recommendation_1">Yes</label> &nbsp;
            <input type="radio" name="overall_recommendation" id="overall_recommendation_0" value="0"/> <label for="overall_recommendation_0">No</label>
        </div>
        <input type="hidden" value="<?=$this_course_or_professor_id?>" name="course_or_professor_page_id">
        <input type="hidden" value="<?php
            switch ($course_or_professor) {
                case 'professor':
                    echo 'course';
                    break;
                case 'course':
                    echo 'professor';
                    break;
            } ?>" name="course_or_professor_page">
        <input type="checkbox" style="margin: 0px; padding: 0px;" id="anonymous" name="anonymous" value="1" />
        <label for="anonymous" style="font: normal 11px arial; color: #333;">Please don't show my username</label>
        &nbsp;&nbsp;&nbsp;
        <input type="hidden" value="create_review" name="action" />
        <input type="submit" value="Submit" class="input_submit_main" id="review_submit" onclick="this.blur()" style="margin: 5px 0px 0px;"/>
    </form>
</div>
</div>
<!---------------------- START ADD COURSE / PROFESSOR ------------------------->
<?php $this->load->view("{$course_or_professor}/{$course_or_professor}_create"); ?>
<!----------------------- END ADD COURSE / PROFESSOR -------------------------->
<?

/* End of file course_professor_review.php */
/* Location: ./application/views/rating/course_professor_review.php */