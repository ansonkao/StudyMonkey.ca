<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info extends CI_Controller {

	public function index()
	{
        $this->view_params['page_tab'] = "Home";
        $this->view_params['page_title'] = "Welcome to StudyMonkey!";
        $this->view_params['page_subtitle'] = "Home &#187; Learn more &#187; Terms and Conditions";
        $this->view_params['page_content'] = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
        $this->view_params['page_content'] .= "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
        $this->view_params['page_content'] .= "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
		$this->load->view('_layout_main', $this->view_params);
	}

    public function contact()
    {
        $inquiry_categories = array(
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

        // Form Validation
        if (!isset($inquiry_categories[$subject]) OR empty($name) OR empty($email) OR empty($message) OR empty($captcha))
            $notification = Notification::error('You have empty fields.');
        else
        {
            if (filter_var($email, FILTER_VALIDATE_EMAIL) == false)
                $notification = Notification::error('Please enter a valid email address.');
            else
            {
                if ($this->input->post('captcha') != $this->session->flashdata('captcha_answer'))
                    $notification = Notification::error('You got the math question wrong.');
                else
                {
                    $notification = Notification::success('Your message has been sent to us!');

                    /*
                    $new_email = new email();
                    $new_email->tag("Contact Us Form");

                    $new_email->from_account("notification");
                    $new_email->replyTo($_POST['email'], $_POST['name']);
                    $new_email->to($_POST['subject']."@studymonkey.ca", "Administrator");
                    $new_email->subject($inquiry[$_POST['subject']] . " (" . $_POST['name'] . ")");
                    $new_message = $_POST['body'] . "\n\nRespond to {$_POST['name']} at {$_POST['email']}";

                    $new_email->messageHtml(nl2br($new_message));
                    $new_email->messagePlain(strip_tags($new_message));

                    if ($new_email->send()) {
                        session::set_flash("Your message was sent - we will get back to you as soon as we can! :)");
                    } else {
                        session::set_flash("ERROR: Failed to send message!", false);
                    }
                    $new_email->update_email_log("contact_us_form");
                     * 
                     */
                }
            }
        }

        // Default subject select to previous submission if applicable
        $inquiry_default = empty($subject)? 'feedback' : $subject;

        // Custom Parameters
        $this->view_params['inquiry_categories'] = $inquiry_categories;
        $this->view_params['inquiry_default'] = $inquiry_default;
        $this->view_params['name'] = $name;
        $this->view_params['email'] = $email;
        $this->view_params['message'] = $message;
        $this->view_params['captcha'] = $captcha;

        // Layout Parameters
        $this->view_params['notification'] = $notification;
        $this->view_params['page_tab'] = "Learn more";
        $this->view_params['page_title'] = "Contact Us";
        $this->view_params['page_subtitle'] = NULL;
        $this->view_params['page_content'] = $this->load->view('info/contact', $this->view_params, TRUE);
		$this->load->view('_layout_main', $this->view_params);
    }

    public function terms()
    {
        // Layout Parameters
        $this->view_params['page_tab'] = "Learn more";
        $this->view_params['page_title'] = "Terms and Conditions";
        $this->view_params['page_subtitle'] = NULL;
        $this->view_params['page_content'] = $this->load->view('info/terms', $this->view_params, TRUE);
		$this->load->view('_layout_main', $this->view_params);
    }

    public function privacy()
    {
        // Layout Parameters
        $this->view_params['page_tab'] = "Learn more";
        $this->view_params['page_title'] = "Privacy Policy";
        $this->view_params['page_subtitle'] = NULL;
        $this->view_params['page_content'] = $this->load->view('info/privacy', $this->view_params, TRUE);
		$this->load->view('_layout_main', $this->view_params);
    }
}

/* End of file info.php */
/* Location: ./application/controllers/info.php */