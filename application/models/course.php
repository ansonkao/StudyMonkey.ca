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

    public function search( $search_term, $limit = ITEMS_PER_PAGE, $page = 1, $school_id = NULL )
    {
        // Validate
        if ( ! is_numeric($limit) )
            return false;
        if ( ! is_numeric($page) )
            $page = 1;

        // Begin query
        $sql = "SELECT DISTINCT * FROM course WHERE ";

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
        $params[] = $limit * ( $page - 1 );
        $params[] = $limit;

        // Run Query
        $result = $this->db->query($sql, $params );
        return $result->result_array();
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