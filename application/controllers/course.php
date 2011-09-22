<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Course extends CI_Controller
{
	public function index()
	{
        echo "<pre>";
        echo $this->uri->uri_string();
        echo "</pre>";
        exit();

        $this->view_params['page_tab'] = "Courses";
        $this->view_params['page_title'] = "Course Search";
        $this->view_params['page_subtitle'] = NULL;
        $this->view_params['page_content'] = $this->load->view('course/search', $this->view_params, TRUE);
		$this->load->view('_layout_main', $this->view_params);
	}

    public function view($school_segment, $course_segment)
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
        $professors = $this->professor->find_by_course_id($course['id']);

        // Reviews
        $this->load->model('course_professor_review');
        $reviews = $this->course_professor_review->find_by_course_id($course['id']);

        // Review authors and professors
        $this->load->model('user');
        $review_authors = array();
        $review_professors = array();
        foreach($reviews as $review)
        {
            $review_authors[$review['id']] = $this->user->find_by_id($review['user_id']);
            $review_professors[$review['id']] = $this->professor->find_by_id($review['professor_id']);
        }

        // Custom Parameters
        $this->view_params['school'] = $school;
        $this->view_params['course'] = $course;
        $this->view_params['professors'] = $professors;
        $this->view_params['reviews'] = $reviews;
        $this->view_params['review_authors'] = $review_authors;
        $this->view_params['review_professors'] = $review_professors;

        // Layout Parameters
        $this->view_params['page_tab'] = "Courses";
        $this->view_params['page_title'] = $course['course_code'];
        $this->view_params['page_subtitle'] = $school['full_name'];
        $this->view_params['page_content'] = $this->load->view('course/view', $this->view_params, TRUE);
		$this->load->view('_layout_main', $this->view_params);
    }
}

/* End of file courses.php */
/* Location: ./application/controllers/courses.php */