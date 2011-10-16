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

    function count_all_by_school_id( $school_id )
    {
        $result = $this->db->query("SELECT COUNT(*) FROM professor WHERE school_id = ? LIMIT 1", array( $school_id ) );
        return array_shift( $result->row_array() );
    }

    function find_all_by_school_id( $school_id )
    {
        $result = $this->db->query("SELECT * FROM professor WHERE school_id = ?", array( $school_id ) );
        return $result->result_array();
    }

    function find_by_uri_segment( $professor_segment, $school_id )
    {
        // Identify first and last name
        $parts = explode( "_", $professor_segment );
        if( count( $parts ) != 2 )
        {
            return false;
        }
        $first_name = uri2string( $parts[0] );
        $last_name  = uri2string( $parts[1] );

        // Run the query
        return $this->find_by_name( $first_name, $last_name, $school_id );
    }

    function find_by_name( $first_name, $last_name, $school_id )
    {
        $result = $this->db->query("SELECT * FROM professor WHERE first_name LIKE ? AND last_name LIKE ? AND school_id = ? LIMIT 1", array( $first_name, $last_name, $school_id ) );
        return $result->row_array();
    }


    function find_by_course_id( $course_id )
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

    function find_most_popular( $school_id = NULL, $limit = 5 )
    {
        $sql = "SELECT * FROM professor ";
        $params = array();

        if( $school_id )
        {
            $sql .= "WHERE school_id = ? ";
            $params[] = $school_id;
        }
        $sql .= "ORDER BY total_reviews DESC LIMIT ?";
        $params[] = $limit;

        $result = $this->db->query( $sql, $params );
        return $result->result_array();
    }

    function find_top_rated( $school_id = NULL, $limit = 5 )
    {
        $sql = "SELECT * FROM professor ";
        $params = array();

        if( $school_id )
        {
            $sql .= "WHERE school_id = ? ";
            $params[] = $school_id;
        }
        $sql .= "ORDER BY (overall_rating * total_reviews + 17) / (total_reviews + 5) DESC LIMIT ?";
        $params[] = $limit;

        $result = $this->db->query( $sql, $params );
        return $result->result_array();
    }

    function search( $search_term, $limit = ITEMS_PER_PAGE, $page = 1, $school_id = NULL )
    {
        // Validate
        if ( ! is_numeric($limit) )
            return false;
        if ( ! is_numeric($page) )
            $page = 1;

        // Begin query
        $sql = "SELECT * FROM professor WHERE ";

        // Filter by school
        if ( is_numeric( $school_id ) )
        {
            $sql .= "school_id = ? ";
            $params = array( $school_id );
        }
        else
            $sql .= "1 ";   // Dummy to sandwich the extra AND clause in next part

        // Search terms
        foreach(explode(" ", $search_term) as $i_search_term) {
            $sql .= " AND (first_name LIKE ? OR last_name LIKE ?)";
            $params[] = "%{$i_search_term}%";
            $params[] = "%{$i_search_term}%";
        }

        // Order
        $sql .= " ORDER BY last_name LIKE ? DESC";
        $params[] = $search_term . "%";

        // Offset / Limit
        $sql .= " LIMIT ?, ?";
        $params[] = intval( $limit * ( $page - 1 ) );
        $params[] = intval( $limit );

        // Run Query
        $result = $this->db->query($sql, $params );
        return $result->result_array();
    }

    function search_count( $search_term, $school_id = NULL )
    {
        // Begin query
        $sql = "SELECT COUNT(*) FROM professor WHERE ";

        // Filter by school
        if ( is_numeric( $school_id ) )
        {
            $sql .= "school_id = ? ";
            $params = array( $school_id );
        }
        else
            $sql .= "1 ";   // Dummy to sandwich the extra AND clause in next part

        // Search terms
        foreach(explode(" ", $search_term) as $i_search_term) {
            $sql .= " AND (first_name LIKE ? OR last_name LIKE ?)";
            $params[] = "%{$i_search_term}%";
            $params[] = "%{$i_search_term}%";
        }

        // Run Query
        $result = $this->db->query($sql, $params );
        return array_shift( $result->row_array() );
    }

    function update_totals( $this_professor )
    {
        $this->load->model('course_professor_review');
        $reviews = $this->course_professor_review->find_by_professor_id( $this_professor['id'] );

        $this_professor['total_reviews'] = 0;
        $this_professor['overall_rating'] = 0;
        $this_professor['knowledge_rating'] = 0;
        $this_professor['helpful_rating'] = 0;
        $this_professor['awesome_rating'] = 0;
        $this_professor['overall_recommendation_1'] = 0;
        $this_professor['overall_recommendation_0'] = 0;
        $this_professor['attendance_rating_4'] = 0;
        $this_professor['attendance_rating_3'] = 0;
        $this_professor['attendance_rating_2'] = 0;
        $this_professor['attendance_rating_1'] = 0;
        foreach( $reviews as $review )
        {
            $this_professor['total_reviews'] += 1;
            switch( $review['overall_recommendation'] )
            {
                case 1: $this_professor['overall_recommendation_1'] += 1; break;
                case 0: $this_professor['overall_recommendation_0'] += 1; break;
            }
            switch ($review['attendance_rating'] )
            {
                case 4: $this_professor['attendance_rating_4'] += 1; break;
                case 3: $this_professor['attendance_rating_3'] += 1; break;
                case 2: $this_professor['attendance_rating_2'] += 1; break;
                case 1: $this_professor['attendance_rating_1'] += 1; break;
            }
            $this_professor['knowledge_rating'] += $review['knowledge_rating'];
            $this_professor['helpful_rating']   += $review['helpful_rating'];
            $this_professor['awesome_rating']   += $review['awesome_rating'];
            // OVERALL RATING
            $this_professor['overall_rating'] += $review['easiness_rating'];
            $this_professor['overall_rating'] += (6 - $review['workload_rating']); // Flip workload because it is a negative quality
            $this_professor['overall_rating'] += $review['interest_rating'];
            $this_professor['overall_rating'] += 3 * $review['knowledge_rating'];
            $this_professor['overall_rating'] += 3 * $review['helpful_rating'];
            $this_professor['overall_rating'] += 3 * $review['awesome_rating'];
        }
        $this_professor['overall_rating']   /= (float)$this_professor['total_reviews'];
        $this_professor['overall_rating']   /= (float)12.0;
        $this_professor['knowledge_rating'] /= (float)$this_professor['total_reviews'];
        $this_professor['helpful_rating']   /= (float)$this_professor['total_reviews'];
        $this_professor['awesome_rating']   /= (float)$this_professor['total_reviews'];

        $this->save( $this_professor );
    }

    /* FORM VALIDATION
     * Returns an array of error strings corresponding to each
     * invalid parameter, otherwise returns false.
     */
    function validate_new( &$first_name, &$last_name, &$department, $gender, $school_id )
    {
        $first_name = trim( $first_name );
        $last_name  = trim( $last_name );
        $department = trim( $department );

        // Empty fields
        if( empty( $first_name ) OR empty( $last_name ) OR empty( $department ) )
        {
            return Notification::error( "You have empty fields." );
        }

        // First name length
        if( strlen( $first_name ) > 25 )
        {
            return Notification::error( "The professor's first name cannot be more than 25 characters long." );
        }
        // Last name length
        if( strlen( $last_name ) > 25 )
        {
            return Notification::error( "The professor's last name cannot be more than 25 characters long." );
        }
        // Department length
        if( strlen( $department ) > 50 )
        {
            return Notification::error( "The professor's department cannot be more than 50 characters long." );
        }

        // Gender
        if( !in_array($gender, array( "M", "F" ) ) )
        {
            return Notification::error( "Please indicate the professor's sex! (hehehe)" );
        }

        // Check for existing professor
        $exact_match = $this->professor->find_by_name( $first_name, $last_name, $school_id );
        if( ! empty( $exact_match ) )
        {
            return Notification::error( "That professor already exists." );
        }

        return Notification::success();
        // Gender
        if (empty($params['gender'])) {
            $errors['gender'] = "Please indicate the professor's sex! (hehehe)";
            return $errors;
        }
    }

}

/* End of file professor.php */
/* Location: ./application/models/professor.php */