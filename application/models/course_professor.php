<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course_professor_model extends StudyMonkey_Model
{
    protected $TABLE_NAME;

    protected $db_fields = array
        ( 'school_id'
        , 'course_id'
        , 'professor_id'
        , 'total_reviews'
        );

    function __construct()
    {
        parent::__construct();
        $this->TABLE_NAME = 'course_professor';
    }

    public function find_by_course_id($course_id)
    {
        $result = $this->db->query("SELECT * FROM course_professor WHERE course_id = ?", array( $course_id ) );
        return $result->result_array();
    }

    public static function find_by_professor_id($in_professor_id) {
        global $database;
        if (empty($in_professor_id)) { return false; }
        return self::find_by_sql("SELECT * FROM ". get_class() ." WHERE professor_id=". $in_professor_id);
    }

    public static function find_by_course_and_professor_id($in_course_id, $in_professor_id) {
        global $database;
        if (empty($in_course_id) || empty($in_professor_id)) { return false; }
        $result_array = self::find_by_sql("SELECT * FROM ". get_class() ." WHERE course_id =". $in_course_id ." AND professor_id=". $in_professor_id ." LIMIT 1");
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public static function count_by_course_id($in_course_id) {
        global $database;
        if (empty($in_course_id)) { return false; }
        $result_set = $database->query("SELECT count(*) FROM ". get_class() ." WHERE course_id=". $in_course_id);
        $row = $database->fetch_array($result_set);
        return array_shift($row);
    }

    public static function count_by_professor_id($in_professor_id) {
        global $database;
        if (empty($in_professor_id)) { return false; }
        $result_set = $database->query("SELECT count(*) FROM ". get_class() ." WHERE professor_id=". $in_professor_id);
        $row = $database->fetch_array($result_set);
        return array_shift($row);
    }

    public function course_professor($course = NULL, $professor = NULL) {
        if ($course) {
            $this->course_id = $course->id;
            $this->school_id = $course->school_id;
        }
        if ($professor) {
            $this->professor_id = $professor->id;
        }
    }

    /* FORM VALIDATION
     * Returns any errors, NULL if no error.
     */
    public function validate_new() {
        $result_array = self::find_by_sql("SELECT * FROM ". get_class() ." WHERE course_id=". $this->course_id ." AND professor_id=". $this->professor_id);
        return empty($result_array) ? NULL : "That professor has already been added to this course, silly!";
    }

}

?>