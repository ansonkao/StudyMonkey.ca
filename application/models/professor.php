<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Professor_model extends StudyMonkey_Model
{
    protected $TABLE_NAME;

    protected $db_fields = array
        ( 'school_id'
        , 'first_name'
        , 'last_name'
        , 'department'
        , 'gender'
        , 'total_courses'
        , 'total_reviews'
        , 'overall_rating'
        , 'knowledge_rating'
        , 'helpful_rating'
        , 'awesome_rating'
        , 'overall_recommendation_1'
        , 'overall_recommendation_0'
        , 'attendance_rating_4'
        , 'attendance_rating_3'
        , 'attendance_rating_2'
        , 'attendance_rating_1'
        );

    function __construct()
    {
        parent::__construct();
        $this->TABLE_NAME = 'professor';
    }

    function find_by_course_id($course_id)
    {
        $result = $this->db->query(
            "SELECT * FROM professor p
             INNER JOIN course_professor cp
             WHERE p.id = cp.professor_id
                AND cp.course_id = ?
             ORDER BY p.last_name ASC"
            , array( $course_id )
            );
        return $result->result_array();
    }
    
    public static function autocomplete($in_search_term, $in_max, $in_school) {
        global $database;
        $search_term = mysql_real_escape_string($in_search_term);
        $max = (int)$in_max;
        $school = (int)$in_school;
        $sql = "SELECT DISTINCT * FROM ". get_class() . " WHERE school_id = " . $school;
        $order_by_relevance = "";
        foreach(explode(" ", $search_term) as $i_search_term) {
            $sql .= " AND (last_name LIKE '%{$i_search_term}%' OR first_name LIKE '%{$i_search_term}%')";
            $order_by_relevance .= "last_name LIKE '{$i_search_term}%' DESC, first_name LIKE '{$i_search_term}%' DESC, ";
        }
        $order_by_relevance .= "last_name DESC";
        $sql .= " ORDER BY {$order_by_relevance} LIMIT $max";
        return self::find_by_sql($sql);
    }

    public static function find_by_name($in_first_name, $in_last_name, $in_school_id) {
        $first_name = mysql_real_escape_string($in_first_name);
        $last_name = mysql_real_escape_string($in_last_name);
        $school_id = mysql_real_escape_string($in_school_id);
        return self::find_by_sql("SELECT * FROM " . get_class() . " WHERE school_id={$school_id} AND first_name='{$first_name}' AND last_name='{$last_name}'");
    }

    public function update_totals() {
        global $database;
        if (empty($which) || $which == "courses") {
            $this->total_courses = course_professor::count_by_professor_id($this->id);
        }
        if (empty($which) || $which == "course_professor_reviews") {
            $reviews = course_professor_review::find_by_professor_id($this->id);
            $this->total_reviews = 0;
            $this->overall_rating = 0;
            $this->knowledge_rating = 0;
            $this->helpful_rating = 0;
            $this->awesome_rating = 0;
            $this->overall_recommendation_1 = 0;
            $this->overall_recommendation_0 = 0;
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
                switch ($review->attendance_rating) {
                    case 4: $this->attendance_rating_4++; break;
                    case 3: $this->attendance_rating_3++; break;
                    case 2: $this->attendance_rating_2++; break;
                    case 1: $this->attendance_rating_1++; break;
                }
                $this->knowledge_rating += $review->knowledge_rating;
                $this->helpful_rating   += $review->helpful_rating;
                $this->awesome_rating   += $review->awesome_rating;
                // OVERALL RATING
                $this->overall_rating += $review->easiness_rating;
                $this->overall_rating += (6 - $review->workload_rating); // Flip workload because it is a negative quality
                $this->overall_rating += $review->interest_rating;
                $this->overall_rating += 3 * $review->knowledge_rating;
                $this->overall_rating += 3 * $review->helpful_rating;
                $this->overall_rating += 3 * $review->awesome_rating;
            }
            $this->overall_rating   /= (float)$this->total_reviews;
            $this->overall_rating   /= 12.0;
            $this->knowledge_rating /= (float)$this->total_reviews;
            $this->helpful_rating   /= (float)$this->total_reviews;
            $this->awesome_rating   /= (float)$this->total_reviews;
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
    public function rating_bar($rating, $domain_min, $domain_max, $which, $size, $colour) {
        if ($this->total_reviews) {
            $label = round($this->{$rating . "_" . $which} * 100.0 / $this->total_reviews) . "%";
        } else { // Default
            $colour = "white";
            $label = "N/A";
        }
        switch ($colour) {
            case "blue"  : $border_colour = "#008"; $background_colour = "#27F"; break;
            case "green" : $border_colour = "#080"; $background_colour = "#3F3"; break;
            case "yellow": $border_colour = "#880"; $background_colour = "#FF3"; break;
            case "red"   : $border_colour = "#800"; $background_colour = "#F33"; break;
            case "white" : $border_colour = "#888"; $background_colour = "#FFF"; break;
        }
        switch ($size) {
            case "large": $bar_max_length = 100; $bar_height = 10; $font_size = 12; $margin = "2px 5px 2px 0px"; break;
            case "small": $bar_max_length =  50; $bar_height =  5; $font_size = 10; $margin = "5px 5px 0px 0px"; break;
        }
        echo "<div style='height: {$bar_height}px; width: {$this->rating_bar_width($rating, $domain_min, $domain_max, $which, $bar_max_length)}px; border: 1px solid {$border_colour}; border-left: 0px; background: {$background_colour}; float: left; margin: {$margin};'>";
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
    public function rating_bar_width($rating, $domain_min, $domain_max, $which, $bar_max_length) {
        // Output zero if no ratings
        if (empty($this->total_reviews)) {
            return $bar_max_length;
        }
        // Find value of greateset bar to be used as a divider
        $divider = 0;
        for ($i = $domain_min; $i <= $domain_max; $i++) {
            if ($this->{$rating . "_" . $i} > $divider) {
                $divider = $this->{$rating . "_" . $i};
            }
        }
        // Normalize width in a logarithmic manner
        $multiplier = ($divider + $this->total_reviews)/(2 * $divider);
        $result = round($this->{$rating . "_" . $which} * $multiplier * $bar_max_length / $this->total_reviews);
        return 1 + $result; // Add 1 pixel in case the value is zero so user can still see the bar is there.
    }

    /* FORM VALIDATION
     * Returns an array of error strings corresponding to each
     * invalid parameter, otherwise returns false.
     */
    public static function validate_new($params) {
        $errors;
        // First name format
        if (strlen($params['first_name']) < 1) {
            $errors['first_name'] = "Please enter the professor's first name!";
            return $errors;
        } else if (strlen($params['first_name']) > 20) {
            $errors['first_name'] = "We can only accept first names up to 25 characters long.";
            return $errors;
        } else if (preg_match("/^[a-zA-Z-.\s]+$/", $params['first_name']) == false) {
            $errors['first_name'] = "No weird characters in the first name please!";
            return $errors;
        }
        // Last name format
        if (strlen($params['last_name']) < 1) {
            $errors['last_name'] = "Please enter the professor's last name!";
            return $errors;
        } else if (strlen($params['first_name']) > 20) {
            $errors['last_name'] = "We can only accept last names up to 25 characters long.";
            return $errors;
        } else if (preg_match("/^[a-zA-Z-.\s]+$/", $params['last_name']) == false) {
            $errors['last_name'] = "No weird characters in the last name please!";
            return $errors;
        }
        // Duplicate professor
        if (self::find_by_name($params['first_name'], $params['last_name'], session::school_id())) {
            $errors['id'] = "Sorry, that Professor is already in the database.";
            return $errors;
        }
        // Gender
        if (empty($params['gender'])) {
            $errors['gender'] = "Please indicate the professor's sex! (hehehe)";
            return $errors;
        }
        // Department
        if (empty($params['department'])) {
            $errors['department'] = "Please specify the professor's department.";
            return $errors;
        }
        return $errors;
    }

}

?>