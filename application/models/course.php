<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course_model extends StudyMonkey_Model
{
    protected $TABLE_NAME;

    protected $db_fields = array
        ( 'course_code'
        , 'course_title'
        , 'school_id'
        , 'total_professors'
        , 'total_notes'
        , 'total_reviews'
        , 'overall_rating'
        , 'easiness_rating'
        , 'workload_rating'
        , 'interest_rating'
        , 'overall_recommendation_1'
        , 'overall_recommendation_0'
        , 'textbook_rating_4'
        , 'textbook_rating_3'
        , 'textbook_rating_2'
        , 'textbook_rating_1'
        , 'textbook_rating_0'
        , 'attendance_rating_4'
        , 'attendance_rating_3'
        , 'attendance_rating_2'
        , 'attendance_rating_1'
        );

    function __construct()
    {
        parent::__construct();
        $this->TABLE_NAME = 'course';
    }

    public function find_by_course_code($code)
    {
        $result = $this->db->query("SELECT * FROM course WHERE course_code = ? LIMIT 1", array( $code ) );
        return $result->row_array();
    }

    public static function autocomplete($in_search_term, $in_max, $in_school) {
        global $database;
        $search_term = mysql_real_escape_string($in_search_term);
        $max = (int)$in_max;
        $school = (int)$in_school;
        $sql = "SELECT DISTINCT * FROM ". get_class() . " WHERE school_id = " . $school;
        foreach(explode(" ", $search_term) as $i_search_term) {
            $sql .= " AND (course_code LIKE '%{$i_search_term}%' OR course_title LIKE '%{$i_search_term}%')";
        }
        $sql .= " ORDER BY course_code LIKE '{$search_term}%' DESC LIMIT $max";
        return self::find_by_sql($sql);
    }

    public function update_totals($which = NULL) {
        global $database;
        if (empty($which) || $which == "professors") {
            $this->total_professors = course_professor::count_by_course_id($this->id);
        }
        if (empty($which) || $which == "notes") {
            $this->total_notes = note::count_by_course_id($this->id);
        }
        if (empty($which) || $which == "course_professor_reviews") {
            $reviews = course_professor_review::find_by_course_id($this->id);
            $this->total_reviews = 0;
            $this->overall_rating = 0;
            $this->easiness_rating = 0;
            $this->workload_rating = 0;
            $this->interest_rating = 0;
            $this->overall_recommendation_1 = 0;
            $this->overall_recommendation_0 = 0;
            $this->textbook_rating_4 = 0;
            $this->textbook_rating_3 = 0;
            $this->textbook_rating_2 = 0;
            $this->textbook_rating_1 = 0;
            $this->textbook_rating_0 = 0;
            $this->attendance_rating_4 = 0;
            $this->attendance_rating_3 = 0;
            $this->attendance_rating_2 = 0;
            $this->attendance_rating_1 = 0;
            foreach ($reviews as $review) {
                $this->total_reviews++;
                switch ($review->overall_recommendation) {
                    case 1: $this->overall_recommendation_1++; break;
                    case 0: $this->overall_recommendation_0++; break;
                }
                switch ($review->textbook_rating) {
                    case 4: $this->textbook_rating_4++; break;
                    case 3: $this->textbook_rating_3++; break;
                    case 2: $this->textbook_rating_2++; break;
                    case 1: $this->textbook_rating_1++; break;
                    case 0: $this->textbook_rating_0++; break;
                }
                switch ($review->attendance_rating) {
                    case 4: $this->attendance_rating_4++; break;
                    case 3: $this->attendance_rating_3++; break;
                    case 2: $this->attendance_rating_2++; break;
                    case 1: $this->attendance_rating_1++; break;
                }
                $this->easiness_rating += $review->easiness_rating;
                $this->workload_rating += $review->workload_rating;
                $this->interest_rating += $review->interest_rating;
                // OVERALL RATING
                $this->overall_rating += 3 * $review->easiness_rating;
                $this->overall_rating += 3 * (6 - $review->workload_rating); // Flip workload because it is a negative quality
                $this->overall_rating += 3 * $review->interest_rating;
                $this->overall_rating += $review->knowledge_rating;
                $this->overall_rating += $review->helpful_rating;
                $this->overall_rating += $review->awesome_rating;
            }
            $this->overall_rating  /= (float)$this->total_reviews;
            $this->overall_rating  /= 12.0;
            $this->easiness_rating /= (float)$this->total_reviews;
            $this->workload_rating /= (float)$this->total_reviews;
            $this->interest_rating /= (float)$this->total_reviews;
        }
    }

    /* Outputs a div and a span, styled for displaying a bar in a bar graph.
     * 
     * Usage example:
     *   rating_bar("textbook_rating", 0, 4, 2, "small", "yellow");
     *     ...will output html for a small labelled yellow bar for the
     *     textbook_rating_2 value.  Width of the bar is determined by
     *     rating_bar_width(), see below.
     */
    public function rating_bar($course, $rating, $domain_min, $domain_max, $which, $size, $colour) {
        if ($course['total_reviews']) {
            $label = round($course[$rating . "_" . $which] * 100.0 / $course['total_reviews']) . "%";
        } else { // Default
            $colour = "white";
            $label = "N/A";
        }
        switch ($colour) {
            case "blue"  : $border_colour = "#008"; $background_colour = "#27F"; break;
            case "green" : $border_colour = "#080"; $background_colour = "#3F3"; break;
            case "yellow": $border_colour = "#880"; $background_colour = "#FC3"; break;
            case "red"   : $border_colour = "#800"; $background_colour = "#F33"; break;
            case "white" : $border_colour = "#888"; $background_colour = "#FFF"; break;
        }
        switch ($size) {
            case "large": $bar_max_length = 100; $bar_height = 10; $font_size = 12; $margin = "5px 5px 2px 0px"; break;
            case "small": $bar_max_length =  50; $bar_height =  5; $font_size = 10; $margin = "8px 5px 0px 0px"; break;
        }
        echo "<div style='height: {$bar_height}px; width: {$this->rating_bar_width($course, $rating, $domain_min, $domain_max, $which, $bar_max_length)}px; border: 1px solid {$border_colour}; border-left: 0px; background: {$background_colour}; float: left; margin: {$margin};'>";
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
    public function rating_bar_width($course, $rating, $domain_min, $domain_max, $which, $bar_max_length) {
        // Output zero if no ratings
        if (empty($course['total_reviews'])) {
            return $bar_max_length;
        }
        // Find value of greateset bar to be used as a divider
        $divider = 0;
        for ($i = $domain_min; $i <= $domain_max; $i++) {
            if ($course[$rating . "_" . $i] > $divider) {
                $divider = $course[$rating . "_" . $i];
            }
        }
        // Normalize width in a logarithmic manner
        $multiplier = ($divider + $course['total_reviews'])/(2 * $divider);
        $result = round($course[$rating . "_" . $which] * $multiplier * $bar_max_length / $course['total_reviews']);
        return 1 + $result; // Add 1 pixel in case the value is zero so user can still see the bar is there.
    }

    /* FORM VALIDATION
     * Returns an array of error strings corresponding to each
     * invalid parameter, otherwise returns false.
     */
    public static function validate_new($params) {
        $errors;
        // Course_code format
        if (strlen($params['course_code']) < 1) {
            $errors['course_code'] = "Please enter the course code!";
            return $errors;
        } else if (strlen($params['course_code']) > 15) {
            $errors['course_code'] = "We can only accept course codes up to 15 characters long.";
            return $errors;
        } else if (ctype_alnum($params['course_code']) == false) {
            $errors['course_code'] = "Course codes must be alphanumeric, no spaces please.";
            return $errors;
        }
        // Duplicate course
        $found_course = self::find_by_course_code($params['course_code']);
        if ($found_course && $found_course->school_id == session::school_id()) {
            $errors['course_code'] = "That course code already exists.";
            return $errors;
        }
        // Title
        if (empty($params['course_title'])) {
            $errors['course_title'] = "Please specify the course title.";
            return $errors;
        }
        return $errors;
    }
}
