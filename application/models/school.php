<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class School extends StudyMonkey_Model
{

    protected $TABLE_NAME;

    protected $db_fields = array
        ( 'full_name'
        , 'province'
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

    public function search( $search_term, $limit = 10 )
    {
        // Validate limit
        if ( ! is_numeric($limit) )
            return false;

        // Begin query
        $sql = "SELECT DISTINCT * FROM school WHERE ";
        $sql .= "1 ";   // Dummy to sandwich the extra AND clause in next part

        // Search terms
        foreach(explode(" ", $search_term) as $i_search_term) {
            $sql .= " AND full_name LIKE ?";
            $params[] = "%{$i_search_term}%";
        }

        // Order and Limit
        $sql .= " ORDER BY full_name LIKE ?";
        $params[] = $search_term . "%";
        $sql .= " DESC LIMIT ?";
        $params[] = $limit;

        // Run Query
        $result = $this->db->query($sql, $params );
        return $result->result_array();
    }

}
