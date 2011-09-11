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

    public function terms()
    {
        $this->view_params['page_tab'] = "Learn more";
        $this->view_params['page_title'] = "Terms and Conditions";
        $this->view_params['page_subtitle'] = NULL;
        $this->view_params['page_content'] = $this->load->view('info/terms', NULL, TRUE);
		$this->load->view('_layout_main', $this->view_params);
    }

    public function privacy()
    {
        $this->view_params['page_tab'] = "Learn more";
        $this->view_params['page_title'] = "Privacy Policy";
        $this->view_params['page_subtitle'] = NULL;
        $this->view_params['page_content'] = $this->load->view('info/privacy', NULL, TRUE);
		$this->load->view('_layout_main', $this->view_params);
    }
}

/* End of file _footer.php */
/* Location: ./application/views/_footer.php */