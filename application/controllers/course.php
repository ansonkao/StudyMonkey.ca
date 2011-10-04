<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course extends CI_Controller
{
	public function index()
	{
        $search_result = NULL;

        // Custom Parameters
        $this->view_params['search_result'] = $search_result;

        // Layout Parameters
        $this->view_params['page_tab'] = "Courses";
        $this->view_params['page_title'] = "Course Search";
        $this->view_params['page_subtitle'] = NULL;
        $this->view_params['page_subtitle2'] = NULL;
        $this->view_params['page_content'] = $this->load->view('course/course_search', $this->view_params, TRUE);
		$this->load->view('_layout_main', $this->view_params);
	}

    public function search( $school_segment )
	{
        // School
        $this->load->model('school');
        $school = $this->school->find_by_uri_segment($school_segment);
        if( empty( $school ) )
            show_404($this->uri->uri_string());

        // Process search query
        $this->load->model('course');
        $search_query = $this->input->post('search');
        $search_result = NULL;
        if( ! empty( $search_query ) )
        {
            $query_result = $this->course->search( $search_query, 10, $school['id'] );

            $search_result_params = array();
            $search_result_params['courses'] = $query_result;
            $search_result_params['school'] = $school;
            $search_result_params['query'] = $search_query;
            $search_result = $this->load->view('course/course_search_result', $search_result_params, TRUE);

            if( $this->input->is_ajax_request() )
            {
                echo $search_result;
                return;
            }
        }

        // 5 Popular courses
        $popular_courses = $this->course->find_most_popular( $school['id'], 5 );

        // Custom Parameters
        $this->view_params['school'] = $school;
        $this->view_params['search_result'] = $search_result;
        $this->view_params['popular_courses'] = $popular_courses;

        // Layout Parameters
        $this->view_params['page_tab'] = "Courses";
        $this->view_params['page_title'] = "Course Search";
        $this->view_params['page_subtitle'] = $school['full_name'];
        $this->view_params['page_subtitle2'] = NULL;
        $this->view_params['page_content'] = $this->load->view('course/course_search', $this->view_params, TRUE);
		$this->load->view('_layout_main', $this->view_params);
	}

    public function view( $school_segment, $course_segment, $page_segment = 1 )
    {
        // School
        $this->load->model('school');
        $school = $this->school->find_by_uri_segment($school_segment);
        if ( empty( $school ) )
            show_404($this->uri->uri_string());

        // Course
        $this->load->model('course');
        $course = $this->course->find_by_course_code($course_segment);
        if ( empty( $course ) )
            show_404($this->uri->uri_string());

        // Professors
        $this->load->model('professor');
        $professors = $this->professor->find_by_course_id( $course['id'] );

        // Reviews
        $this->load->model('course_professor_review');
        $total_reviews = $this->course_professor_review->count_by_course_id( $course['id'] );
        $reviews = $this->course_professor_review->paginate_by_course_id( $course['id'], $page_segment, 5 );
        if ( $total_reviews > 0 AND empty( $reviews ) )
            show_404($this->uri->uri_string());

        // Review authors and professors
        $this->load->model('user');
        $review_authors = array();
        $review_professors = array();
        if ( ! empty( $reviews ) )
        {
            foreach($reviews as $review)
            {
                $review_authors[$review['id']] = $this->user->find_by_id($review['user_id']);
                $review_professors[$review['id']] = $this->professor->find_by_id($review['professor_id']);
            }
        }

        // Pagination
        $pagination_params = array();
        $pagination_params['total_rows'] = $total_reviews;
        $pagination_params['current_page'] = $page_segment;
        $pagination_params['parent_uri'] = "/{$school_segment}/courses/{$course_segment}";
        $this->load->library( 'pagination', $pagination_params );
                
        // Custom Parameters
        $this->view_params['school'] = $school;
        $this->view_params['course'] = $course;
        $this->view_params['professors'] = $professors;
        $this->view_params['total_reviews'] = $total_reviews;
        $this->view_params['reviews'] = $reviews;
        $this->view_params['review_authors'] = $review_authors;
        $this->view_params['review_professors'] = $review_professors;

        // Layout Parameters
        $this->view_params['page_tab'] = "Courses";
        $this->view_params['page_title'] = $course['course_code'];
        $this->view_params['page_subtitle'] = $course['course_title'];
        $this->view_params['page_subtitle2'] = $school['full_name'];
        $this->view_params['page_content'] = $this->load->view('course/course_view', $this->view_params, TRUE);
		$this->load->view('_layout_main', $this->view_params);
    }
}

/* End of file courses.php */
/* Location: ./application/controllers/courses.php */