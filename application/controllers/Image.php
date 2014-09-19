<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Image extends MY_Controller
{
	private $who_add_favs_config;
	private $image_comments_config;

	public function __construct()
	{
		parent::__construct();

		$this->who_add_favs_config = $this->config->item('who_add_favorites', 'digallery');
		$this->image_comments_config = $this->config->item('image_comments', 'digallery');
	}

	public function stop18_confirm()
	{
		if ($this->input->is_ajax_request())
		{
			$this->session->set_userdata('stop18', 1);
			$this->output
					->set_content_type('application/json')
					->set_output(json_encode(array("status" => 1)));
		}
	}	
	
	public function download($image_id)
	{
		$this->load->model('browse_model');
		$this->load->helper('download');
		
		if (($image = $this->browse_model->get_image(intval($image_id))) === FALSE)
		{
			show_error("Praca nie występuje...", 404, 'Błąd!');
		}		

		$images_config = $this->config->item('uploads', 'digallery');
		
		$data = file_get_contents($images_config['path'] . $image->file_name); // Wczytujemy zawartość pliku
		$name = $image->file_name;

		force_download($name, $data);
	}
	
	public function zoom($image_id = 0)
	{
		$this->load->model('browse_model');
		$this->load->helper(array('browse', 'urlslug'));

		if (($image = $this->browse_model->get_image(intval($image_id))) === FALSE)
		{
			show_error("Praca nie występuje...", 404, 'Błąd!');
		}
		
		$this->data['previous_image_id_name'] = $this->browse_model->get_user_prev_image_id_name($image->user_id, $image->id);
		$this->data['next_image_id_name'] = $this->browse_model->get_user_next_image_id_name($image->user_id, $image->id);
		
		$cats_uri_rows = $this->browse_model->get_cats_uri_rows($this->browse_model->build_path_cats($image->category_id, 'images'), 'images');
		$cats_path = create_hierarchical_path(base_url() . 'browse/images/', $cats_uri_rows);		
		$this->data['cats_path'] = $cats_path;
		
		$this->data['image'] = $image;
		
		$this->data['images_config'] = $this->config->item('uploads', 'digallery');
		$this->data['avatars_config'] = $this->config->item('avatar', 'digallery');
		//$this->data['thumb_preview_config'] = $this->config->item('thumb_preview', 'digallery');
		
		$this->data['user_image'] = $this->browse_model->get_user($image->user_id);
		$this->data['adult_user'] = $this->adult_user;
		
		if ($this->ion_auth->logged_in())
		{
			$this->data['logged_in_user'] = $this->ion_auth->user()->row();
		}		
		
		$this->increment_downloads($image_id);
		
		//$this->data['js'][] = 'zoom.js';
		
		$this->data['no_container_class'] = TRUE;
		$this->render();
	}
	
	public function increment_downloads($image_id)
	{
		if ($this->previous_controller_name == 'image' && $this->previous_action_name == 'preview')
		{
			$this->browse_model->increment_downloads($image_id);
		}
	}
	
	public function increment_views($image_id)
	{
		if ($this->previous_image_id === FALSE || $this->previous_image_id !== $image_id)
		{
			$this->browse_model->increment_views($image_id);
		}
	}	
	
	public function preview($image_id = 0, $name = '')
	{	
		$this->load->model('browse_model');
		$this->load->model('comments_model');
		$this->load->model('evaluations_model');
		$this->load->library('typography');
		$this->load->helper(array('browse', 'urllinker', 'urlslug'));
	
		if (($image = $this->browse_model->get_image(intval($image_id))) === FALSE)
		{
			show_error("Praca nie występuje...", 404, 'Błąd!');
		}

		if (($current_page = $this->current_page(4)) === FALSE)
		{
			show_error("Strona nie występuje...", 404, 'Błąd!');
		}

		$this->data['adult_user'] = $this->adult_user;
		
		$this->data['previous_image_id_name'] = $this->browse_model->get_user_prev_image_id_name($image->user_id, $image->id);
		$this->data['next_image_id_name'] = $this->browse_model->get_user_next_image_id_name($image->user_id, $image->id);
		
		$all_image_comments = $this->comments_model->counts_image_comments($image_id);

		if ($all_image_comments > 0)
		{
			$last_page = ceil($all_image_comments / $this->image_comments_config['page_size']);

			if ($current_page > $last_page)
			{
				$current_page = $last_page;
			}

			$image_comments = $this->comments_model->get_image_comments($image_id, $this->image_comments_config['page_size'], $current_page);

			foreach ($image_comments as &$image_comment)
			{
				$image_comment->comment = $this->typography->auto_typography(htmlEscapeAndLinkUrls($image_comment->comment), TRUE);
				$image_comment->signature = $this->typography->auto_typography(htmlEscapeAndLinkUrls($image_comment->signature), TRUE);
			}

			$this->data['object_comments'] = $image_comments;
			$this->data['pagination_links'] = $this->pagination_links("/image/preview/{$image_id}/", $this->image_comments_config['page_size'], $all_image_comments, 4);
		}
		else
		{
			$this->data['object_comments'] = array();
		}

		$this->data['can_comment'] = $image->can_comment;
		$this->data['comment_object_owner'] = $image->user_id;
		$this->data['can_evaluate'] = $image->can_evaluated;
		
		$cats_uri_rows = $this->browse_model->get_cats_uri_rows($this->browse_model->build_path_cats($image->category_id, 'images'), 'images');
		$cats_path = create_hierarchical_path(base_url() . 'browse/images/', $cats_uri_rows);

		$user_image = $this->browse_model->get_user($image->user_id);

		$this->data['number_views'] = $this->browse_model->counts_views($image->id);
		$this->data['number_views_today'] = $this->browse_model->counts_views_today($image->id);

		$this->increment_views($image->id);

		$this->data['number_downloads'] = $this->browse_model->counts_downloads($image->id);
		$this->data['number_downloads_today'] = $this->browse_model->counts_downloads_today($image->id);

		//$this->browse_model->increment_downloads($image->id);

		$this->data['user_image'] = $user_image;
		$this->data['thumbs_mini'] = $this->browse_model->get_thumb_images(0, 0, 'dd', $image->user_id, 1, 8);

		$this->data['preview_image'] = $image;
		
		$this->data['cats_path'] = $cats_path;

		$this->data['number_favs'] = $this->browse_model->counts_favorites($image->id);
		$this->data['number_favs_today'] = $this->browse_model->counts_favorites_today($image->id);

		if ($this->ion_auth->logged_in())
		{
			$logged_in_user = $this->ion_auth->user()->row();
			$this->data['logged_in_user'] = $logged_in_user;
			$this->data['image_added_to_favs'] = $this->browse_model->added_to_favorites($image_id, $logged_in_user->id);
			$this->data['object_rated'] = $this->evaluations_model->rated_image($image->id, $logged_in_user->id);
		}

		$this->data['thumb_mini_config'] = $this->config->item('thumb_mini', 'digallery');
		$this->data['thumb_preview_config'] = $this->config->item('thumb_preview', 'digallery');
		$this->data['avatars_config'] = $this->config->item('avatar', 'digallery');

		if (($image->plus_18 && !$this->adult_user) && !(isset($logged_in_user) && $logged_in_user->id === $image->user_id))
		{
			$this->data['adult_filter'] = TRUE;
		}
		else
		{
			$this->data['adult_filter'] = FALSE;
		}
		
		$this->data['js'][] = 'comments.js';
		//$this->data['js'][] = 'preview.js';

		$this->data['name_of_ratings'] = $this->config->item('name_of_ratings', 'digallery');

		$this->render();
	}

	public function add_remove_favorite($image_id)
	{
		$this->load->model('browse_model');

		if ($this->input->is_ajax_request())
		{
			$image_id = intval($image_id);

			if ($this->ion_auth->logged_in())
			{
				$user = $this->ion_auth->user()->row();
				$image = $this->browse_model->get_image($image_id);

				if ($image === FALSE)
				{
					$this->output->set_status_header('404');
					return;
				}

				if ($user->id !== $image->user_id)
				{
					if ($this->browse_model->added_to_favorites($image_id, $user->id))
					{
						if ($this->browse_model->remove_from_favorites($image_id, $user->id))
						{
							$status = 1;
							$added = 0;
						}
						else
						{
							$status = 0;
						}
					}
					else
					{
						if ($this->browse_model->add_to_favorites($image_id, $user->id))
						{
							$status = 1;
							$added = 1;
						}
						else
						{
							$status = 0;
						}
					}

					$ajx_output = array();
					$ajx_output['status'] = $status;

					if ($status)
					{
						$ajx_output['added'] = $added;
						$ajx_output['number_favs'] = intval($this->browse_model->counts_favorites($image_id));
					}

					$this->output
							->set_content_type('application/json')
							->set_output(json_encode($ajx_output));
				}
			}
			else
			{
				$this->output
						->set_content_type('application/json')
						->set_output(json_encode(array("status" => 0)));
			}
		}
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

	public function who_add_favorites($image_id)
	{
		$this->load->model('browse_model');

		$image_id = intval($image_id);

		if (($current_page = $this->current_page(4)) === FALSE)
		{
			show_error("Strona nie występuje...", 404, 'Błąd!');
		}

		$all_favs = $this->browse_model->counts_favorites($image_id);

		if ($all_favs > 0)
		{
			$last_page = ceil($all_favs / $this->who_add_favs_config['page_size']);

			if ($current_page > $last_page)
			{
				$current_page = $last_page;
			}

			$this->data['favorites'] = $this->browse_model->who_favorites($image_id, $this->who_add_favs_config['page_size'], $current_page);
			$this->data['offset'] = ($current_page - 1) * $this->who_add_favs_config['page_size'];
			$this->data['pagination_links'] = $this->pagination_links("/image/who_add_favorites/{$image_id}/", $this->who_add_favs_config['page_size'], $all_favs, 4);
		}
		else
		{
			$this->data['favorites'] = array();
		}

		echo $this->load->view('/image/who_add_favorites', $this->data, TRUE);
	}

	public function _delete_image($image_id)
	{
		$this->load->model('browse_model');

		if ($this->input->is_ajax_request())
		{
			$image_id = intval($image_id);

			if ($this->ion_auth->logged_in())
			{
				$user = $this->ion_auth->user()->row();
				$image = $this->browse_model->get_image($image_id);

				if ($image === FALSE)
				{
					show_error('Nie ma takiej pracy', 404);
				}

				if ($user->id === $image->user_id)
				{
					$this->db->trans_begin();

					$this->browse_model->delete_image($image_id);

					if ($this->db->trans_status() === FALSE)
					{
						$this->db->trans_rollback();

						$this->output
								->set_content_type('application/json')
								->set_output(json_encode(array("status" => "0")));
					}
					else
					{
						$this->db->trans_commit();

						unlink("uploads/" . $image->file_name);
						unlink("uploads/thumbs_mini/" . $image->file_name);
						unlink("uploads/thumbs_small/" . $image->file_name);
						unlink("uploads/thumbs_preview/" . $image->file_name);

						$this->output
								->set_content_type('application/json')
								->set_output(json_encode(array("status" => "1")));
					}
				}
			}
			else
			{
				// ....gdy niezalogowany
			}
		}
	}


	public function soft_delete_image($image_id)
	{
		$this->load->model('browse_model');

		if ($this->input->is_ajax_request())
		{
			$image_id = intval($image_id);

			if ($this->ion_auth->logged_in())
			{
				$user = $this->ion_auth->user()->row();
				$image = $this->browse_model->get_image($image_id);

				if ($image === FALSE)
				{
					show_error('Nie ma takiej pracy', 404);
				}

				if ($user->id === $image->user_id)
				{
					if ($this->browse_model->soft_delete_image($image_id))
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


/* End of file image.php */
/* Location: ./application/controllers/image.php */
