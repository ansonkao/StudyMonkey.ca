<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course_model extends StudyMonkey_Model
{
    protected $TABLE_NAME;

    protected $db_fields = array
        ( 'course_code'
        , 'course_title'
        , 'school_id'
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

    function count_all_by_school_id( $school_id )
    {
        $result = $this->db->query("SELECT COUNT(*) FROM course WHERE school_id = ? LIMIT 1", array( $school_id ) );
        return array_shift( $result->row_array() );
    }

    function find_all_by_school_id( $school_id )
    {
        $result = $this->db->query("SELECT * FROM course WHERE school_id = ?", array( $school_id ) );
        return $result->result_array();
    }

    function find_by_course_code( $code, $school_id )
    {
        $result = $this->db->query("SELECT * FROM course WHERE course_code = ? AND school_id = ? LIMIT 1", array( $code, $school_id ) );
        return $result->row_array();
    }

    function find_by_professor_id( $professor_id )
    {
        $result = $this->db->query(
            "SELECT * FROM course c
             INNER JOIN course_professor cp
             WHERE c.id = cp.course_id
                AND cp.professor_id = ?
             ORDER BY c.course_code ASC"
            , array( $professor_id )
            );
        return $result->result_array();
    }

    function find_most_popular( $school_id = NULL, $limit = 5 )
    {
        $sql = "SELECT * FROM course ";
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
        $sql = "SELECT * FROM course ";
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
        $sql = "SELECT * FROM course WHERE ";

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
            $sql .= " AND (course_code LIKE ? OR course_title LIKE ?)";
            $params[] = "%{$i_search_term}%";
            $params[] = "%{$i_search_term}%";
        }

        // Order
        $sql .= " ORDER BY course_code LIKE ? DESC, course_code ASC";
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
        $sql = "SELECT COUNT(*) FROM course WHERE ";

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
            $sql .= " AND (course_code LIKE ? OR course_title LIKE ?)";
            $params[] = "%{$i_search_term}%";
            $params[] = "%{$i_search_term}%";
        }

        // Run Query
        $result = $this->db->query($sql, $params );
        return array_shift( $result->row_array() );
    }

    function update_totals( $this_course )
    {
        $this->load->model('course_professor_review');
        $reviews = $this->course_professor_review->find_by_course_id( $this_course['id'] );

        $this_course['total_reviews'] = 0;
        $this_course['overall_rating'] = 0;
        $this_course['easiness_rating'] = 0;
        $this_course['workload_rating'] = 0;
        $this_course['interest_rating'] = 0;
        $this_course['overall_recommendation_1'] = 0;
        $this_course['overall_recommendation_0'] = 0;
        $this_course['textbook_rating_4'] = 0;
        $this_course['textbook_rating_3'] = 0;
        $this_course['textbook_rating_2'] = 0;
        $this_course['textbook_rating_1'] = 0;
        $this_course['textbook_rating_0'] = 0;
        $this_course['attendance_rating_4'] = 0;
        $this_course['attendance_rating_3'] = 0;
        $this_course['attendance_rating_2'] = 0;
        $this_course['attendance_rating_1'] = 0;

        foreach( $reviews as $review )
        {
            $this_course['total_reviews'] += 1;
            switch( $review['overall_recommendation'] )
            {
                case 1: $this_course['overall_recommendation_1'] += 1; break;
                case 0: $this_course['overall_recommendation_0'] += 1; break;
            }
            switch( $review['textbook_rating'] )
            {
                case 4: $this_course['textbook_rating_4'] += 1; break;
                case 3: $this_course['textbook_rating_3'] += 1; break;
                case 2: $this_course['textbook_rating_2'] += 1; break;
                case 1: $this_course['textbook_rating_1'] += 1; break;
                case 0: $this_course['textbook_rating_0'] += 1; break;
            }
            switch( $review['attendance_rating'] )
            {
                case 4: $this_course['attendance_rating_4'] += 1; break;
                case 3: $this_course['attendance_rating_3'] += 1; break;
                case 2: $this_course['attendance_rating_2'] += 1; break;
                case 1: $this_course['attendance_rating_1'] += 1; break;
            }
            $this_course['easiness_rating'] += $review['easiness_rating'];
            $this_course['workload_rating'] += $review['workload_rating'];
            $this_course['interest_rating'] += $review['interest_rating'];
            // OVERALL RATING
            $this_course['overall_rating'] += 3 * $review['easiness_rating'];
            $this_course['overall_rating'] += 3 * (6 - $review['workload_rating']); // Flip workload because it is a negative quality
            $this_course['overall_rating'] += 3 * $review['interest_rating'];
            $this_course['overall_rating'] += $review['knowledge_rating'];
            $this_course['overall_rating'] += $review['helpful_rating'];
            $this_course['overall_rating'] += $review['awesome_rating'];
        }
        $this_course['overall_rating']  /= (float)$this_course['total_reviews'];
        $this_course['overall_rating']  /= (float)12.0;
        $this_course['easiness_rating'] /= (float)$this_course['total_reviews'];
        $this_course['workload_rating'] /= (float)$this_course['total_reviews'];
        $this_course['interest_rating'] /= (float)$this_course['total_reviews'];

        log_message("ERROR", print_r( $this_course, true ));

        $this->save( $this_course );
    }

    public function validate_new( &$course_code, &$course_title, $school_id )
    {
        $course_code = strtoupper( str_replace(" ", "", $course_code) );
        $course_title = trim( $course_title );

        // Empty fields
        if( empty( $course_code ) OR empty( $course_title ) )
        {
            return Notification::error( "You have empty fields." );
        }

        // Course code max length
        if( strlen( $course_code ) > 15 )
        {
            return Notification::error( "The course code cannot be more than 15 characters long." );
        }

        // Course code alphanumeric
        if( ctype_alnum( $course_code ) == false )
        {
            return Notification::error( "The course code can only contain letters or numbers." );
        }

        // Check for existing course
        $exact_match = $this->find_by_course_code( $course_code, $school_id );
        if( ! empty( $exact_match ) )
        {
            return Notification::error( "That course code already exists." );
        }

        // Success!
        return Notification::success();
    }
}

/* End of file courses.php */
/* Location: ./application/models/courses.php */