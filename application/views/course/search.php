<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
////////////////////////////////////////////////////////////////////////////////
// STUDYMONKEY.CA | COURSE SELECTION
////////////////////////////////////////////////////////////////////////////////
/*
 * This where users can browse the course directory
 */
$INITIALIZE_PATH = "../_include/initialize";
require_once($INITIALIZE_PATH.'/initialize.php');

$menu_this_page = 'Find Courses';

if ($_GET['action'] == 'submit_search') {
    if ($_GET['course_id']) {
        header('Location: '. CLIENT_HTTP .'/user/course.php?id=' . $_GET['course_id']);
        exit();
    }
}

// PAGINATE THE TABLE
if ($_GET) {
    $pagination = new pagination(20);
    $pagination->order_by = "relevance";  // Default to relevance in case of search_terms
    $pagination->get_params($_GET);
    $pagination->sql_params = "school_id = " . session::school_id();
    if (!empty($pagination->search)) {
        foreach(explode(" ", $pagination->search) as $i_search) {
            $pagination->sql_params .= " AND (course_code LIKE '%{$i_search}%' OR";
            $pagination->sql_params .= " course_title LIKE '%{$i_search}%')";
        }
        // Sort by relevance
        if ($pagination->order_by == "relevance") {
            $pagination->order_by_relevance = "course_code LIKE '{$pagination->search}%'";
            $pagination->sort_descending = true;
        }
    } else {
        // No search terms, sort by course_code by default
        if ($pagination->order_by == "relevance") {
            $pagination->order_by = "course_code";
        }
    }
    $courses = course::paginate($pagination);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">

<head>
    <?php require_once(SERVER_HTTP.'/_include/layout/html_head.php'); ?>
    <script>
        $(document).ready(function(){

            // AUTOCOMPLETE COURSE_CODE
            $("#search").autocomplete("<?php echo CLIENT_HTTP."/user/autocomplete_course.php"; ?>",{
                minChars: 1,
                selectFirst: false,
                autoFill: false,
                mustMatch: false,
                width: "310"
            }).result(function(event, data, formatted){ // Update course_id
                if (data) {
                    $(this).prev().val(data[1]);
                    $("#form_search_course").submit();
                }
            }).click(function(event){
                $(this).search();
                $(this).select();
            })<?php if (empty($_GET['search'])) { echo ".select()"; } ?>;

        });
    </script>
</head>

<body>

<?php include SERVER_HTTP.'/_include/layout/header.php'; ?>

<div style="width: 750px; margin: 10px auto; padding: <?php echo ($_GET)? "0px" : "50px 0px 75px"; ?>; text-align: center;">

    <h1 style="text-align: center; padding: 5px 10px 0px;">
        <?php
            if (!$_GET) {
                echo "Search for ";
            } else if ($_GET['search'] == "") {
                echo "Browsing ALL ";
            } else {
                echo "Searching for <span style='font: bold 24px arial; color: #352;'>[ {$pagination->search} ]</span> in ";
            }
        ?>
        Courses
    </h1>

    <span style="text-align: center;">
        at <?php echo school::find_by_id(session::school_id())->full_name . "\n"; ?>
    </span>
    <br/>
    <br/>

    <div class="<?php echo (!$_GET)? "search_box_huge" : "search_box"; ?>">
        <form action="" method="get" class="" id="form_search_course">
            <div style="float: left;">
                <input type="hidden" value="" name="course_id" id="course_id" />
                <input type="text" class="input_text_main" name="search" id="search" value="<?php echo $_GET['search']; ?>" />
            </div>
            <input type="hidden" value="submit_search" name="action" />
            <input type="submit" value="Search &#9658;" class="input_submit_main input_submit_search" id="search_submit" onclick="this.blur()"/>
        </form>
    </div>

    <?php if (!$_GET) { ?>
    <br/>
    <span style="text-align: center;">
        <?php $this_school = school::find_by_id(session::school_id()); ?>
        <a href="<?php echo CLIENT_HTTP."/user/course_find.php?action=submit_search"; ?>" id="view_all">
            View All <?php echo $this_school->total_courses; ?> Courses
        </a>
    </span>
    <?php } ?>

<?php
    // DISPLAY LIST IF SEARCH QUERY IS PRESENT =================================
    if ($_GET) {
?>
    <?php $pagination->display_current_positions('right'); ?>
    <?php $pagination->display_page_links('right'); ?>
    <br/>

    <form action="<?php echo CLIENT_HTTP."/user/course_enrollment_handler.php"; ?>" method="post">
        <input type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>" name="destination_page" />
        <input type="hidden" value="add_course_to_profile" name="action" />
        <table border="0" cellspacing="0" cellpadding="12" width="100%" class="list">
            <thead>
                <tr>
                    <th width="110" align="left">&nbsp;&nbsp;<?php $pagination->display_sort_link('course_code' , 'Course Code'); ?></th>
                    <th width=""    align="left"><?php $pagination->display_sort_link('course_title', 'Title'); ?></th>
                    <!--
                    <th width="120" align="left"><?php $pagination->display_sort_link('date_modified' , 'Activity'); ?></th>
                    -->
                    <th width="115" align="left" class="last_cell">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
<?php
    // Check courses already added to user's profile
    $profile_courses = course_enrollment::find_by_user_id(session::user_id());
    $profile_courses_id;
    foreach ($profile_courses as $i_course) {
        $profile_courses_id[] = $i_course->course_id;
    }
    $oddeven = 0;
    foreach($courses as $current_course) {
        $oddeven++;
?>
                <tr class="<?php echo ($oddeven & 1)? 'odd' : 'even'; ?>">
                    <td align="left">
                        &nbsp;&nbsp;
                        <a href="<?php echo CLIENT_HTTP."/user/course.php?id=".$current_course->id; ?>" style="font-size: 15px;">
                            <?php echo $current_course->course_code;  ?>
                        </a>
                    </td>
                    <td align="left"  >
                        <a href="<?php echo CLIENT_HTTP."/user/course.php?id=".$current_course->id; ?>" style="color: #000;">
                            <b><?php echo $current_course->course_title; ?></b>
                        </a>
                        <!--
                        <br/>
                        <?php
                            if (strlen($current_course->description) > 110){
                                echo substr($current_course->description, 0, 100) . "... ";
                                echo "(<a href='".CLIENT_HTTP."/user/course.php?id=".$current_course->id."'>read more</a>)\n";
                            } else {
                                echo $current_course->description . "\n";
                            }
                        ?>
                        -->
                    </td>
                    <!--
                    <td align="left"  >
                        <a href="<?php echo CLIENT_HTTP."/user/course.php?id=".$current_course->id; ?>" style="font-weight: normal;">
                            <?php
                                echo ($current_course->total_professors == 0)? "No" : "<b>" . $current_course->total_professors;
                                echo ($current_course->total_professors == 1)? " professor" : " professors";
                                echo ($current_course->total_professors == 0)? "" : "</b>";
                                echo ($current_course->total_professors == 0)? " added" : "";
                            ?>
                        </a>
                        <br/>
                        <a href="<?php echo CLIENT_HTTP."/user/course.php?id=".$current_course->id; ?>" style="font-weight: normal;">
                            <?php 
                                echo ($current_course->total_notes == 0)? "No" : "<b>" . $current_course->total_notes;
                                echo ($current_course->total_notes == 1)? " note" : " notes";
                                echo ($current_course->total_notes == 0)? "" : "</b>";
                                echo ($current_course->total_notes == 0)? " yet" : " for sale";
                            ?>
                        </a>
                        <br/>
                        <a href="<?php echo CLIENT_HTTP."/user/course.php?id=".$current_course->id; ?>" style="font-weight: normal;">
                            <?php
                                echo ($current_course->total_reviews == 0)? "No" : "<b>" . $current_course->total_reviews;
                                echo ($current_course->total_reviews == 1)? " review" : " reviews";
                                echo ($current_course->total_reviews == 0)? "" : "</b>";
                                echo ($current_course->total_reviews == 0)? " yet" : "";
                            ?>
                        </a>
                    </td>
                    -->
                    <td align="center" class="last_cell">
<?php if (in_array($current_course->id, $profile_courses_id)) { ?>
                        Already Added
<?php } else { ?>
                        <input type="submit" class="input_submit_main" name="<?php echo $current_course->id; ?>" value="Add Course" />
<?php } ?>
                    </td>
                </tr>
<?php } ?>
                <tr>
                    <td class="box" colspan="5">
                        <p style="padding: 5px; text-align: center; font-weight: bold;">
                            Can't find your course?
                            <a href="<?php echo CLIENT_HTTP."/user/course_create.php"; ?>" target="_blank" onclick="
                                window.open(
                                    '<?php echo CLIENT_HTTP."/user/course_create.php"; ?>',
                                    'add_window_course',
                                    'menubar=no, toolbar=no, width=600, height=400');
                                return false;
                                ">
                                Create a Course
                            </a>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>

    <br/>
    <?php $pagination->display_page_links('right'); ?>
    <?php $pagination->display_current_positions('right'); ?>

<?php
    }   // END OF LIST =========================================================
?>

</div>

<?php include SERVER_HTTP.'/_include/layout/footer.php' ?>

</body>

</html>