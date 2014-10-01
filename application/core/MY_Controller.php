<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	//Page info
	protected $data = array();
	protected $page_name = FALSE;
	protected $template = "main";
	protected $has_nav_top_bar = TRUE;
	protected $has_dropdown_menu_top_bar = TRUE;
	protected $has_info_panel_top_bar = TRUE;
	protected $has_top_header = TRUE;
	
	//Page contents
	protected $javascript = array();
	protected $css = array();
	protected $fonts = array();
	
	//Page Meta
	protected $title = FALSE;
	protected $description = FALSE;
	protected $keywords = FALSE;
	protected $author = FALSE;
	
	// Other
	protected $controller_name;
    protected $action_name;
    protected $previous_controller_name;
    protected $previous_action_name;
    protected $save_previous_url = FALSE;
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
		$this->previous_action_name = $this->session->flashdata('previous_action_name');

		//set the current controller and action name
		$this->controller_name = $this->router->fetch_directory() . $this->router->fetch_class();
		$this->action_name = $this->router->fetch_method();

		$this->previous_image_id = $this->session->flashdata('previous_image_id');
		$this->previous_gallery_id = $this->session->flashdata('previous_gallery_id');

		$this->adult_user = $this->user_maturity();

		$meta_config = $this->config->item('meta', 'digallery');

		$this->title = $meta_config['title'];
		$this->description = $meta_config['site_description'];
		$this->keywords = $meta_config['site_keywords'];
		$this->author = $meta_config['site_author'];
		$this->page_name = strtolower(get_class($this));
	}

	protected function user_maturity()
	{
		if (is_null($this->session->userdata('stop18')))
		{
			$this->session->set_userdata('stop18', 0);
			return 0;
		}
		else
		{
			return $this->session->userdata('stop18');
		}
	}

	protected function render ()
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

		if (file_exists(APPPATH . '../assets/js/' . $this->controller_name . '.js'))
		{
			$this->javascript[] = $this->controller_name . '.js';
		}
		
		$this->javascript[] = 'digallery.js';
		
		$view_path = $this->controller_name . '/' . $this->action_name . '.php'; //set the path of the view
		if (file_exists(APPPATH . 'views/' . $view_path)) 
		{
			$toBody["content_body"] = $this->load->view($view_path, $this->data, TRUE);  //load the view
		}
		
		$toTopBar = array();
		// top bar menu
		if ($this->has_nav_top_bar)
		{
			$toTopBar["nav"] = $this->load->view("template/nav_top_bar", '', TRUE);
		}
		
		if ($this->has_info_panel_top_bar)
		{
			if ($this->ion_auth->logged_in())
			{
				$this->load->model('posts_model');
				$toInfoPanel['number_of_unreaded_posts'] = $this->posts_model->counts_unreaded_posts($this->ion_auth->user()->row()->id);
				
				$toTopBar["info_panel"] = $this->load->view("template/info_panel_top_bar", $toInfoPanel, TRUE);
			}
		}
		
		// top bar dropdown menu
		if ($this->has_dropdown_menu_top_bar)
		{
			$toTopBar["dropdown_menu"] = $this->load->view("template/dropdown_menu_top_bar", '', TRUE);
		}
		
		$toBody["basejs"] = $this->load->view("template/basejs", $this->data, TRUE);
		$toBody["top_bar"] = $this->load->view("template/top_bar", $toTopBar, TRUE);
		$toBody["footer"] = $this->load->view("template/footer", '', TRUE);

		// static
		$toTpl["controller_name"] = $this->controller_name;
		$toTpl["action_name"] = $this->action_name;
		$toTpl["javascript"] = $this->javascript;
		$toTpl["css"] = $this->css;
		$toTpl["fonts"] = $this->fonts;
		
		// meta
		$toTpl["title"] = $this->title;
		$toTpl["description"] = $this->description;
		$toTpl["keywords"] = $this->keywords;
		$toTpl["author"] = $this->author;
		
		$toTpl["body"] = $this->load->view("template/" . $this->template, $toBody, TRUE);
		
		// render view
		$this->load->view("template/skeleton", $toTpl);
	}

    protected function save_url()
	{
    	$this->save_previous_url = true;
    }
}