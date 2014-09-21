<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    protected $data = Array();
    protected $controller_name;
    protected $action_name;
    protected $previous_controller_name;
    protected $previous_action_name;
    protected $save_previous_url = FALSE;
    protected $page_title;

	protected $adult_user = 0;
	protected $previous_image_id;
	protected $previous_gallery_id;
	
    public function __construct()
	{
        parent::__construct();

		$this->config->load('digallery', TRUE);
		$this->load->database();
		$this->load->driver('session');
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'html'));
		$this->lang->load('digallery', 'polish');

		$this->form_validation->set_error_delimiters('', '');

        //save the previous controller and action name from session
        $this->previous_controller_name = $this->session->flashdata('previous_controller_name');
        $this->previous_action_name     = $this->session->flashdata('previous_action_name');
		
        //set the current controller and action name
        $this->controller_name			= $this->router->fetch_directory() . $this->router->fetch_class();
        $this->action_name				= $this->router->fetch_method();

		$this->previous_image_id		= $this->session->flashdata('previous_image_id');
		$this->previous_gallery_id		= $this->session->flashdata('previous_gallery_id');		
		
		$this->adult_user = $this->user_maturity();
		
        $this->data['top_bar'] = '';
		$this->data['header_bar'] = '';
		$this->data['content'] = '';
        $this->data['css'] = '';
		$this->data['js'] = array();
		$this->data['controller_name'] = $this->controller_name;
		$this->data['action_name'] = $this->action_name;
    }

	protected function user_maturity()
	{
		if ($this->session->userdata('stop18') === FALSE)
		{
			$this->session->set_userdata('stop18', 0);
			return 0;
		}
		else
		{
			return $this->session->userdata('stop18');
		}
	}
	
	protected function init_default_top_bar()
	{
		if (!$this->ion_auth->logged_in())
		{
			$this->data['nav_bar_top']['form_attr'] = array(
				'id' => 'signin-form',
			);

			//dane pola email...
			$this->data['nav_bar_top']['email'] = array(
				'name' => 'email',
				'id' => 'email',
				'class' => 'span4',
				'type' => 'text',
			);

			//dane etykiety pola email
			$this->data['nav_bar_top']['email_label'] = array(
				'for' => 'email',
				'text' => 'Email',
			);

			//dane pola hasło...
			$this->data['nav_bar_top']['password'] = array(
				'name' => 'password',
				'id' => 'password',
				'class' => 'span4',
				'type' => 'password',
			);

			//dane etykiety pola password
			$this->data['nav_bar_top']['password_label'] = array(
				'for' => 'password',
				'text' => 'Hasło',
			);

			//dane pola zapamiętaj mnie...
			$this->data['nav_bar_top']['remember'] = array(
				'name' => 'remember',
				'value' => '1',
				'checked' => FALSE,
			);
		}
	}

	protected function render($template = 'main')
	{
		//save the controller and action names in session
		if ($this->save_previous_url)
		{
			$this->session->set_flashdata('previous_controller_name', $this->previous_controller_name);
			$this->session->set_flashdata('previous_action_name', $this->previous_action_name);
		}
		else
		{
			$this->session->set_flashdata('previous_controller_name', $this->controller_name);
			$this->session->set_flashdata('previous_action_name', $this->action_name);
		}
		
		//zachowaj id zdjecia jesli jestes w kontrolerze image i akcji preview
		if ($this->controller_name === 'image' && $this->action_name === 'preview')
		{
			$current_image_id = $this->uri->segment(3);
			
			if ($this->previous_image_id === FALSE || $this->previous_image_id != $current_image_id)
			{
				$this->session->set_flashdata('previous_image_id', $current_image_id);
			}
			else
			{
				$this->session->keep_flashdata('previous_image_id');
			}
		}
		
		$view_path = $this->controller_name . '/' . $this->action_name . '.php'; //set the path off the view
		
		if (file_exists(APPPATH . 'views/top_bar/' . $view_path))
		{
			$this->data['top_bar'] .= $this->load->view('/top_bar/' . $view_path, $this->data, true);  //load the top_bar
		}
		else
		{
			$this->init_default_top_bar();
			
			$this->data['top_bar'] .= $this->load->view('/top_bar/default_top_bar.php', $this->data, true);  //load the default top_bar
		}

		if (file_exists(APPPATH . 'views/header_bar/' . $view_path))
		{
			$this->data['header_bar'] .= $this->load->view('/header_bar/' . $view_path, $this->data, true);  //load the header_bar
		}
		else
		{
			$this->data['header_bar'] .= $this->load->view('/header_bar/default_header_bar.php', $this->data, true);  //load the default header_bar
		}
				
		if (file_exists(APPPATH . 'views/' . $view_path))
		{
			$this->data['content'] .= $this->load->view($view_path, $this->data, true);  //load the view
		}
		
		if (file_exists(APPPATH . '../assets/js/' . $this->controller_name . '.js'))
		{
			$this->data['js'][] = $this->controller_name . '.js';
		}
		
		$this->data['js'][] = 'digallery.js';
		
		$this->load->view("layouts/$template.tpl.php", $this->data);  //load the template
	}

    protected function save_url()
	{
    	$this->save_previous_url = true;
    }
}