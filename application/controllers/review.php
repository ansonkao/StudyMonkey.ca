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
        $review['school_id']                    = $school['id'];
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
        $review['username']                     = $this->input->post('username');
        $review['gender']                       = $this->input->post('gender');
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
        }

        // Successful save
        if( $response->is_success() )
        {
            // Update totals for course and professor
            $this->load->model('course');
            $this->load->model('professor');
            $course = $this->course->find_by_id( $review['course_id'] );
            $professor = $this->professor->find_by_id( $review['professor_id'] );
            $this->course->update_totals( $course );
            $this->professor->update_totals( $professor );

            // Identify destination page
            $uri = "/{$school_segment}/{$course_or_professor_page}s/";
            switch( $course_or_professor_page )
            {
                case "course":
                    $subject = $course['course_code'];
                    $uri .= string2uri( $course['course_code'] );
                    break;
                case "professor":
                    $subject = "{$professor['first_name']} {$professor['last_name']}";
                    $uri .= $professor['uri'];
                    break;
                default:
                    $uri = "";
            }

            // Flash success to user!
            $response = Notification::success(
                'Thanks for voicing your opinion!
                &nbsp;
                <a href="http://www.facebook.com/dialog/feed'
                        .'?app_id='         .'131408070231787'
                        .'&redirect_uri='   .urlencode( site_url( $uri ) )
                        .'&link='           .urlencode( site_url( $uri ) )
                        .'&caption='        .urlencode("{$subject} - {$school['full_name']} - StudyMonkey.ca")
                        .'&description='    .urlencode("View ratings for {$subject} at {$school['full_name']}. Get the inside scoop on the best courses your friends have taken and the professors they have taken them with!")
                        .'&picture='        .urlencode("http://www.studymonkey.ca/image/social/mascot_facebook_share.png")
                            . '" target="_blank">
                    <img src="/image/social/facebook_share.gif" alt="Share on Facebook!" title="Share on Facebook!" style="vertical-align: bottom;" />
                </a>
                &nbsp;
                <a href="http://twitter.com/'  // USE rawurlencode() to get %20 instead of + for spaces - twitter behaviour is weird
                        .'?status=' .rawurlencode("I just rated {$subject} at StudyMonkey.ca " . site_url($uri) )
                        .'" target="_blank">
                    <img src="/image/social/tweet_button.png" alt="Tweet it!" title="Tweet it!" style="vertical-align: bottom;" />
                </a>'
                );
            $this->session->set_flashdata( array( 'notification' => $response ) );

            // Redirect the user
            echo "REDIRECT {$uri}";
            return;
        }

        // Unsuccessful
        else
        {
            echo $response->to_AJAX();
            return;
        }
    }

}

/* End of file review.php */
/* Location: ./application/controllers/review.php */