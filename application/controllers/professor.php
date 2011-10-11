<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Professor extends CI_Controller
{
	public function index()
	{
        $search_result = NULL;

        // Custom Parameters
        $this->view_params['search_result'] = $search_result;

        // Layout Parameters
        $this->view_params['page_tab'] = "Professors";
        $this->view_params['page_title'] = "Professor Search";
        $this->view_params['page_subtitle'] = NULL;
        $this->view_params['page_subtitle2'] = NULL;
        $this->view_params['page_content'] = $this->load->view('professor/professor_search', $this->view_params, TRUE);
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
        $this->load->model('professor');
        $search_query = $this->input->post('search');
        $search_result = NULL;
        $page = 1;
        if( ! empty( $search_query ) )
        {
            // Pagination
            $page = $this->input->post('page');
            if( empty($page) )
                $page = 1;

            // Search
            $query_result = $this->professor->search( $search_query, ITEMS_PER_PAGE, $page, $school['id'] );
            
            // Build up parameters
            $search_result_params = array();
            $search_result_params['previous_query'] = $search_query;
            $search_result_params['page'] = $page;
            $search_result_params['professors'] = $query_result;
            $search_result_params['school'] = $school;
            $search_result = $this->load->view('professor/professor_search_result', $search_result_params, TRUE);

            // Ajax
            if( $this->input->is_ajax_request() )
            {
                echo $search_result;
                return;
            }
        }

        // Popular professors
        $popular_professors = $this->professor->find_most_popular( $school['id'], 3 );

        // Top-rated professors
        $top_rated_professors = $this->professor->find_top_rated( $school['id'], 3 );

        // Custom Parameters
        $this->view_params['previous_query'] = $search_query;
        $this->view_params['page'] = $page;
        $this->view_params['school'] = $school;
        $this->view_params['search_result'] = $search_result;
        $this->view_params['popular_professors'] = $popular_professors;
        $this->view_params['top_rated_professors'] = $top_rated_professors;

        // Layout Parameters
        $this->view_params['notification'] = empty($notification)? NULL : $notification;
        $this->view_params['page_tab'] = "Professors";
        $this->view_params['page_title'] = "Professor Search";
        $this->view_params['page_subtitle'] = $school['full_name'];
        $this->view_params['page_subtitle2'] = NULL;
        $this->view_params['page_content'] = $this->load->view('professor/professor_search', $this->view_params, TRUE);
		$this->load->view('_layout_main', $this->view_params);
	}

    public function view( $school_segment, $professor_segment, $page_segment = 1 )
    {
        // School
        $this->load->model('school');
        $school = $this->school->find_by_uri_segment( $school_segment );
        if ( empty( $school ) )
            show_404($this->uri->uri_string());

        // Professor
        $this->load->model('professor');
        $professor = $this->professor->find_by_uri_segment( $professor_segment, $school['id'] );
        if ( empty( $professor ) )
            show_404($this->uri->uri_string());

        // Courses
        $this->load->model('course');
        $courses = $this->course->find_by_professor_id( $professor['id'] );

        // Reviews
        $this->load->model('course_professor_review');
        $total_reviews = $this->course_professor_review->count_by_professor_id( $professor['id'] );
        $reviews = $this->course_professor_review->paginate_by_professor_id( $professor['id'], $page_segment, 5 );
        if ( $total_reviews > 0 AND empty( $reviews ) )
            show_404($this->uri->uri_string());

        // Review authors and courses
        $this->load->model('user');
        $review_authors = array();
        $review_courses = array();
        if ( ! empty( $reviews ) )
        {
            foreach($reviews as $review)
            {
                $review_authors[$review['id']] = $this->user->find_by_id($review['user_id']);
                $review_courses[$review['id']] = $this->course->find_by_id($review['course_id']);
            }
        }

        // Pagination
        $pagination_params = array();
        $pagination_params['total_rows'] = $total_reviews;
        $pagination_params['current_page'] = $page_segment;
        $pagination_params['parent_uri'] = "/{$school_segment}/professors/{$professor_segment}";
        $this->load->library( 'pagination', $pagination_params );
                
        // Custom Parameters
        $this->view_params['school'] = $school;
        $this->view_params['professor'] = $professor;
        $this->view_params['courses'] = $courses;
        $this->view_params['total_reviews'] = $total_reviews;
        $this->view_params['reviews'] = $reviews;
        $this->view_params['review_authors'] = $review_authors;
        $this->view_params['review_courses'] = $review_courses;

        // Layout Parameters
        $this->view_params['page_tab'] = "Professors";
        $this->view_params['page_title'] = "{$professor['last_name']}, {$professor['first_name']}";
        $this->view_params['page_subtitle'] = "Department of {$professor['department']}";
        $this->view_params['page_subtitle2'] = $school['full_name'];
        $this->view_params['page_content'] = $this->load->view('professor/professor_view', $this->view_params, TRUE);
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
        $first_name = $this->input->post('first_name');
        $last_name  = $this->input->post('last_name');
        $department = $this->input->post('department');
        $gender     = $this->input->post('gender');

        // Validate the new professor
        $this->load->model('professor');
        $response = $this->professor->validate_new( $first_name, $last_name, $department, $gender, $school['id'] );
        if( $response->is_success() )
        {
            // Validate Captcha
            $captcha = $this->input->post('captcha');
            if( $captcha != $this->session->userdata('captcha_answer') )
            {
                $response = Notification::error('You got the math question wrong.');
            }

            // Save new professor, passed all tests
            else
            {
                $new_professor = array();
                $new_professor['first_name'] = $first_name;
                $new_professor['last_name']  = $last_name;
                $new_professor['department'] = $department;
                $new_professor['gender']     = $gender;
                $new_professor['school_id']  = $school['id'];
                $this->professor->save( $new_professor );

                // Set a success flash message
                $this->session->set_flashdata( array( 'notification' => Notification::success("You've added {$new_professor['first_name']} {$new_professor['last_name']} to {$school['full_name']}!") ) );

                // Redirect the user
                if( $this->input->is_ajax_request() )
                {
                    // Return the professor ID for ratings lightbox
                    if( $this->input->post('return') == "id" )
                    {
                        $this->session->set_flashdata( array( 'notification' => NULL ) );   // Inset the flash message since it will be generated client side
                        $redirect = Notification::redirect( $new_professor['id'] );
                        echo $redirect->to_AJAX ();
                    }
                    else
                    {
                        $redirect = Notification::redirect( string2uri( $new_professor['first_name'] ) . "_" . string2uri( $new_professor['last_name'] ) );
                        echo $redirect->to_AJAX ();
                    }
                }
                else
                {
                    header( "location: /" . string2uri( $school['full_name'] . "/professors/" . string2uri( $new_professor['first_name'] ) . "_" . string2uri( $new_professor['last_name'] ) ) );
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
            header( "location: /" . string2uri( $school['full_name'] . "/professors" ) );
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

        // Professor search
        $this->load->model('professor');
        $items = $this->professor->search( $query, $limit, 1, $school['id'] );

        // Output results
        header('Content-Type: text/html; charset=ISO-8859-15'); // <--- Needed to prevent accents from turning into question marks in the autocomplete
        foreach($items as $item)
            echo $item['last_name'] . ", " . $item['first_name'] . "|" . $item['id'] . "\n";
        return;
    }

}

/* End of file professor.php */
/* Location: ./application/controllers/professor.php */