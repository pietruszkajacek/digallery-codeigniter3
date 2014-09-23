<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Gallery extends MY_Controller
{
	private $gallery_comments_config;

	public function __construct()
	{
		parent::__construct();

		$this->gallery_comments_config = $this->config->item('gallery_comments', 'digallery');
	}

	public function view($gallery_id = 0, $current_image_index = 1, $name = '')
	{
		$this->load->model('browse_model');
		$this->load->model('comments_model');
		$this->load->model('evaluations_model');
		$this->load->library('typography');
		$this->load->helper(array('urllinker', 'urlslug', 'browse'));

		if (($gallery = $this->browse_model->get_gallery(intval($gallery_id))) === FALSE)
		{
			show_error("Galeria nie występuje...", 404, 'Błąd!');
		}
		
		if (($current_page = $this->current_page(5)) === FALSE)
		{
			show_error("Strona nie występuje...", 404, 'Błąd!');
		}
		
		$current_image_index = intval($current_image_index);
		$gallery_images = $this->browse_model->get_gallery_images($gallery_id);
		
		if ($current_image_index < 1 || $current_image_index > count($gallery_images))
		{
			show_error("Brak pracy w galerii...", 404, 'Błąd!');
		}		

		$this->data['adult_user'] = $this->adult_user;
				
		$all_gallery_comments = $this->comments_model->counts_gallery_comments($gallery_id);

		if ($all_gallery_comments > 0)
		{
			$last_page = ceil($all_gallery_comments / $this->gallery_comments_config['page_size']);

			if ($current_page > $last_page)
			{
				$current_page = $last_page;
			}

			$gallery_comments = $this->comments_model->get_gallery_comments($gallery_id, $this->gallery_comments_config['page_size'], $current_page);

			foreach ($gallery_comments as &$gallery_comment)
			{
				$gallery_comment->comment = $this->typography->auto_typography(htmlEscapeAndLinkUrls($gallery_comment->comment), TRUE);
				$gallery_comment->signature = $this->typography->auto_typography(htmlEscapeAndLinkUrls($gallery_comment->signature), TRUE);
			}

			$this->data['object_comments'] = $gallery_comments;
			$this->data['pagination_links'] = $this->pagination_links("/gallery/view/{$gallery_id}/" . $current_image_index, $this->gallery_comments_config['page_size'], 
					$all_gallery_comments, 5);
		}
		else
		{
			$this->data['object_comments'] = array();
		}
		
		$this->data['can_comment'] = $gallery->can_comment;
		$this->data['comment_object_owner'] = $gallery->user_id;
		
		$this->data['can_evaluate'] = $gallery->can_evaluate;
				
		$this->data['gallery_images'] = $gallery_images;
		$this->data['current_image_index'] = $current_image_index;
		
		$this->data['preview_image'] = $gallery_images[$current_image_index - 1];
		
		if ($current_image_index - 1 > 0)
		{
			$this->data['previous_image_index'] = $current_image_index - 1;
		}

		if ($current_image_index + 1 <= count($this->data['gallery_images']))
		{
			$this->data['next_image_index'] = $current_image_index + 1;
		}		
				
		$cats_uri_rows = $this->browse_model->get_cats_uri_rows($this->browse_model->build_path_cats($gallery->category_id, 'galleries'), 'galleries');
		$this->data['gallery_cats_path'] = create_hierarchical_path(base_url() . 'browse/galleries/', $cats_uri_rows);
		
		$cats_uri_rows = $this->browse_model->get_cats_uri_rows($this->browse_model->build_path_cats($gallery_images[$current_image_index - 1]->category_id, 'images'), 'images');
		$this->data['current_image_cats_path'] = create_hierarchical_path(base_url() . 'browse/images/', $cats_uri_rows);
		
		$this->data['user_gallery'] = $this->browse_model->get_user($gallery->user_id);

		$this->data['gallery'] = $gallery;
		
		if ($this->ion_auth->logged_in())
		{
			$logged_in_user = $this->ion_auth->user()->row();
			$this->data['logged_in_user'] = $logged_in_user;
			$this->data['object_rated'] = $this->evaluations_model->rated_gallery($gallery->id, $logged_in_user->id);
		}

		$this->data['thumb_mini_config'] = $this->config->item('thumb_mini', 'digallery');
		$this->data['thumb_preview_config'] = $this->config->item('thumb_preview', 'digallery');
		$this->data['avatars_config'] = $this->config->item('avatar', 'digallery');
		
		$this->data['name_of_ratings'] = $this->config->item('name_of_ratings', 'digallery');
		
		$this->data['js'][] = 'comments.js';
		$this->data['js'][] = 'jquery.jcarousel.min.0.2.8.js';
		//$this->data['js'][] = 'jquery.jcarousel.min.0.3.0.js';
		//$this->data['js'][] = 'gallery_view.js';

		$this->render();
	}

	private function current_page($uri_segment)
	{
		$current_page = intval($this->uri->segment($uri_segment));

		if ($current_page == 0)
		{
			$current_page = 1;
		}
		elseif ($current_page < 0)
		{
			$current_page = FALSE;
		}

		return $current_page;
	}

	private function pagination_links($url, $page_size, $total, $uri_segment)
	{
		$this->load->library('pagination');

		$config['uri_segment'] = $uri_segment;
		$config['base_url'] = base_url() . $url;
		$config['use_page_numbers'] = TRUE;
		$config['num_links'] = 2;
		$config['per_page'] = $page_size;

		$config['total_rows'] = $total;

		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';

		$config['first_link'] = 'Pierw.';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';

		$config['last_link'] = 'Ostat.';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		$config['next_link'] = '»';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '«';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';

		$config['cur_tag_open'] = '<li class="active"><a href="#" onclick="return false;">';
		$config['cur_tag_close'] = '</a></li>';

		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);

		return $this->pagination->create_links();
	}

	public function soft_delete_gallery($gallery_id)
	{
		$this->load->model('browse_model');

		if ($this->input->is_ajax_request())
		{
			$gallery_id = intval($gallery_id);

			if ($this->ion_auth->logged_in())
			{
				$user = $this->ion_auth->user()->row();
				$gallery = $this->browse_model->get_gallery($gallery_id);

				if ($gallery === FALSE)
				{
					show_error('Nie ma takiej galerii', 404);
				}

				if ($user->id === $gallery->user_id)
				{
					if ($this->browse_model->soft_delete_gallery($gallery_id))
					{
						$this->output
								->set_content_type('application/json')
								->set_output(json_encode(array("status" => "1")));
					}
					else
					{
						$this->output
								->set_content_type('application/json')
								->set_output(json_encode(array("status" => "0")));
					}
				}
			}
			else
			{
				// ....gdy niezalogowany
				$this->output->set_status_header('403');
			}
		}
	}
}

/* End of file artwork.php */
/* Location: ./application/controllers/gallery.php */
