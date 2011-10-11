<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * COURSE PAGE
 */
?>
<!----------------------- START COURSE/PROFESSOR-REVIEW ----------------------->
<?php
    $course_professor_review_params = array();
    $course_professor_review_params['course_or_professor'] = "professor";
    $course_professor_review_params['this_course_or_professor_id'] = $course['id'];
    $this->load->view('rating/course_professor_review', $course_professor_review_params );
?>
<!------------------------ END COURSE/PROFESSOR-REVIEW ------------------------>

<!----------------------------- START ADD COURSE ------------------------------>
<?php $this->load->view("course/course_create"); ?>
<!------------------------------ END ADD COURSE ------------------------------->

<?php if (0) {// session::user()->privilege == 'admin') { ?>
<div style="width: 20px; height: 30px; position: fixed; left: 0px; top: 160px; padding: 10px; border: 3px solid #000; border-left: 0px; -moz-border-radius: 0px 10px 10px 0px; background: #3D6A27; z-index: 2;">
    <div style="position: absolute; top: 10px; right: 10px;">
        <a href="#" id="admin_tab_open" style="color: #000;">
            <h2>&#9658;</h2>
        </a>
        <a href="#" id="admin_tab_close" style="color: #000; display: none;">
            <h1 style="margin: 0px 3px; padding: 0px;">&#215;</h1>
        </a>
    </div>
    <script>
        $(document).ready(function(){

            $("#admin_tab_open").click(function(){
                $(this).hide();
                $("#admin_tab_close").show();
                $(this).parent().parent().animate({"width":"400px", "height":"200px"}, 200, function(){
                    $("#admin_tab").show();
                });
                return false;
            });
            $("#admin_tab_close").click(function(){
                $(this).hide();
                $("#admin_tab_open").show();
                $(this).parent().parent().animate({"width":"20px", "height":"30px"}, 200);
                $("#admin_tab").hide();
                return false;
            });

            // AUTOCOMPLETE consolidate_course_name
            $("#consolidate_course_name").autocomplete("autocomplete_course.php",{
                minChars: 1,
                selectFirst: true,
                autoFill: false,
                mustMatch: false,
                width: "310"
            }).result(function(event, data, formatted){ // Update course_id
                if (data) {
                    $(this).prev().val(data[1]);
                    if ($(this).val().length > 30) {
                        $(this).val($(this).val().substr(0, 27) + "...");
                    }
                    $(this).css("color", "#000");
                    $(this).css("background-image", "url(<?php echo site_url()."image/rating/cancel_black.gif"; ?>)")
                    $(this).css("background-repeat", "no-repeat");
                    $(this).css("background-position", "center right");
                    $(this).blur();
                    event.stopPropogation();    // Stop propogation to avoid
                                                // focus on the textbox after
                                                // "disabled" appearance
                }
            }).focus(function(event){
                $(this).prev().val("");
                $(this).css("color", "#000")
                $(this).css("background-image", "")
                $(this).val("");
                $(this).select();
            }).blur(function(){
                if ($(this).prev().val() == "") {
                    $(this).val("Type a course code or title...");
                }
            });

        });
    </script>
    <div id="admin_tab" style="display: none;">
        <h3>Consolidate Duplicate Courses</h3>
        <p style="padding: 0px 0px 5px;">
            If there is a duplicate for this course in the system,
            indicate the duplicate below and this tool will perform the consolidation
            transparently to all end users.
        </p>
        <form action="" method="post" name="form_consolidate_course" id="form_consolidate_course">
            <input type="hidden" value="consolidate_course" name="action" />
            <input type="hidden" value="" name="consolidate_course_id" id="consolidate_course_id" />
            <input type="text" class="input_text_main" name="course_name" id="consolidate_course_name" value="Type a course code or title..." style="float: left; width: 200px; margin-right: 5px; background: #695;" />
            <input type="submit" value="Consolidate" class="input_submit_main" id="consolidate_course_submit" onclick="this.blur()" />
        </form>
        <h3>Update Totals</h3>
        <p style="padding: 0px 0px 5px;">
            If for some reason the course statistics need to be recalculated, use this button to do so:
        </p>
        <form action="" method="post" name="form_update_totals" id="form_update_totals">
            <input type="hidden" value="update_totals" name="action" />
            <input type="submit" value="Update" class="input_submit_main" id="update_totals_submit" onclick="this.blur()" />
        </form>
    </div>
</div>
<?php } ?>

<div class="left_column">

    <!-- STATISTICS ================================================ -->
    <table width="540" border="0" cellspacing="0" cellpadding="0" style="margin: 0px;">
        <tr>
            <td valign="top" rowspan="4" align="left">
                <h1 style="text-align: left; margin: 0; padding: 0 0 5px; font: bold 18px arial;">
                    Overall Score
                    <span style="padding-left: 10px; font: normal 12px arial; color: #888;">
                        <?php
                            switch( $total_reviews ) {
                                case 0: echo "No reviews yet"; break;
                                case 1: echo "Based on 1 review"; break;
                                default: // Multiple reviews
                                    echo "Based on ".$total_reviews." reviews";
                            }
                        ?>
                    </span>
                </h1>
                <div style="height: 48px; width: 240px; background: transparent url('<?php echo site_url()."image/rating/star_rating_large.gif"; ?>') repeat-x scroll top; text-align: left; float: left;">
                    <div style="height: 48px; width: <?php if ($course['overall_rating']) { echo round($course['overall_rating'] * 240.0 / 5.0); } else { echo "0"; } ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating_large.gif"; ?>') repeat-x scroll left bottom;">
                        &nbsp;
                    </div>
                </div>
                <span style="text-align: left; font: bold 42px arial; padding-left: 5px;"><?php if ($course['total_reviews']) { printf("%2.1f", $course['overall_rating']); } else { echo "N/A"; } ?></span>
            </td>
            <td colspan="2" style="height: 10px;">
                <!-- SPACER -->
            </td>
        </tr>
        <tr>
            <td>
                <h2 style="text-align: right; margin: 0; padding: 5px 3px 5px 0; font: bold 12px arial;">Workload</h2>
            </td>
            <td style="width: 120px;">
                <div style="margin-left: 5px; height: 24px; width: 120px; background: transparent url('<?php echo site_url()."image/rating/sad_smiley_face_rating.gif"; ?>') repeat-x scroll top; text-align: left;">
                    <div style="height: 24px; width: <?php if ($course['workload_rating']) { echo round($course['workload_rating'] * 120.0 / 5.0); } else { echo "0"; } ?>px; background: transparent url('<?php echo site_url()."image/rating/sad_smiley_face_rating.gif"; ?>') repeat-x scroll left bottom;">
                        &nbsp;
                    </div>
                </div>
            </td>
            <td valign="bottom">
                <span style="font: bold 16px arial">&nbsp;<?php if ($course['total_reviews']) { printf("%2.1f", $course['workload_rating']); } else { echo "N/A"; } ?></span>
            </td>
        </tr>
        <tr>
            <td>
                <h2 style="text-align: right; margin: 0; padding: 5px 3px 5px 0; font: bold 12px arial;">Easiness</h2>
            </td>
            <td>
                <div style="margin-left: 5px; height: 24px; width: 120px; background: transparent url('<?php echo site_url()."image/rating/star_rating.gif"; ?>') repeat-x scroll top; text-align: left;">
                    <div style="height: 24px; width: <?php if ($course['easiness_rating']) { echo round($course['easiness_rating'] * 120.0 / 5.0); } else { echo "0"; } ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating.gif"; ?>') repeat-x scroll left bottom;">
                        &nbsp;
                    </div>
                </div>
            </td>
            <td valign="bottom">
                <span style="font: bold 16px arial">&nbsp;<?php if ($course['total_reviews']) { printf("%2.1f", $course['easiness_rating']); } else { echo "N/A"; } ?></span>
            </td>
        </tr>
        <tr>
            <td>
                <h2 style="text-align: right; margin: 0; padding: 5px 3px 5px 0; font: bold 12px arial;">Interest</h2>
            </td>
            <td>
                <div style="margin-left: 5px; height: 24px; width: 120px; background: transparent url('<?php echo site_url()."image/rating/star_rating.gif"; ?>') repeat-x scroll top; text-align: left;">
                    <div style="height: 24px; width: <?php if ($course['interest_rating']) { echo round($course['interest_rating'] * 120.0 / 5.0); } else { echo "0"; } ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating.gif"; ?>') repeat-x scroll left bottom;">
                        &nbsp;
                    </div>
                </div>
            </td>
            <td valign="bottom">
                <span style="font: bold 16px arial">&nbsp;<?php if ($course['total_reviews']) { printf("%2.1f", $course['interest_rating']); } else { echo "N/A"; } ?></span>
            </td>
        </tr>
    </table>

    <!-- BAR GRAPHS ==================================== -->
    <table width="525" border="0" cellspacing="0" cellpadding="0" style="margin: 20px 0 20px; line-height: 1.6em;">
        <tr>
            <td align="left" valign="top">
                <!-- OVERALL RECOMMENDATION ============ -->
                <table width="" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="2">
                            <h2 style="text-align: center; margin: 0; padding: 0 0 8px; font: bold 12px arial;">Overall Recommendation</h2>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" style="padding: 5px 0px 5px;">
                            <span style="font: normal 12px arial;">Yes&nbsp;</span>
                        </td>
                        <td align="left" valign="center" style="border-left: 1px solid #000; padding: 5px 5px 5px 0px;">
                            <?php echo $this->course_professor_review->rating_bar($course, "overall_recommendation", 0, 1, 1, "large", "green"); ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" style="padding: 5px 0px 5px;">
                            <span style="font: normal 12px arial;">No&nbsp;</span>
                        </td>
                        <td align="left" valign="center" style="border-left: 1px solid #000; padding: 5px 5px 5px 0px;">
                            <?php echo $this->course_professor_review->rating_bar($course, "overall_recommendation", 0, 1, 0, "large", "red"); ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td align="center" valign="top">
                <!-- TEXTBOOK ========================== -->
                <table width="" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="2">
                            <h2 style="text-align: center; margin: 0; padding: 0 0 8px; font: bold 12px arial;">Textbook</h2>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <span style="font: normal 10px arial;">Mandatory&nbsp;</span>
                        </td>
                        <td align="left" valign="center" style="border-left: 1px solid #000;">
                            <?php echo $this->course_professor_review->rating_bar($course, "textbook_rating", 0, 4, 4, "small", "blue"); ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <span style="font: normal 10px arial;">Recommended&nbsp;</span>
                        </td>
                        <td align="left" valign="center" style="border-left: 1px solid #000;">
                            <?php echo $this->course_professor_review->rating_bar($course, "textbook_rating", 0, 4, 3, "small", "green"); ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <span style="font: normal 10px arial;">Not Necessary&nbsp;</span>
                        </td>
                        <td align="left" valign="center" style="border-left: 1px solid #000;">
                            <?php echo $this->course_professor_review->rating_bar($course, "textbook_rating", 0, 4, 2, "small", "yellow"); ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <span style="font: normal 10px arial;">Useless <span style="font-size: 9px;">(don't buy!)</span>&nbsp;</span>
                        </td>
                        <td align="left" valign="center" style="border-left: 1px solid #000;">
                            <?php echo $this->course_professor_review->rating_bar($course, "textbook_rating", 0, 4, 1, "small", "red"); ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <span style="font: normal 10px arial;">N/A&nbsp;</span>
                        </td>
                        <td align="left" valign="center" style="border-left: 1px solid #000;">
                            <?php echo $this->course_professor_review->rating_bar($course, "textbook_rating", 0, 4, 0, "small", "white"); ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td align="right" valign="top">
                <!-- Attendance ==================================== -->
                <table width="" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="2">
                            <h2 style="text-align: center; margin: 0; padding: 0 0 8px; font: bold 12px arial;">Attendance</h2>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <span style="font: normal 10px arial;">Mandatory&nbsp;</span>
                        </td>
                        <td align="left" valign="center" style="border-left: 1px solid #000;">
                            <?php echo $this->course_professor_review->rating_bar($course, "attendance_rating", 1, 4, 4, "small", "blue"); ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <span style="font: normal 10px arial;">Recommended&nbsp;</span>
                        </td>
                        <td align="left" valign="center" style="border-left: 1px solid #000;">
                            <?php echo $this->course_professor_review->rating_bar($course, "attendance_rating", 1, 4, 3, "small", "green"); ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <span style="font: normal 10px arial;">Not Necessary&nbsp;</span>
                        </td>
                        <td align="left" valign="center" style="border-left: 1px solid #000;">
                            <?php echo $this->course_professor_review->rating_bar($course, "attendance_rating", 1, 4, 2, "small", "yellow"); ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <span style="font: normal 10px arial;">Useless <span style="font-size: 9px;">(don't go!)</span>&nbsp;</span>
                        </td>
                        <td align="left" valign="center" style="border-left: 1px solid #000;">
                            <?php echo $this->course_professor_review->rating_bar($course, "attendance_rating", 1, 4, 1, "small", "red"); ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <?php if (!$reviews) { ?>
    <div style="height: 240px; width: 540px; margin-top: -240px; opacity: 0.75; filter: alpha(opacity=75); background: #FFF;">
    </div>
    <div style="position: relative; margin-top: -175px; height: 175px;">
    <?php } ?>
    <div style="width: 540px; margin: 20px 0; text-align: center;">
        <?php if (!$reviews) { ?>
        <div style="margin: 0; padding: 5px 0; font-weight: bold;">
            No reviews yet - Be the first!
        </div>
        <?php } ?>
        <a href="#course_professor_review" class="button yellow review_lightbox_link">
            Rate this Course! &#9658;
        </a>
    </div>
    <?php if (!$reviews) { ?>
    </div>
    <?php } ?>

    <!-- REVIEWS =============================================================== -->
    <div style="padding: 30px 0 10px; border-bottom: 1px solid #333; width: 540px;">
        <h2 style="float: left; font: bold 18px arial; margin: 0px;">
            User Reviews
        </h2>
        <div style="float: right; padding-top: 5px; color: #888;"><?php $this->pagination->display_current_positions(); ?></div>
        <div style="clear: both;"></div>
    </div>

    <?php
        if ($reviews)
        {
            foreach($reviews as $review)
            {
                $author = $review_authors[$review['id']];
                $professor = $review_professors[$review['id']];
    ?>
    <div style="width: 520px; padding: 10px; border-bottom: 1px solid #333;">
        <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
            <tr>
                <td rowspan="2" style="width: 145px;" valign="top">
                    <table border="0" cellspacing="2" cellpadding="0" style="margin: 0px 0px 0px 0px;">
                        <tr>
                            <td align="right">
                                <span style="font: bold 11px arial;">
                                    Workload
                                </span>
                            </td>
                            <td style="width: 80px;">
                                <div style="margin-left: 5px; height: 12px; width: 60px; background: transparent url('<?php echo site_url()."image/rating/sad_smiley_face_small.gif"; ?>') repeat-x scroll top; text-align: left;">
                                    <div style="height: 12px; width: <?php echo round($review['workload_rating'] * 60.0 / 5.0); ?>px; background: transparent url('<?php echo site_url()."image/rating/sad_smiley_face_small.gif"; ?>') repeat-x scroll left bottom;">
                                        &nbsp;
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                <span style="font: bold 11px arial;">
                                    Easiness
                                </span>
                            </td>
                            <td>
                                <div style="margin-left: 5px; height: 12px; width: 60px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll top; text-align: left;">
                                    <div style="height: 12px; width: <?php echo round($review['easiness_rating'] * 60.0 / 5.0); ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll left bottom;">
                                        &nbsp;
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                <span style="font: bold 11px arial;">
                                    Interest
                                </span>
                            </td>
                            <td>
                                <div style="margin-left: 5px; height: 12px; width: 60px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll top; text-align: left;">
                                    <div style="height: 12px; width: <?php echo round($review['interest_rating'] * 60.0 / 5.0); ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll left bottom;">
                                        &nbsp;
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" colspan="2">
                                <div style="font-size: 11px; margin: 8px 0; line-height: 14px;">
                                    Taken with:
                                    <br/>
                                    <a href="<?php echo site_url().string2uri($school['full_name'])."/professors/".string2uri($professor['first_name'])."_".string2uri($professor['last_name']); ?>">
                                        <?php echo $professor['last_name'] . ", " . $professor['first_name']; ?>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="transparent">
                            <td align="right">
                                <span style="font: bold 11px arial;">
                                    Knowledge
                                </span>
                            </td>
                            <td>
                                <div style="margin-left: 5px; height: 12px; width: 60px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll top; text-align: left;">
                                    <div style="height: 12px; width: <?php echo round($review['knowledge_rating'] * 60.0 / 5.0); ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll left bottom;">
                                        &nbsp;
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="transparent">
                            <td align="right">
                                <span style="font: bold 11px arial;">
                                    Helpful
                                </span>
                            </td>
                            <td>
                                <div style="margin-left: 5px; height: 12px; width: 60px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll top; text-align: left;">
                                    <div style="height: 12px; width: <?php echo round($review['helpful_rating'] * 60.0 / 5.0); ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll left bottom;">
                                        &nbsp;
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="transparent">
                            <td align="right">
                                <span style="font: bold 11px arial;">
                                    Awesome
                                </span>
                            </td>
                            <td>
                                <div style="margin-left: 5px; height: 12px; width: 60px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll top; text-align: left;">
                                    <div style="height: 12px; width: <?php echo round($review['awesome_rating'] * 60.0 / 5.0); ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll left bottom;">
                                        &nbsp;
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td align="left" valign="top">
                    <p><?php echo $review['review_text']; ?></p>
                    <div style="font-size: 10px; color: #666; margin-top: 5px;">
                        <?php
                            //echo date("F Y", strtotime($review['date_created']));
                            echo date("F jS Y", strtotime($review['date_created']));
                            if (!$review['anonymous']) { echo " - " . $review_authors[$review['id']]['username']; }
                            echo "\n";
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td align="right" valign="bottom">
                    <span style="font: bold 10px arial; color: #444;">Textbook: </span>&nbsp;
                    <span style="font: normal 10px arial; color: #444;"><?php echo $this->course_professor_review->textbook_rating($review); ?></span>
                    <br/>
                    <span style="font: bold 10px arial; color: #444;">Attendance: </span>&nbsp;
                    <span style="font: normal 10px arial; color: #444;"><?php echo $this->course_professor_review->attendance_rating($review); ?></span>
                    <br/>
                    <span style="font: bold 11px arial;">Overall Recommendation: </span>
                    <span style="font: normal 11px tahoma, arial;">&nbsp;<?php echo $this->course_professor_review->overall_recommendation($review); ?></span>
                </td>
            </tr>
        </table>
    </div>
    <?php
            }
        }
    ?>

    <div style="margin-top: 15px; float: left; width: 540px;">
        <div style="float: left;">
<?php $this->pagination->display_page_links(); ?>
        </div>
        <a href="#course_professor_review" class="button yellow small" style="float: right;">Rate this Course! &#9658;</a>
    </div>

</div>

<!-- ############################################################### -->
<div class="right_column">

<!------------------------ START RECTANGLE BANNER AD -------------------------->
<?php $this->load->view("banners/notesolution_rectangle"); ?>
<!------------------------- END RECTANGLE BANNER AD --------------------------->

    <h2 style="font: bold 14px arial; padding: 20px 0 0px; margin: 0px;">
        Professors
    </h2>
    <div style="padding-bottom: 15px; color: #555;">
        who teach <?=$course['course_code']?>
    </div>
    
    <table border="0" cellspacing="0" cellpadding="0">
        <tbody>
<?php foreach($professors as $professor) { ?>
            <tr>
                <td align="left" valign="top" style="height: 45px;">
                    <a href="<?php echo site_url().string2uri($school['full_name'])."/professors/".string2uri($professor['first_name'])."_".string2uri($professor['last_name']); ?>">
                        <img src="<?php echo site_url()."image/icon/professors.png"; ?>" style="margin-right: 5px;" />
                    </a>
                </td>
                <td align="left" valign="top" style="padding-top: 5px;">
                    <a href="<?php echo site_url().string2uri($school['full_name'])."/professors/".string2uri($professor['first_name'])."_".string2uri($professor['last_name']); ?>">
                        <strong><?php echo $professor['last_name'] . ", " . $professor['first_name']; ?></strong>
                    </a>
                    <div class="transparent" style="padding-top: 2px;">
<?php   if ($professor['total_reviews'] > 0) { ?>
                        <div style="margin-right: 5px; height: 12px; width: 60px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll top; text-align: left; float: left;">
                            <div style="height: 12px; width: <?php echo round($professor['overall_rating'] * 60.0 / 5.0); ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll left bottom;">
                                &nbsp;
                            </div>
                        </div>
                        <span style="float: left; font: normal 10px arial;">
                            <?php echo $professor['total_reviews'];  ?> reviews
                        </span>
<?php   } else { ?>
                        <span style="float: left; font: normal 11px arial;">
                            No reviews yet
                        </span>
<?php   } ?>
                    </div>
                </td>
            </tr>
<?php } ?>
        </tbody>
    </table>

    <p style="margin-top: 5px;">
        <?php echo empty($professors)? "Be the first! " : "Is this list incomplete? "; ?>
        Add a professor to this course by
        <a href="#course_professor_review" class="review_lightbox_link">
             Rating the Course!
        </a>
    </p>

</div>
<?php

/* End of file course_view.php */
/* Location: ./application/views/course/course_view.php */