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

    public static function count_by_course_professor_id($in_course_professor_id) {
        global $database;
        if (empty($in_course_professor_id)) { return false; }
        return self::count_all("course_professor_id={$in_course_professor_id}");
    }
    public static function count_by_user_id($user_id) {
        if (empty($user_id)) { return false; }
        return self::count_all("user_id={$user_id}");
    }

    public function find_by_course_id($course_id) {
        $result = $this->db->query("SELECT * FROM course_professor_review WHERE course_id = ?", array( $course_id ) );
        return $result->result_array();
    }

    public static function find_by_professor_id($in_professor_id) {
        global $database;
        if (empty($in_professor_id)) { return false; }
        return self::find_by_sql("SELECT * FROM ". get_class() ." WHERE professor_id=". $in_professor_id);
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

    /* Distinguishes if the review is submitted by a course or professor page
     * and identifies the corresponding records and saves them in this object.
     * Returns any errors, or false if no errors.
     */
    public function process_new($params) {
        $new_course_professor = new course_professor();
        switch ($params['course_or_professor_page']) {
            case 'course':
                $new_course_professor->course_id = $params['course_or_professor_page_id'];
                $new_course_professor->professor_id = $params['course_professor_id'];
                break;
            case 'professor':
                $new_course_professor->course_id = $params['course_professor_id'];
                $new_course_professor->professor_id = $params['course_or_professor_page_id'];
                break;
        }
        $found_course_professor = course_professor::find_by_course_and_professor_id($new_course_professor->course_id, $new_course_professor->professor_id);

        // Found existing record for this course/professor combo
        if ($found_course_professor) {
            // Update this rating with the course/professor values
            $this->course_id = $found_course_professor->course_id;
            $this->professor_id = $found_course_professor->professor_id;
            $this->course_professor_id = $found_course_professor->id;

            // Make sure the user hasn't rated this course/professor before
            if (session::user()->privilege == "user") { // Admin can rate courses as much as they'd like
                $already_reviewed = course_professor_review::count_all("user_id = ".session::user_id()." AND course_professor_id = {$this->course_professor_id}");
                if ($already_reviewed) {
                    return "You've already rated this course/professor!";
                }
            }
            return false;

        // None found, check if course exists and professor exists > create
        } else {
            $error = $new_course_professor->validate_new();
            if ($error) {
                return $error;
            }

            $new_course_professor->school_id = session::school_id();
            if ($new_course_professor->save() == false) {
                // Can't save
                return "ERROR: Unable to create new course/professor relationship.";
            }

            $this->course_id = $new_course_professor->course_id;
            $this->professor_id = $new_course_professor->professor_id;
            $this->course_professor_id = $new_course_professor->id;
            // Success, no error!
            return false;
        }
    }

}

?>