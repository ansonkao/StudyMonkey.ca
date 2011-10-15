<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info extends CI_Controller {

	public function index()
	{
        // TODO: Log searches

        // Process search query
        $this->load->model('school');
        $search_query = $this->input->post('search');
        $search_result = NULL;
        if( ! empty( $search_query ) )
        {
            // Run the Query
            $query_result = $this->school->search( $search_query );

            // Redirect immediately if exact match found
            if( sizeof( $query_result ) == 1 )
            {
                $school = array_shift( $query_result );
                if( $this->input->is_ajax_request() )
                {
                    echo "REDIRECT " . string2uri( $school['full_name'] );
                    return;
                }
                else
                {
                    header( "location: /" . string2uri( $school['full_name'] . "/courses" ) );
                    return;
                }
            }

            // Display list of results if not exact match
            else
            {
                $search_result_params = array();
                $search_result_params['schools'] = $query_result;
                $search_result_params['query'] = $search_query;
                $search_result = $this->load->view('school/school_search_result', $search_result_params, TRUE);

                if( $this->input->is_ajax_request() )
                {
                    echo $search_result;
                    return;
                }
            }
        }

        // Statistics
        $this->load->model('course_professor_review');
        $total_reviews = $this->course_professor_review->count_all();
        $total_schools = $this->school->count_all();

        // Custom Parameters
        $this->view_params['search_result'] = $search_result;
        $this->view_params['total_reviews'] = number_format($total_reviews, 0, ".", ",");
        $this->view_params['total_schools'] = number_format($total_schools, 0, ".", ",");

        // Layout Parameters
        $this->view_params['page_title'] = "Welcome to StudyMonkey!";
        $this->view_params['page_content'] = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.";
		$this->load->view('info/home', $this->view_params);
	}

    public function contact()
    {
        $this->load->helper('email');

        $subject_topics = array(
            "feedback"  => "Suggestions / Feedback",
            "campus"    => "I want StudyMonkey at MY school!",
            "media"     => "Media Information",
            "general"   => "General Inquiry");

        // Accept incoming post
        $subject    = $this->input->post('subject');
        $name       = $this->input->post('name');
        $email      = $this->input->post('email');
        $message    = $this->input->post('message');
        $captcha    = $this->input->post('captcha');

        // Was this a form submission?
        if ( ! empty( $subject ) )
        {
            // Form Validation
            if ( ! isset( $subject_topics[$subject] ) OR empty( $name ) OR empty( $email ) OR empty( $message ) )
                $notification = Notification::error('You have empty fields.');
            else
            {
                if( filter_var( $email, FILTER_VALIDATE_EMAIL ) == false )
                    $notification = Notification::error('Please enter a valid email address.');
                else
                {
                    if( $captcha != $this->session->userdata('captcha_answer') )
                        $notification = Notification::error('You got the math question wrong.');
                    else
                    {
                        $success = Email::contact_us($subject_topics, $subject, $name, $email, $message);
                        if ($success)
                            $notification = Notification::success("Your message has been sent - we'll get back to you as soon as we can!");
                        else
                            $notification = Notification::success("Your message failed to send - please try again in a few moments....");
                    }
                }
            }
        }

        // Default subject select to previous submission if applicable
        $inquiry_default = empty($subject)? 'feedback' : $subject;

        // Custom Parameters
        $this->view_params['subject_topics'] = $subject_topics;
        $this->view_params['inquiry_default'] = $inquiry_default;
        $this->view_params['name'] = $name;
        $this->view_params['email'] = $email;
        $this->view_params['message'] = $message;
        $this->view_params['captcha'] = $captcha;

        // Layout Parameters
        $this->view_params['notification'] = empty($notification)? NULL : $notification;
        $this->view_params['page_tab'] = "Learn more";
        $this->view_params['page_title'] = "Contact Us";
        $this->view_params['page_subtitle'] = NULL;
        $this->view_params['page_subtitle2'] = NULL;
        $this->view_params['page_content'] = $this->load->view('info/contact', $this->view_params, TRUE);
		$this->load->view('_layout_main', $this->view_params);
    }

    public function terms()
    {
        // Layout Parameters
        $this->view_params['page_tab'] = "Learn more";
        $this->view_params['page_title'] = "Terms and Conditions";
        $this->view_params['page_subtitle'] = NULL;
        $this->view_params['page_subtitle2'] = NULL;
        $this->view_params['page_content'] = $this->load->view('info/terms', $this->view_params, TRUE);
		$this->load->view('_layout_main', $this->view_params);
    }

    public function privacy()
    {
        // Layout Parameters
        $this->view_params['page_tab'] = "Learn more";
        $this->view_params['page_title'] = "Privacy Policy";
        $this->view_params['page_subtitle'] = NULL;
        $this->view_params['page_subtitle2'] = NULL;
        $this->view_params['page_content'] = $this->load->view('info/privacy', $this->view_params, TRUE);
		$this->load->view('_layout_main', $this->view_params);
    }

    public function notesolution()
    {
        // Layout Parameters
        $this->view_params['page_tab'] = "Learn more";
        $this->view_params['page_title'] = "Welcome to the new StudyMonkey!";
        $this->view_params['page_subtitle'] = NULL;
        $this->view_params['page_subtitle2'] = NULL;
        $this->view_params['page_content'] = $this->load->view('info/notesolution', $this->view_params, TRUE);
		$this->load->view('_layout_main', $this->view_params);
    }
}

/* End of file info.php */
/* Location: ./application/controllers/info.php */