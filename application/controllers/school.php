<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class School extends CI_Controller {

    /* Blog->index()(
     *
     * - Default method
     * - Homepage
     * - Browsing of posts
     */
	public function index()
	{
        $this->view_params['page_title'] = "Home";
        $this->view_params['page_content'] = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
        $this->view_params['page_content'] .= "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
        $this->view_params['page_content'] .= "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
		$this->load->view('_layout_main', $this->view_params);
	}

    public function find()
    {
        $this->view_params['page_title'] = "Schools";
        $this->view_params['page_content'] = "TEST STUFF!!!";
		$this->load->view('_layout_main', $this->view_params);
    }
}

/* End of file _footer.php */
/* Location: ./application/views/_footer.php */