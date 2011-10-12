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

    function find_by_course_and_professor_id( $course_id, $professor_id )
    {
        if( empty( $course_id ) OR empty( $professor_id ) )
            return false;

        $result = $this->db->query( "SELECT * FROM course_professor WHERE course_id = ? AND professor_id = ? LIMIT 1", array( $course_id, $professor_id ) );
        return $result->row_array();
    }

}

?>