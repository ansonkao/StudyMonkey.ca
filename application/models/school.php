<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class School extends StudyMonkey_Model
{

    protected $TABLE_NAME;

    protected $db_fields = array
        ( 'full_name'
        , 'domain_name'
        , 'domain_suffix'
        , 'description'
        , 'total_courses'
        , 'total_professors'
        , 'total_users'
        , 'total_notes'
        , 'total_course_professor_reviews'
        );

    function __construct()
    {
        parent::__construct();
        $this->TABLE_NAME = 'school';
    }

    function find_by_uri_segment( $uri_segment )
    {
        $school_name = uri2string( $uri_segment );
        $sql = 'SELECT * FROM `school` WHERE full_name LIKE ?';
        $result = $this->db->query( $sql, array( $school_name ) );
        return $result->row_array();
    }

}
