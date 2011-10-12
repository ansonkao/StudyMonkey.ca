<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course_professor_review_model extends StudyMonkey_Model
{
    protected $TABLE_NAME;

    protected $db_fields = array
        ( 'school_id'
        , 'user_id'
        , 'course_id'
        , 'professor_id'
        , 'course_professor_id'
        , 'workload_rating'
        , 'easiness_rating'
        , 'interest_rating'
        , 'knowledge_rating'
        , 'helpful_rating'
        , 'awesome_rating'
        , 'attendance_rating'
        , 'textbook_rating'
        , 'review_text'
        , 'overall_recommendation'
        , 'anonymous'
        );

    function __construct()
    {
        parent::__construct();
        $this->TABLE_NAME = 'course_professor_review';
    }

    function count_by_course_professor_id( $in_course_professor_id )
    {
        global $database;
        if (empty($in_course_professor_id)) { return false; }
        return self::count_all("course_professor_id={$in_course_professor_id}");
    }

    function count_by_user_id( $user_id )
    {
        if (empty($user_id)) { return false; }
        return self::count_all("user_id={$user_id}");
    }

    function count_by_course_id( $course_id )
    {
        $result = $this->db->query("SELECT COUNT(*) FROM course_professor_review WHERE course_id = ?", array( $course_id ) );
        return array_shift($result->row_array());
    }

    function count_by_professor_id( $professor_id )
    {
        $result = $this->db->query("SELECT COUNT(*) FROM course_professor_review WHERE professor_id = ?", array( $professor_id ) );
        return array_shift($result->row_array());
    }

    function find_by_course_id( $course_id )
    {
        $result = $this->db->query("SELECT * FROM course_professor_review WHERE course_id = ? ORDER BY id DESC", array( $course_id ) );
        return $result->result_array();
    }

    function find_by_professor_id( $professor_id )
    {
        $result = $this->db->query("SELECT * FROM course_professor_review WHERE professor_id = ? ORDER BY id DESC", array( $professor_id ) );
        return $result->result_array();
    }

    function paginate_by_professor_id( $professor_id, $page, $rows_per_page )
    {
        // Make sure a valid page value
        if ( ! is_numeric($page) OR $page < 1 )
            return false;

        // Calculate the offset and make sure it is within the valid range
        $total_rows = $this->count_by_professor_id( $professor_id );
        $offset = ( $page - 1 ) * $rows_per_page;
        if ( $offset >= $total_rows )
            return false;

        // Good to go, run the query
        $result = $this->db->query( "SELECT * FROM course_professor_review WHERE professor_id = ? ORDER BY id DESC LIMIT ? , ?", array( $professor_id, $offset, $rows_per_page ) );
        return $result->result_array();
    }

    function paginate_by_course_id( $course_id, $page, $rows_per_page )
    {
        // Make sure a valid page value
        if ( ! is_numeric($page) OR $page < 1 )
            return false;

        // Calculate the offset and make sure it is within the valid range
        $total_rows = $this->count_by_course_id( $course_id );
        $offset = ( $page - 1 ) * $rows_per_page;
        if ( $offset >= $total_rows )
            return false;

        // Good to go, run the query
        $result = $this->db->query( "SELECT * FROM course_professor_review WHERE course_id = ? ORDER BY id DESC LIMIT ? , ?", array( $course_id, $offset, $rows_per_page ) );
        return $result->result_array();
    }

    public function textbook_rating($course_professor_review) {
        $text = "";
        switch($course_professor_review['textbook_rating']) {
            case 4: $text .= "Mandatory"    ; break;
            case 3: $text .= "Recommended"  ; break;
            case 2: $text .= "Not necessary"; break;
            case 1: $text .= "Useless"      ; break;
            default:
            case 0: $text .= "N/A"         ; break;
        }
        return $text;
    }

    public function attendance_rating($course_professor_review) {
        $text = "";
        switch($course_professor_review['attendance_rating']) {
            case 4: $text .= "Mandatory"    ; break;
            case 3: $text .= "Recommended"  ; break;
            case 2: $text .= "Not necessary"; break;
            case 1: $text .= "Useless"      ; break;
            default:
            case 0: $text .= "N/A"         ; break;
        }
        return $text;
    }

    public function overall_recommendation($course_professor_review) {
        $text = "";
        switch($course_professor_review['overall_recommendation']) {
            case 1:  $text .= "Yes"; break;
            case 0:  $text .=  "No"; break;
            default: $text .= "N/A"; break;
        }
        return $text;
    }

    /* Outputs a div and a span, styled for displaying a bar in a bar graph.
     *
     * Usage example:
     *   rating_bar("textbook_rating", 0, 4, 2, "small", "yellow");
     *     ...will output html for a small labelled yellow bar for the
     *     textbook_rating_2 value.  Width of the bar is determined by
     *     rating_bar_width(), see below.
     */
    public function rating_bar
        ( $course_or_professor
        , $rating
        , $domain_min
        , $domain_max
        , $which
        , $size
        , $colour
        )
    {
        // Non-empty ratings
        if( $course_or_professor['total_reviews'] )
        {
            $label = round( $course_or_professor[$rating . "_" . $which] * 100.0 / $course_or_professor['total_reviews'] ) . "%";
        }

        // Blank default
        else
        {
            $colour = "white";
            $label = "N/A";
        }

        switch( $colour )
        {
            case "blue"  : $border_colour = "#008"; $background_colour = "#27F"; break;
            case "green" : $border_colour = "#080"; $background_colour = "#3F3"; break;
            case "yellow": $border_colour = "#880"; $background_colour = "#FC3"; break;
            case "red"   : $border_colour = "#800"; $background_colour = "#F33"; break;
            case "white" : $border_colour = "#888"; $background_colour = "#FFF"; break;
        }
        switch( $size )
        {
            case "large": $bar_max_length = 100; $bar_height = 10; $font_size = 12; $margin = "5px 5px 2px 0px"; break;
            case "small": $bar_max_length =  50; $bar_height =  5; $font_size = 10; $margin = "8px 5px 0px 0px"; break;
        }
        echo "<div style='height: {$bar_height}px; width: {$this->rating_bar_width($course_or_professor, $rating, $domain_min, $domain_max, $which, $bar_max_length)}px; border: 1px solid {$border_colour}; border-left: 0px; background: {$background_colour}; float: left; margin: {$margin};'>";
        echo "</div>";
        echo "<span style='font: normal {$font_size}px arial;'>({$label})</span>\n";
    }

    /* For use with outputting course reviews as bar graphs.
     * This function outputs the width of the specified bar.
     *
     * Usage example:
     *   rating_bar_width("textbook_rating", 0, 4, 2, 100);
     *     ...will output the length of the bar for textbook_rating_2,
     *     calculated with respect to the domain of bars
     *     (0 - 4 indicating textbook_rating_0 through textbook_rating_4)
     *     to be displayed in this particular bar graph.
     *     The output values are normalized by the length of the greatest
     *     bar in the domain of bars so that cases with a pretty even
     *     distribution between the bars (e.g. 20%, 20%, 20%, 20%, 20%) don't
     *     appear so small and squished next to other graphs (e.g. 100%, 0%, ...)
     */
    function rating_bar_width
        ( $course_or_professor
        , $rating
        , $domain_min
        , $domain_max
        , $which
        , $bar_max_length
        )
    {
        // Output zero if no ratings
        if( empty($course_or_professor['total_reviews']) )
        {
            return $bar_max_length;
        }

        // Find value of greateset bar to be used as a divider
        $divider = 0;
        for( $i = $domain_min; $i <= $domain_max; $i++ )
        {
            if ($course_or_professor[$rating . "_" . $i] > $divider)
            {
                $divider = $course_or_professor[$rating . "_" . $i];
            }
        }

        // Normalize width in a logarithmic manner
        $multiplier = ( $divider + $course_or_professor['total_reviews'] )/( 2 * $divider );
        $result = round( $course_or_professor[$rating . "_" . $which] * $multiplier * $bar_max_length / $course_or_professor['total_reviews'] );
        return 1 + $result; // Add 1 pixel in case the value is zero so user can still see the bar is there.
    }

    /* Distinguishes if the review is submitted by a course or professor page
     * and identifies the corresponding records and saves them in this object.
     * Returns any errors, or false if no errors.
     */
    function process_new
        (   $course_or_professor_page
        ,   $course_or_professor_page_id
        ,   $course_or_professor_id
        , & $review
        ,   $school
        )
    {
        $new_course_professor = array();
        switch( $course_or_professor_page )
        {
            case 'course':
                $new_course_professor['course_id'] = $course_or_professor_page_id;
                $new_course_professor['professor_id'] = $course_or_professor_id;
                break;
            case 'professor':
                $new_course_professor['course_id'] = $course_or_professor_id;
                $new_course_professor['professor_id'] = $course_or_professor_page_id;
                break;
        }

        $this->load->model('course_professor');
        $found_course_professor = $this->course_professor->find_by_course_and_professor_id( $new_course_professor['course_id'], $new_course_professor['professor_id'] );

        // Found existing record for this course/professor combo
        if( $found_course_professor )
        {
            // Validate school
            if( $found_course_professor['school_id'] != $school['id'] )
            {
                return Notification::error("Invalid school.");
            }

            // Valid, update this rating with the course/professor values
            $review['course_id']            = $found_course_professor['course_id'];
            $review['professor_id']         = $found_course_professor['professor_id'];
            $review['course_professor_id']  = $found_course_professor['id'];

            return Notification::success();
        }

        // None found, try creating new course/professor
        else
        {
            // Validate
            $this->load->model('course');
            $this->load->model('professor');
            $course = $this->course->find_by_id( $new_course_professor['course_id'] );
            $professor = $this->professor->find_by_id( $new_course_professor['professor_id'] );
            if( empty( $course ) )
                return Notification::error("Invalid course.");
            if( empty( $professor ) )
                return Notification::error("Invalid professor.");
            if( $course['school_id'] != $professor['school_id'] )
                return Notification::error("Invalid course / professor.");
            
            // Valid, save new coures/professor
            $new_course_professor['school_id'] = $school['id'];
            $this->course_professor->save( $new_course_professor );

            $review['course_id']            = $new_course_professor['course_id'];
            $review['professor_id']         = $new_course_professor['professor_id'];
            $review['course_professor_id']  = $new_course_professor['id'];

            return Notification::success();
        }
    }

}

?>