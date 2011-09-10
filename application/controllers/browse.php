<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Browse extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        $this->load->helper("url");

        $data['test_record'] = $this->uri->segment(2);
		$this->load->view('browse_view', $data);
	}

	public function test_function()
	{
        $data['test_record'] = $this->uri->segment(3);
		$this->load->view('browse_view', $data);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */