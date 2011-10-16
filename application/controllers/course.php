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
        $total_search_results = 0;
        $page = 1;
        $total_courses = $this->course->count_all_by_school_id( $school['id'] );
        if( ! empty( $search_query ) OR $total_courses <= ITEMS_PER_PAGE )
        {
            // Pagination
            $page = $this->input->post('page');
            if( empty($page) )
                $page = 1;

            // Exact match
            $exact_match = $this->course->find_by_course_code( str_replace(" ", "", $search_query), $school['id'] );
            if( ! empty( $exact_match ) )
            {
                if( $this->input->is_ajax_request() )
                {
                    $notification = Notification::redirect( string2uri( $exact_match['course_code'] ) );
                    echo $notification->to_AJAX();
                    return;
                }
                else
                {
                    header( "location: /" . string2uri( $school['full_name'] . "/courses/" . string2uri( $exact_match['course_code'] ) ) );
                }
            }

            // Search
            if( $total_courses > ITEMS_PER_PAGE )
            {
                $total_search_results = $this->course->search_count( $search_query, $school['id'] );
                $query_result = $this->course->search( $search_query, ITEMS_PER_PAGE, $page, $school['id'] );
            }

            // Just show all courses if we have 10 or less in total
            else
            {
                $total_search_results = $total_courses;
                $query_result = $this->course->find_all_by_school_id( $school['id'] );
            }
            
            // Build up parameters
            $search_result_params = array();
            $search_result_params['total_courses'] = $total_courses;
            $search_result_params['previous_query'] = $search_query;
            $search_result_params['page'] = $page;
            $search_result_params['courses'] = $query_result;
            $search_result_params['total_search_results'] = $total_search_results;
            $search_result_params['school'] = $school;
            $search_result_params['exact_match'] = $exact_match;
            $search_result = $this->load->view('course/course_search_result', $search_result_params, TRUE);

            // Ajax
            if( $this->input->is_ajax_request() )
            {
                echo $search_result;
                return;
            }
        }

        // 3 Popular courses
        $popular_courses = $this->course->find_most_popular( $school['id'], 3 );

        // 3 Top Rated
        $top_rated_courses = $this->course->find_top_rated( $school['id'], 3 );

        // Custom Parameters
        $this->view_params['total_courses'] = $total_courses;
        $this->view_params['previous_query'] = $search_query;
        $this->view_params['page'] = $page;
        $this->view_params['school'] = $school;
        $this->view_params['search_result'] = $search_result;
        $this->view_params['total_search_results'] = $total_search_results;
        $this->view_params['popular_courses'] = $popular_courses;
        $this->view_params['top_rated_courses'] = $top_rated_courses;

        // Layout Parameters
        $this->view_params['notification'] = empty($notification)? NULL : $notification;
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
        $school = $this->school->find_by_uri_segment( $school_segment );
        if ( empty( $school ) )
            show_404($this->uri->uri_string());

        // Course
        $this->load->model('course');
        $course = $this->course->find_by_course_code( $course_segment, $school['id'] );
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

        // Review professors
        $review_professors = array();
        if ( ! empty( $reviews ) )
        {
            foreach($reviews as $review)
            {
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
        $this->view_params['review_professors'] = $review_professors;

        // Layout Parameters
        $this->view_params['page_tab'] = "Courses";
        $this->view_params['page_title'] = $course['course_code'];
        $this->view_params['page_subtitle'] = $course['course_title'];
        $this->view_params['page_subtitle2'] = $school['full_name'];
        $this->view_params['page_content'] = $this->load->view('course/course_view', $this->view_params, TRUE);
		$this->load->view('_layout_main', $this->view_params);
    }

    function create( $school_segment )
    {
        // School
        $this->load->model('school');
        $school = $this->school->find_by_uri_segment($school_segment);
        if( empty( $school ) )
        {
            // Invalid school
            $notification = Notification::error( "Something went wrong." );

            if( $this->input->is_ajax_request() )
            {
                echo $notification->to_AJAX();
            }
            else
            {
                $this->session->set_flashdata( array( 'notification' => $notification ) );
                header( "location: /" );
            }
            return;
        }

        // Get form fields
        $course_code = $this->input->post('course_code');
        $course_title = $this->input->post('course_title');

        // Validate the new course
        $this->load->model('course');
        $response = $this->course->validate_new( $course_code, $course_title, $school['id'] );
        if( $response->is_success() )
        {
            // Validate Captcha
            $captcha = $this->input->post('captcha');
            if( $captcha != $this->session->userdata('captcha_answer') )
            {
                $response = Notification::error('You got the math question wrong.');
            }

            // Save new course, passed all tests
            else
            {
                $new_course = array();
                $new_course['course_code'] = $course_code;
                $new_course['course_title'] = $course_title;
                $new_course['school_id'] = $school['id'];
                $this->course->save( $new_course );

                // Set a success flash message
                $this->session->set_flashdata( array( 'notification' => Notification::success("You've added {$new_course['course_code']} to {$school['full_name']}!") ) );

                // Redirect the user
                if( $this->input->is_ajax_request() )
                {
                    // Return the professor ID for ratings lightbox
                    if( $this->input->post('return') == "id" )
                    {
                        $this->session->set_flashdata( array( 'notification' => NULL ) );   // Inset the flash message since it will be generated client side
                        $redirect = Notification::redirect( $new_course['id'] );
                        echo $redirect->to_AJAX();
                    }
                    else
                    {
                        $redirect = Notification::redirect( string2uri( $new_course['course_code'] ) );
                        echo $redirect->to_AJAX();
                    }
                }
                else
                {
                    header( "location: /" . string2uri( $school['full_name'] . "/courses/" . string2uri( $new_course['course_code'] ) ) );
                }
                return;
            }
        }

        // Failure, notify user
        if( $this->input->is_ajax_request() )
        {
            echo $response->to_AJAX();
        }
        else
        {
            $this->session->set_flashdata( array( 'notification' => $response ) );
            header( "location: /" . string2uri( $school['full_name'] . "/courses" ) );
        }
        return;
    }

    function autocomplete( $school_segment )
    {
        // School
        $this->load->model('school');
        $school = $this->school->find_by_uri_segment( $school_segment );
        if ( empty( $school ) )
            return;

        // Post Parameters
        $query = $this->input->post("q");
        $limit = $this->input->post("limit");
        if( empty( $query) || ! is_numeric( $limit ) )
            return;

        // Course search
        $this->load->model('course');
        $items = $this->course->search( $query, $limit, 1, $school['id'] );

        // Output results
        header('Content-Type: text/html; charset=ISO-8859-15'); // <--- Needed to prevent accents from turning into question marks in the autocomplete
        foreach($items as $item)
            echo $item['course_code'] . ": " . $item['course_title'] . "|" . $item['id'] . "\n";
        return;
    }

}

/* End of file courses.php */
/* Location: ./application/controllers/courses.php */