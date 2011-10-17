<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends CI_Controller
{
    function _remap( $method )
    {
        switch( $method )
        {
            case "studymonkey-partnership":
            {
                // Layout Parameters
                $this->view_params['page_tab'] = "Learn more";
                $this->view_params['page_title'] = "Welcome to the new StudyMonkey!";
                $this->view_params['page_subtitle'] = NULL;
                $this->view_params['page_subtitle2'] = NULL;
                $this->view_params['page_content'] = $this->load->view('blog/studymonkey-partnership', $this->view_params, TRUE);
                $this->load->view('_layout_main', $this->view_params);
                break;
            }
            default:
            {
                show_404( "blog/$method" );
                break;
            }
        }
    }
}

/* End of file blog.php */
/* Location: ./application/controllers/blog.php */