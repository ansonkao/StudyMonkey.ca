<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Review extends CI_Controller
{

    function course_professor( $school_segment )
    {
        // School
        $this->load->model('school');
        $school = $this->school->find_by_uri_segment($school_segment);
        if( empty( $school ) )
        {
            show_error("Something went wrong...");
        }

        // Get post params
        $review['workload_rating']              = $this->input->post('workload_rating');
        $review['easiness_rating']              = $this->input->post('easiness_rating');
        $review['interest_rating']              = $this->input->post('interest_rating');
        $review['knowledge_rating']             = $this->input->post('knowledge_rating');
        $review['helpful_rating']               = $this->input->post('helpful_rating');
        $review['awesome_rating']               = $this->input->post('awesome_rating');
        $review['attendance_rating']            = $this->input->post('attendance_rating');
        $review['textbook_rating']              = $this->input->post('textbook_rating');
        $review['review_text']                  = $this->input->post('review_text');
        $review['overall_recommendation']       = $this->input->post('overall_recommendation');
        $course_or_professor_id = $this->input->post('course_professor_id');
        $course_or_professor_page = $this->input->post('course_or_professor_page');
        $course_or_professor_page_id = $this->input->post('course_or_professor_page_id');

        // Validate data
        if( ! in_array( $course_or_professor_page, array( "course", "professor" ) ) )
        {
            show_error("Something went wrong...");
        }
        $this->load->model('course_professor_review');
        $response = $this->course_professor_review->process_new
            ( $course_or_professor_page
            , $course_or_professor_page_id
            , $course_or_professor_id
            , $review
            , $school
            );

        // Successful validation
        if( $response->is_success() )
        {
            /*
            // Validate Captcha
            $captcha = $this->input->post('captcha');
            if( $captcha != $this->session->userdata('captcha_answer') )
            {
                $response = Notification::error('You got the math question wrong.');
            }

            // Save new review, passed all tests
            else
            {
                $this->course_professor_review->save( $review );
            }
            */
            $this->course_professor_review->save( $review );    // TEMP until captcha's implemented
        }

        // Whatever response we have at this point, flash it to user
        $this->session->set_flashdata( array( 'notification' => $response ) );

        // Redirect the user
        switch( $course_or_professor_page )
        {
            case "course":
                $this->load->model('course');
                $course = $this->course->find_by_id( $review['course_id'] );
                $uri = "/" . string2uri( $course['course_code'] );
                break;
            case "professor":
                $this->load->model('professor');
                $professor = $this->professor->find_by_id( $review['professor_id'] );
                $uri = "/" . string2uri( $professor['first_name'] ) . "_" . string2uri( $professor['last_name'] );
                break;
            default:
                $uri = "";
        }
        header( "location: /" . string2uri( $school['full_name'] . "/{$course_or_professor_page}s" . $uri ) );
        return;
    }

}

/* End of file review.php */
/* Location: ./application/controllers/review.php */