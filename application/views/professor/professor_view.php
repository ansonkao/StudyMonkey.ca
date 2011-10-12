<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * PROFESSOR PAGE
 */
?>
<!--  =================== START COURSE/PROFESSOR-REVIEW ==================== -->
<?php
    $course_professor_review_params = array();
    $course_professor_review_params['course_or_professor'] = "course";
    $course_professor_review_params['this_course_or_professor_id'] = $professor['id'];
    $this->load->view('rating/course_professor_review', $course_professor_review_params );
?>
<!-- ===================== END COURSE/PROFESSOR-REVIEW ===================== -->

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
                                case 0: echo "No ratings yet"; break;
                                case 1: echo "Based on 1 rating"; break;
                                default: // Multiple reviews
                                    echo "Based on ".$total_reviews." ratings";
                            }
                        ?>
                    </span>
                </h1>
                <div style="height: 48px; width: 240px; background: transparent url('<?php echo site_url()."image/rating/star_rating_large.gif"; ?>') repeat-x scroll top; text-align: left; float: left;">
                    <div style="height: 48px; width: <?php if ($professor['overall_rating']) { echo round($professor['overall_rating'] * 240.0 / 5.0); } else { echo "0"; } ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating_large.gif"; ?>') repeat-x scroll left bottom;">
                        &nbsp;
                    </div>
                </div>
                <span style="text-align: left; font: bold 42px arial; padding-left: 5px;"><?php if ($professor['total_reviews']) { printf("%2.1f", $professor['overall_rating']); } else { echo "N/A"; } ?></span>
            </td>
            <td colspan="2" style="height: 10px;">
                <!-- SPACER -->
            </td>
        </tr>
        <tr>
            <td>
                <h2 style="text-align: right; margin: 0; padding: 5px 3px 5px 0; font: bold 12px arial;">Knowledge</h2>
            </td>
            <td style="width: 120px;">
                <div style="margin-left: 5px; height: 24px; width: 120px; background: transparent url('<?php echo site_url()."image/rating/star_rating.gif"; ?>') repeat-x scroll top; text-align: left;">
                    <div style="height: 24px; width: <?php if ($professor['knowledge_rating']) { echo round($professor['knowledge_rating'] * 120.0 / 5.0); } else { echo "0"; } ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating.gif"; ?>') repeat-x scroll left bottom;">
                        &nbsp;
                    </div>
                </div>
            </td>
            <td valign="bottom">
                <span style="font: bold 16px arial">&nbsp;<?php if ($professor['total_reviews']) { printf("%2.1f", $professor['knowledge_rating']); } else { echo "N/A"; } ?></span>
            </td>
        </tr>
        <tr>
            <td>
                <h2 style="text-align: right; margin: 0; padding: 5px 3px 5px 0; font: bold 12px arial;">Helpful</h2>
            </td>
            <td>
                <div style="margin-left: 5px; height: 24px; width: 120px; background: transparent url('<?php echo site_url()."image/rating/star_rating.gif"; ?>') repeat-x scroll top; text-align: left;">
                    <div style="height: 24px; width: <?php if ($professor['helpful_rating']) { echo round($professor['helpful_rating'] * 120.0 / 5.0); } else { echo "0"; } ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating.gif"; ?>') repeat-x scroll left bottom;">
                        &nbsp;
                    </div>
                </div>
            </td>
            <td valign="bottom">
                <span style="font: bold 16px arial">&nbsp;<?php if ($professor['total_reviews']) { printf("%2.1f", $professor['helpful_rating']); } else { echo "N/A"; } ?></span>
            </td>
        </tr>
        <tr>
            <td>
                <h2 style="text-align: right; margin: 0; padding: 5px 3px 5px 0; font: bold 12px arial;">Awesome</h2>
            </td>
            <td>
                <div style="margin-left: 5px; height: 24px; width: 120px; background: transparent url('<?php echo site_url()."image/rating/star_rating.gif"; ?>') repeat-x scroll top; text-align: left;">
                    <div style="height: 24px; width: <?php if ($professor['awesome_rating']) { echo round($professor['awesome_rating'] * 120.0 / 5.0); } else { echo "0"; } ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating.gif"; ?>') repeat-x scroll left bottom;">
                        &nbsp;
                    </div>
                </div>
            </td>
            <td valign="bottom">
                <span style="font: bold 16px arial">&nbsp;<?php if ($professor['total_reviews']) { printf("%2.1f", $professor['awesome_rating']); } else { echo "N/A"; } ?></span>
            </td>
        </tr>
    </table>

    <!-- BAR GRAPHS ==================================== -->
    <table width="525" border="0" cellspacing="0" cellpadding="0" style="margin: 20px 0 20px; line-height: 1.6em;">
        <tr>
            <td align="right" valign="top">
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
                            <?php echo $this->course_professor_review->rating_bar( $professor, "overall_recommendation", 0, 1, 1, "large", "green" ); ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right" valign="top" style="padding: 5px 0px 5px;">
                            <span style="font: normal 12px arial;">No&nbsp;</span>
                        </td>
                        <td align="left" valign="center" style="border-left: 1px solid #000; padding: 5px 5px 5px 0px;">
                            <?php echo $this->course_professor_review->rating_bar( $professor, "overall_recommendation", 0, 1, 0, "large", "red" ); ?>
                        </td>
                    </tr>
                </table>
            </td>
            <td align="center" valign="top">
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
                            <?php echo $this->course_professor_review->rating_bar( $professor, "attendance_rating", 1, 4, 4, "small", "blue" ); ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <span style="font: normal 10px arial;">Recommended&nbsp;</span>
                        </td>
                        <td align="left" valign="center" style="border-left: 1px solid #000;">
                            <?php echo $this->course_professor_review->rating_bar( $professor, "attendance_rating", 1, 4, 3, "small", "green" ); ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <span style="font: normal 10px arial;">Not Necessary&nbsp;</span>
                        </td>
                        <td align="left" valign="center" style="border-left: 1px solid #000;">
                            <?php echo $this->course_professor_review->rating_bar( $professor, "attendance_rating", 1, 4, 2, "small", "yellow" ); ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <span style="font: normal 10px arial;">Useless <span style="font-size: 9px;">(don't go!)</span>&nbsp;</span>
                        </td>
                        <td align="left" valign="center" style="border-left: 1px solid #000;">
                            <?php echo $this->course_professor_review->rating_bar( $professor, "attendance_rating", 1, 4, 1, "small", "red" ); ?>
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
            Rate this Professor! &#9658;
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
                $course = $review_courses[$review['id']];
    ?>
    <div style="width: 520px; padding: 10px; border-bottom: 1px solid #333;">
        <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
            <tr>
                <td rowspan="2" style="width: 145px;" valign="top">
                    <table border="0" cellspacing="2" cellpadding="0" style="margin: 0px 0px 0px 0px;">
                        <tr>
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
                        <tr>
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
                        <tr>
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
                        <tr>
                            <td align="center" colspan="2">
                                <div style="font-size: 11px; margin: 8px 0; line-height: 14px;">
                                    Course:
                                    <br/>
                                    <a href="<?php echo site_url().string2uri($school['full_name'])."/courses/".string2uri($course['course_code']); ?>">
                                        <?php echo $course['course_code']; ?>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="transparent">
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
                        <tr class="transparent">
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
                        <tr class="transparent">
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
                    </table>
                </td>
                <td align="left" valign="top">
                    <p><?php echo $review['review_text']; ?></p>
                    <div style="font-size: 10px; color: #666; margin-top: 5px;">
                        <?php
                            //echo date("F Y", strtotime($review['date_created']));
                            echo date("F jS Y", strtotime($review['date_created']));
                            echo " - {$review['username']}";
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
        <a href="#course_professor_review" class="button yellow small review_lightbox_link" style="float: right;">Rate this Professor! &#9658;</a>
    </div>

</div>

<!-- ############################################################### -->
<div class="right_column">

<!-- ===================== START RECTANGLE BANNER AD ======================= -->
<?php $this->load->view("banners/notesolution_rectangle"); ?>
<!-- ====================== END RECTANGLE BANNER AD ======================== -->

    <h2 style="font: bold 14px arial; padding: 20px 0 0px; margin: 0px;">
        Courses
    </h2>
    <div style="padding-bottom: 15px; color: #555;">
        taught by <?=$professor['last_name']?>
    </div>
    
    <table border="0" cellspacing="0" cellpadding="0">
        <tbody>
<?php foreach($courses as $course) { ?>
            <tr>
                <td align="left" valign="top" style="height: 45px;">
                    <a href="<?php echo site_url().string2uri($school['full_name'])."/courses/".string2uri($course['course_code']); ?>">
                        <img src="<?php echo site_url()."image/icon/courses.png"; ?>" style="margin-right: 5px;" />
                    </a>
                </td>
                <td align="left" valign="top" style="padding-top: 5px;">
                    <a href="<?php echo site_url().string2uri($school['full_name'])."/courses/".string2uri($course['course_code']); ?>">
                        <strong><?php echo $course['course_code']; ?></strong>
                    </a>
                    <div class="transparent" style="padding-top: 2px;">
<?php   if ($course['total_reviews'] > 0) { ?>
                        <div style="margin-right: 5px; height: 12px; width: 60px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll top; text-align: left; float: left;">
                            <div style="height: 12px; width: <?php echo round($course['overall_rating'] * 60.0 / 5.0); ?>px; background: transparent url('<?php echo site_url()."image/rating/star_rating_small.gif"; ?>') repeat-x scroll left bottom;">
                                &nbsp;
                            </div>
                        </div>
                        <span style="float: left; font: normal 10px arial;">
                            <?php echo $course['total_reviews'];  ?> reviews
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
        <?php echo empty($courses)? "Be the first! " : "Is this list incomplete? "; ?>
        Add a course this professor teaches by
        <a href="#course_professor_review" class="review_lightbox_link">
             Rating the Professor!
        </a>
    </p>

</div>
<?php

/* End of file professor_view.php */
/* Location: ./application/views/professor/professor_view.php */