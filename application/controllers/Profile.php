<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends MY_Controller
{
	private $default_avatar;
	private $avatars_path;
	private $avatars_file_type;
	private $avatar_max_size;
	private $avatar_max_width;
	private $avatar_max_height;

	private $default_picture;
	private $picture_path;
	private $pictures_file_type;
	private $picture_max_size;
	private $picture_max_width;
	private $picture_max_height;

	private $uploads_path;
	private $uploads_file_type;
	private $uploads_file_max_size;
	private $uploads_file_max_filename;

	private $thumbs1col_path;
	private $thumbs2col_path;
	private $thumbs11col_path;

	private $mini_thumb_width;
	private $mini_thumb_height;

	private $browse_thumb_width;
	private $browse_thumb_height;

	private $preview_thumb_width;
	private $preview_thumb_height;
	
	private $profile_comments_config;

	public function __construct()
	{
		parent::__construct();

		$this->load->model('browse_model');
		
		$uploads = $this->config->item('uploads', 'digallery');

		$this->uploads_path = $uploads['path'];
		$this->uploads_file_type = $uploads['file_type'];
		$this->uploads_file_max_size = $uploads['max_size'];
		$this->uploads_file_max_filename = $uploads['max_filename'];

		$avatar = $this->config->item('avatar', 'digallery');

		$this->default_avatar = $avatar['default'];
		$this->avatars_path = $avatar['path'];
		$this->avatars_file_type = $avatar['file_type'];
		$this->avatar_max_size = $avatar['max_size'];
		$this->avatar_max_width = $avatar['max_width'];
		$this->avatar_max_height = $avatar['max_height'];

		$picture = $this->config->item('picture', 'digallery');

		$this->default_picture = $picture['default'];
		$this->picture_path = $picture['path'];
		$this->pictures_file_type = $picture['file_type'];
		$this->picture_max_size = $picture['max_size'];
		$this->picture_max_width = $picture['max_width'];
		$this->picture_max_height = $picture['max_height'];
		
		$thumb_mini = $this->config->item('thumb_mini', 'digallery');

		$this->thumbs1col_path = $thumb_mini['path'];
		$this->mini_thumb_width = $thumb_mini['width'];
		$this->mini_thumb_height = $thumb_mini['height'];

		$thumb_small = $this->config->item('thumb_small', 'digallery');

		$this->thumbs2col_path = $thumb_small['path'];
		$this->browse_thumb_width = $thumb_small['width'];
		$this->browse_thumb_height = $thumb_small['height'];

		$thumb_preview = $this->config->item('thumb_preview', 'digallery');

		$this->thumbs11col_path = $thumb_preview['path'];
		$this->preview_thumb_width = $thumb_preview['width'];
		$this->preview_thumb_height = $thumb_preview['height'];
		
		$this->profile_comments_config = $this->config->item('profile_comments', 'digallery');
	}

    private function pagination_links($url, $page_size, $total, $uri_segment)
	{
		$this->load->library('pagination');

		$config['uri_segment'] = $uri_segment;
		$config['base_url'] = $url;
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
   
	private function current_page($uri_segment)
	{
		$current_page = intval($this->uri->rsegment($uri_segment));

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
    
    public function get_thumbs_images()
    {
		if ($this->input->is_ajax_request())
		{
			$this->load->model('browse_model');
			
			if ($this->ion_auth->logged_in())
			{
				if (($current_page = $this->current_page(3)) === FALSE)
                {
					$this->output->set_status_header('404');
					return;
                }
                
                $user = $this->ion_auth->user()->row();

                $all_images = $this->browse_model->get_count_thumb_images(0, 0, 'dd', $user->id);
                
				$add_gallery_config = $this->config->item('thumbs_add_gallery', 'digallery');
				
				$page_size = $add_gallery_config['page_size'];
				
                if ($all_images > 0)
                {
                    $max_pages = ceil($all_images / $page_size);

                    if ($current_page > $max_pages)
                    {
                        $this->output->set_status_header('404');
                        return;
                    }
                    
                    $this->data['thumbs_mini'] = $this->browse_model->get_thumb_images(0, 0, 'dd', $user->id, $current_page, $page_size);
                    //$this->data['pagination_links'] = $this->pagination_links("#", $page_size, $all_images, 3);
					$pagination_links = $this->pagination_links("#", $page_size, $all_images, 3);
                }
                else    
                {
                    $this->data['thumbs_mini'] = array();
                }
                
                $this->data['thumb_mini_config'] = $this->config->item('thumb_mini', 'digallery');
                
				$this->output
						->set_content_type('application/json')
						->set_output(json_encode(
								array(
									"images" => $this->load->view('/profile/thumbs_add_edit_gallery', $this->data, TRUE), 
									"pagination" => isset($pagination_links) ? $pagination_links : ''
								)));                
            }
			else
			{
                $this->output->set_status_header('403');
			}
		}        
    }
    
	public function _validate_images_in_gallery($gallery_images_in_gallery)
	{
		$msg_error = '';
		
		if (isset($gallery_images_in_gallery))
		{
			$gallery_images_in_gallery = explode(" ", $gallery_images_in_gallery);
			
			if (is_array($gallery_images_in_gallery) && count($gallery_images_in_gallery) > 0) 
			{
				$images_ids = $this->browse_model->get_users_all_images_ids($this->ion_auth->user()->row()->id);

				$result = TRUE;

				foreach ($gallery_images_in_gallery as $image_id) 
				{
					if (!in_array(array('id' => $image_id), $images_ids)) 
					{
						$result = FALSE;
						$msg_error = 'Jedna z prac dodanych do galerii nie występuje.';
						break;
					}
				}
			}
			else
			{
				$msg_error = 'Brak prac w galerii.';
				$result = FALSE;
			}
		}
		else
		{
			$msg_error = 'Brak prac w galerii.';
			$result = FALSE;
		}

		if ($result)
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('_validate_images_in_gallery', $msg_error);
			return FALSE;
		}		
	}
	
	public function add_edit_gallery()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->load->model('browse_model');
			$this->load->helper('browse');
			
			//Pobierz dane zalogowanego użytkownika
			$user = $this->ion_auth->user()->row();
			
			if (!is_null($this->uri->segment(3)))
			{
				$uri_gallery_id = intval($this->uri->segment(3));
				$gallery = $this->browse_model->get_gallery($uri_gallery_id);

				if ($gallery === FALSE || $user->id !== $gallery->user_id)
				{
					// galeria nie wystepuje w bazie lub nie należy do zalogowanego użytkownika...
					show_404();
				}
			}
			
			
			
			$this->form_validation->set_rules('gallery_name', 'Nazwa galerii', 'required');
			$this->form_validation->set_rules('gallery_images_in_gallery', 'Prace w galerii', 'callback__validate_images_in_gallery');
			$this->form_validation->set_rules('gallery_description', 'Opis galerii', 'min_length[3]');
					
			if ($this->form_validation->run() == TRUE)
			{
				
				$user_tags = split_tags(strtolower($this->input->post('tags')));
				$title_desc_tags = split_tags(strtolower($this->input->post('gallery_name') . ' ' . $this->input->post('gallery_description')));
				$tags = array_unique(array_merge($user_tags, $title_desc_tags));
						
				//Walidacja OK!
				$user_data = array(
					'user_id' => $user->id,
					'name' => $this->input->post('gallery_name'),
					'description' => $this->input->post('gallery_description'),
					'can_comment' => (bool) $this->input->post('allow_comments') ? 1 : 0,
					'images' => explode(" ", $this->input->post('gallery_images_in_gallery')),
					'category_id' => '2',
					'tags' => $tags,
					'user_tags' => $user_tags
				);					
				
				if (isset($gallery))
				{
					if ($this->browse_model->update_gallery($gallery->id, $user_data))
					{
						$this->session->set_flashdata(array('type' => 'info', 'msg' => 'Galeria została zaktualizowana...'));
					}
					else
					{
						$this->session->set_flashdata(array('type' => 'error', 'msg' => 'Niestety nie udało się zaktualizować galerii...'));
					}
				}
				else
				{
					if ($this->browse_model->add_gallery($user_data))
					{
						$this->session->set_flashdata(array('type' => 'info', 'msg' => 'Galeria została dodana...'));
					}
					else
					{
						$this->session->set_flashdata(array('type' => 'error', 'msg' => 'Niestety nie udało się utworzyć galerii...'));
					}
				}
				
				redirect("browse/galleries/", 'refresh');
			}
			else
			{
				
				$this->data['message'] = array(
					'type' => $this->session->flashdata('type'),
					'msg' => $this->session->flashdata('msg'),
				);

				$this->data['form_attr'] = array(
					'id' => 'profile-add-edit-gallery',
					'class' => 'form-horizontal',
				);

				$this->data['gallery_name'] = array(
					'name' => 'gallery_name',
					'id' => 'gallery_name',
					'class' => 'span5',
					'type' => 'text',
					'value' => $this->form_validation->set_value('gallery_name', isset($gallery) ? $gallery->name : ''),
				);

				$this->data['gallery_name_label'] = array(
					'for' => 'gallery_name',
					'text' => 'Nazwa',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['gallery_description'] = array(
					'name' => 'gallery_description',
					'id' => 'gallery_description',
					'class' => 'span5',
					'rows' => '3',
					'value' => $this->form_validation->set_value('gallery_description', isset($gallery) ? $gallery->description : ''),
				);

				$this->data['gallery_description_label'] = array(
					'for' => 'gallery_description',
					'text' => 'Opis',
					'attributes' => array(
						'class' => 'control-label',
					),
				);
				
				$this->data['tags'] = array(
					'name' => 'tags',
					'id' => 'tags',
					'class' => 'span5',
					'value' => $this->form_validation->set_value('tags', isset($gallery) ? $this->get_gallery_user_tags($gallery->id) : ''),
				);

				$this->data['tags_label'] = array(
					'for' => 'tags',
					'text' => 'Słowa kluczowe',
					'attributes' => array(
						'class' => 'control-label',
					),
				);				
				
				$this->data['allow_comments'] = array(
					'name' => 'allow_comments',
					'id' => 'allow_comments',
					'class' => 'checkbox',
					'value' => '1',
					'checked' => (bool) set_checkbox('allow_comments', '1', isset($gallery) ? (bool) $gallery->can_comment : ''),
				);

				$this->data['allow_comm_label'] = array(
					'for' => 'allow_comments',
					'text' => 'Komentarze',
					'attributes' => array(
						'class' => 'control-label',
					),
				);				
								
				if (form_error('gallery_images_in_gallery'))
				{
					$this->data['images_in_gallery'] = array();
				}
				else
				{
					if (!$this->input->post('gallery_images_in_gallery'))
					{
						$this->data['images_in_gallery'] = isset($gallery) ? $this->browse_model->get_gallery_images($gallery->id) : array();
					}
					else
					{
						$this->data['images_in_gallery'] = $this->browse_model->get_images_by_id_order_by_field(explode(" ", $this->input->post('gallery_images_in_gallery')));
					}
				}
		
				$this->data['control_groups'] = array(
					'gallery_name' => form_error('gallery_name') ? ' error' : '',
					'gallery_description' => form_error('gallery_description') ? ' error' : '',
					'gallery_images' => form_error('gallery_images_in_gallery') ? ' error' : '',
				);

				if (isset($gallery))
				{
					$this->data['gallery'] = $gallery;
				}
				
				$this->data['thumb_mini_config'] = $this->config->item('thumb_mini', 'digallery');
				$this->data['js'] = 'add_edit_gallery.js';
				
				$this->render();
			}
		}
		else
		{
			redirect('user/login', 'refresh');
		}
	}
	
	public function index($user_id = 0)
	{
		$this->load->model('browse_model');
		$this->load->model('comments_model');
		$this->load->library('typography');
		$this->load->helper(array('urllinker', 'urlslug'));
		
		$user = $this->browse_model->get_user(intval($user_id));
		
		if ($user === FALSE || !$user->active)
		{
			show_error("Użytkownik nie istnieje...", 404, 'Błąd!');
		}		

		if (($current_page = $this->current_page(4)) === FALSE)
		{
			show_error("Strona nie występuje...", 404, 'Błąd!');
		}		
		
		$this->data['adult_user'] = $this->adult_user;
		
		$all_profile_comments = $this->comments_model->counts_profile_comments($user_id);

		if ($all_profile_comments > 0)
		{
			$last_page = ceil($all_profile_comments / $this->profile_comments_config['page_size']);

			if ($current_page > $last_page)
			{
				$current_page = $last_page;
			}

			$profile_comments = $this->comments_model->get_profile_comments($user_id, $this->profile_comments_config['page_size'], $current_page);

			foreach ($profile_comments as &$profile_comment)
			{
				$profile_comment->comment = $this->typography->auto_typography(htmlEscapeAndLinkUrls($profile_comment->comment), TRUE);
				$profile_comment->signature = $this->typography->auto_typography(htmlEscapeAndLinkUrls($profile_comment->signature), TRUE);
			}

			$this->data['object_comments'] = $profile_comments;
			$this->data['pagination_links'] = $this->pagination_links("/profile/{$user_id}/", $this->profile_comments_config['page_size'], $all_profile_comments, 3);
		}
		else
		{
			$this->data['object_comments'] = array();
		}

		$this->data['can_comment'] = $user->profile_can_comment;
		$this->data['comment_object_owner'] = $user_id;
		$this->data['can_evaluate'] = FALSE;
		
		$this->data['thumbs_small_gallery'] = $this->browse_model->get_thumb_galleries(0, 0, 'dd', $user_id, 1, 2);
		
		foreach ($this->data['thumbs_small_gallery'] as &$thumb)
		{
			$thumb['gallery_thumb_images'] = $this->browse_model->get_imgs_filename_to_gallery_thumb($thumb['id'], 5);	
		}		
		
		$picture_properties = array(
			'src' => $user->picture ? base_url() . $this->picture_path . $user->picture : base_url() . $this->picture_path . $this->default_picture,
			'alt' => 'Zdjęcie',
			'id' => 'picture-profile',
			'title' => 'Zdjęcie',
		);

		$this->data['picture_properties'] = $picture_properties;		
		
		$this->data['avatars_config'] = $this->config->item('avatar', 'digallery');
		
		$this->data['thumb_mini_config'] = $this->config->item('thumb_mini', 'digallery');
		
		$this->data['thumbs_mini'] = $this->browse_model->get_thumb_images(0, 0, 'dd', $user_id, 1, 8);
		
		$this->data['user'] = $user;
		
		if ($this->ion_auth->logged_in())
		{
			$this->data['logged_in_user'] = $this->ion_auth->user()->row();
		}		
		
		$this->data['profile_signature'] = $this->typography->auto_typography(htmlEscapeAndLinkUrls($user->signature_profile), TRUE);
		
		$this->data['js'][] = 'comments.js';
		$this->data['js'][] = 'profile.js';
		
		$this->render();
	}
	
	public function _alpha_dash_polish($str)
	{
		if ($str === NULL || preg_match("/^[\p{L}\- ]+$/iu", $str))
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('_alpha_dash_polish', '...tylko znaki literowe i myślnik ...');
			return FALSE;
		}
	}

	public function get_categories_ajax()
	{
		if ($this->input->is_ajax_request())
		{
			/*
			$parse_str = '';

			foreach ($this->browse_model->get_images_categories() as $row)
			{
				$parse_str .= $row->id . '/' . $row->name_cat . '/' . ($row->parent_cat_id === NULL ? 'null' : $row->parent_cat_id) . '|';
			}
			echo substr($parse_str, 0, strlen($parse_str) - 1);
			 * 
			 */
			
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode($this->browse_model->get_images_categories()));			
		}
	}

	public function _category_exist($id_category)
	{
		if ($this->browse_model->category_exist($id_category, 'images'))
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('_category_exist', 'Kategoria nie występuje...');
			return FALSE;
		}
	}

	private function generate_thumb($src_img, $new_img, $file_name, $file_type, $thumb_width, $thumb_height, $img_width, $img_height)
	{
		$result = TRUE;

		if ($img_width > $thumb_width || $img_height > $thumb_height)
		{
			switch ($file_type)
			{
				case 'image/jpeg':
					$org_img = @imagecreatefromjpeg($src_img);
					break;
				case 'image/png':
					$org_img = @imagecreatefrompng($src_img);
					break;
				case 'image/gif':
					$org_img = @imagecreatefromgif($src_img);
					break;
			}

			if (!$org_img)
			{
				return FALSE;
			}

			$ratio_width = $thumb_width / $img_width;
			$ratio_height = $thumb_height / $img_height;

			$ratio = ($ratio_width <= $ratio_height ? $ratio_width : $ratio_height);

			$scaled_width = round($img_width * $ratio);
			$scaled_height = round($img_height * $ratio);

			if (!($scaled_img = @imagecreatetruecolor($scaled_width, $scaled_height)))
			{
				return FALSE;
			}

			if (!imagecopyresampled($scaled_img, $org_img, 0, 0, 0, 0, $scaled_width, $scaled_height, $img_width, $img_height))
			{
				return FALSE;
			}

			switch ($file_type)
			{
				case 'image/jpeg':
					$result = @imagejpeg($scaled_img, $new_img . $file_name);
					break;
				case 'image/png':
					$result = @imagepng($scaled_img, $new_img . $file_name);
					break;
				case 'image/gif':
					$result = @imagegif($scaled_img, $new_img . $file_name);
					break;
			}
		}
		else
		{
			if (!copy($src_img, $new_img.$file_name))
			{
				$result = FALSE;
			}
		}

		return $result;
	}

	private function get_image_user_tags($image_id)
	{
		$user_tags = '';
		
		foreach ($this->browse_model->get_object_user_tags($image_id, 'image') as $user_tag)
		{
			$user_tags .= $user_tag['tag'] . ' ';
		}
		
		return $user_tags;
	}

	private function get_gallery_user_tags($gallery_id)
	{
		$user_tags = '';
		
		foreach ($this->browse_model->get_object_user_tags($gallery_id, 'gallery') as $user_tag)
		{
			$user_tags .= $user_tag['tag'] . ' ';
		}
		
		return $user_tags;
	}
	
	public function submit()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->load->model('browse_model');
			$this->load->helper('browse');

			//Pobierz dane zalogowanego użytkownika
			$user = $this->ion_auth->user()->row();
			
			if (!is_null($this->uri->segment(3)))
			{
				$uri_image_id = intval($this->uri->segment(3));
				$image = $this->browse_model->get_image($uri_image_id);

				//#todo
				//  ...można by pokusić się o dokładną informację na temat błedu oraz
				// przekierowanie na stronę główną...
				if ($image === FALSE || $user->id !== $image->user_id)
				{
					// praca nie wystepuje w bazie lub nie należy do zalogowanego użytkownika...
					show_404();
				}
			}

			$this->form_validation->set_rules('title', 'Tytuł', 'required');
			$this->form_validation->set_rules('category_id', 'Kategoria', 'required|is_natural_no_zero|callback__category_exist');
			
			$this->form_validation->set_rules('statement', 'Oświadczenie', 'required');
			
			if ($this->form_validation->run() == TRUE)
			{
				//Walidacja OK!

				$user_tags = split_tags(strtolower($this->input->post('tags')));
				$tags = split_tags(strtolower($this->input->post('title') . ' ' . $this->input->post('description')));
								
				$user_data = array(
					'user_id' => $user->id,
					'title' => $this->input->post('title'),
					'category_id' => $this->input->post('category_id'),
					'description' => $this->input->post('description'),
					'can_comment' => (bool) $this->input->post('allow_comments') ? 1 : 0,
					'can_evaluated' => (bool) $this->input->post('allow_evaluated') ? 1 : 0,
					'plus_18' => (bool) $this->input->post('mature') ? 1 : 0,
					'statement' => 1,
				);
				
				if (isset($image))
				{
					$this->db->trans_begin();
					
					$this->db->update('images', $user_data, array('id' => $image->id));
					
					if (!$this->browse_model->associate_tags_object($image->id, 'image', array_unique(array_merge($user_tags, $tags)), $user_tags) || $this->db->trans_status() === FALSE)
					{
						$this->db->trans_rollback();
						$this->session->set_flashdata(array('type' => 'error', 'msg' => 'Niestety nie udało się zaktualizować danych pracy...'));				
						redirect("profile/submit/{$image->id}", 'refresh');					
					}
					else
					{
						$this->db->trans_commit();
						$this->session->set_flashdata(array('type' => 'info', 'msg' => 'Dane pracy zostały zaktualizowane...'));
						redirect("profile/submit/{$image->id}", 'refresh');		
					}									
				}
				else
				{
					// Dodawanie nowej pracy
					
					$this->db->trans_begin();

					$this->db->insert('images', $user_data);
					$id = $this->db->insert_id();

					$file_name = (string) $id;

					$upload_config['file_name'] = $file_name;
					$upload_config['upload_path'] = './' . $this->uploads_path;
					$upload_config['allowed_types'] = $this->uploads_file_type;
					$upload_config['max_size'] = $this->uploads_file_max_size;
					$upload_config['max_filename'] = $this->uploads_file_max_filename;

					$this->load->library('upload', $upload_config);

					if (!$this->upload->do_upload('file'))
					{
						// Bład przy wczytywaniu pliku...

						$this->db->trans_rollback();

						$this->session->set_flashdata(array('type' => 'error', 'msg' => $this->upload->display_errors('', '')));
						redirect('profile/submit/', 'refresh');
					}
					
					// Pobierz dane wczytanego pliku
					$uploaded_file_data = $this->upload->data();
										
					// Przygotowanie danych do bazy
					$image_data = array(
						'file_name' => $uploaded_file_data['file_name'],
						'file_type' => $uploaded_file_data['file_type'],
						'orig_name' => $uploaded_file_data['orig_name'],
						'client_name' => $uploaded_file_data['client_name'],
						'file_ext' => $uploaded_file_data['file_ext'],
						'file_size' => $uploaded_file_data['file_size'],
						'is_image' => $uploaded_file_data['is_image'],
						'image_width' => $uploaded_file_data['image_width'],
						'image_height' => $uploaded_file_data['image_height'],
						'image_type' => $uploaded_file_data['image_type'],
					);	 

					// Zapis danych do bazy
					$this->db->update('images', $image_data, array('id' => $id));

					// Generowanie miniatur
					$this->load->library('image_lib');

					// ...miniatura 108x100px
					if (!$this->generate_thumb($uploaded_file_data['full_path'], $this->thumbs2col_path, $uploaded_file_data['file_name'],
							$uploaded_file_data['file_type'], $this->browse_thumb_width, $this->browse_thumb_height, $uploaded_file_data['image_width'],
							$uploaded_file_data['image_height']))
					{
						$this->db->trans_rollback();

						unlink($uploaded_file_data['full_path']);

						//$this->session->set_flashdata(array('type' => 'error', 'msg' => $this->image_lib->display_errors('', '<br />')));
						$this->session->set_flashdata(array('type' => 'error', 'msg' => 'Cos z thumbsami')); //$this->thumbs_error)); @todo
						redirect('profile/submit/', 'refresh');
					}

					// ...miniatura 45x56px
					if (!$this->generate_thumb($uploaded_file_data['full_path'], $this->thumbs1col_path, $uploaded_file_data['file_name'],
							$uploaded_file_data['file_type'], $this->mini_thumb_width, $this->mini_thumb_height, $uploaded_file_data['image_width'],
							$uploaded_file_data['image_height']))
					{
						$this->db->trans_rollback();

						unlink($uploaded_file_data['full_path']);
						unlink($this->thumbs2col_path . $uploaded_file_data['file_name']);

						$this->session->set_flashdata(array('type' => 'error', 'msg' => $this->image_lib->display_errors('', '<br />')));
						redirect('profile/submit/', 'refresh');
					}

					// ...miniatura 675x550px
					if (!$this->generate_thumb($uploaded_file_data['full_path'], $this->thumbs11col_path, $uploaded_file_data['file_name'],
							$uploaded_file_data['file_type'], $this->preview_thumb_width, $this->preview_thumb_height, $uploaded_file_data['image_width'],
							$uploaded_file_data['image_height']))
					{
						$this->db->trans_rollback();

						unlink($uploaded_file_data['full_path']);
						unlink($this->thumbs2col_path . $uploaded_file_data['file_name']);
						unlink($this->thumbs1col_path . $uploaded_file_data['file_name']);

						$this->session->set_flashdata(array('type' => 'error', 'msg' => $this->image_lib->display_errors('', '<br />')));
						redirect('profile/submit/', 'refresh');
					}
					
					if (!$this->browse_model->associate_tags_object($id, 'image', array_unique(array_merge($user_tags, $tags)), $user_tags) || $this->db->trans_status() === FALSE)
					{
						unlink($uploaded_file_data['full_path']);
						unlink($this->thumbs1col_path . $uploaded_file_data['file_name']);
						unlink($this->thumbs2col_path . $uploaded_file_data['file_name']);
						unlink($this->thumbs11col_path . $uploaded_file_data['file_name']);

						$this->db->trans_rollback();
						
						$this->session->set_flashdata(array('type' => 'error', 'msg' => 'Niestety nie udało się dodać pracy...'));
						redirect('profile/submit/', 'refresh');
					}
					else
					{
						$this->db->trans_commit();
						$this->session->set_flashdata(array('type' => 'info', 'msg' => 'Praca została dodana...'));
						redirect('profile/submit/', 'refresh');
					}					
				}
			}
			else
			{									
				$this->data['message'] = array(
					'type' => $this->session->flashdata('type'),
					'msg' => $this->session->flashdata('msg'),
				);

				$this->data['form_attr'] = array(
					'id' => isset($image) ? 'profile-edit-image' : 'profile-upload-image',
					'class' => 'form-horizontal',
				);

				$this->data['title'] = array(
					'name' => 'title',
					'id' => 'title',
					'class' => 'span5',
					'type' => 'text',
					'value' => $this->form_validation->set_value('title', isset($image) ? $image->title : ''),
				);

				$this->data['title_label'] = array(
					'for' => 'title',
					'text' => 'Tytuł',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['categories_label'] = array(
					'for' => 'categories',
					'text' => 'Kategoria',
					'attributes' => array(
						'class' => 'control-label',
					),
				);
				
				if (!$this->input->post('category_id')) 
				{
					$this->data['category_id'] = array(
						'category_id' => isset($image) ? $image->category_id : ''
					);
				}
				else
				{
					if (form_error('category_id'))
					{
						$this->data['category_id'] = array(
							'category_id' => ''
						);
					}
					else
					{
						$this->data['category_id'] = array(
							'category_id' => $this->form_validation->set_value('category_id')
						);
					}
				}
				
				$cat_id = $this->data['category_id']['category_id'];
				
				if ($cat_id !== '')
				{
					$path = array();
					foreach ($this->browse_model->build_path_cats($cat_id, 'images', TRUE) as $level)
					{
						$path[] = $level['name_cat'];
					}
					
					$this->data['category_path'] = implode(' > ', $path);
				}
				else
				{
					$this->data['category_path'] = 'Wybierz kategorię...';
				}
				
				$this->data['description'] = array(
					'name' => 'description',
					'id' => 'description',
					'class' => 'span5',
					'rows' => '3',
					'value' => $this->form_validation->set_value('description', isset($image) ? $image->description : ''),
				);

				$this->data['description_label'] = array(
					'for' => 'description',
					'text' => 'Opis',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['file'] = array(
					'name' => 'file',
					'id' => 'file',
					'class' => 'input-file',
				);

				$this->data['file_label'] = array(
					'for' => 'file',
					'text' => 'Plik',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['tags'] = array(
					'name' => 'tags',
					'id' => 'tags',
					'class' => 'span5',
					'value' => $this->form_validation->set_value('tags', isset($image) ? $this->get_image_user_tags($image->id, 'image') : ''),
				);

				$this->data['tags_label'] = array(
					'for' => 'tags',
					'text' => 'Słowa kluczowe',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['allow_comments'] = array(
					'name' => 'allow_comments',
					'id' => 'allow_comments',
					'class' => 'checkbox',
					'value' => '1',
					'checked' => (bool) set_checkbox('allow_comments', '1', isset($image) ? (bool) $image->can_comment : ''),
				);

				$this->data['allow_comm_label'] = array(
					'for' => 'allow_comments',
					'text' => 'Komentarze',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['allow_evaluated'] = array(
					'name' => 'allow_evaluated',
					'id' => 'allow_evaluated',
					'class' => 'checkbox',
					'value' => '1',
					'checked' => (bool) set_checkbox('allow_evaluated', '1', isset($image) ? (bool) $image->can_evaluated : ''),
				);

				$this->data['allow_eval_label'] = array(
					'for' => 'allow_evaluated',
					'text' => 'Ocenianie',
					'attributes' => array(
						'class' => 'control-label',
					),
				);				
				
				$this->data['mature'] = array(
					'name' => 'mature',
					'id' => 'mature',
					'class' => 'checkbox',
					'value' => '1',
					'checked' => (bool) set_checkbox('mature', '1', isset($image) ? (bool) $image->plus_18 : ''),
				);

				$this->data['mature_label'] = array(
					'for' => 'mature',
					'text' => '18+',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['statement'] = array(
					'name' => 'statement',
					'id' => 'statement',
					'class' => 'checkbox',
					'value' => '1',
					'checked' => (bool) set_checkbox('statement', '1', isset($image) ? (bool) $image->statement : ''),
				);

				$this->data['statement_label'] = array(
					'for' => 'statement',
					'text' => 'Oświadczenie',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['control_groups'] = array(
					'title' => form_error('title') ? ' error' : '',
					'categories' => form_error('category_id') ? ' error' : '',
					'file' => form_error('file') ? ' error' : '',
					'statement' => form_error('statement') ? ' error' : '',
					//'tags' => form_error('tags[0]') || form_error('tags[1]') || form_error('tags[2]') ? ' error' : '',
				);

				if (isset($image))
				{
					$this->data['image'] = $image;
				}

				// wybór kategorii i walidacja formularza
				$this->data['js'][] = 'widget.js';
				$this->data['js'][] = 'submit.js';

				$this->render();

				//$cats_uri_rows = $this->browse_model->get_cats_uri_rows($this->browse_model->build_path_cats($image->category_id));
				//$cats_path = $this->browse_model->create_hierarchical_path(base_url() . 'browse/', $cats_uri_rows);
			}
		}
		else
		{
			redirect('user/login', 'refresh');
		}
	}

	public function edit()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_error_delimiters('', '');

			$this->form_validation->set_rules('first_name', 'Imię', 'callback__alpha_dash_polish|xss_clean');
			$this->form_validation->set_rules('last_name', 'Nazwisko', 'callback__alpha_dash_polish|xss_clean');
			$this->form_validation->set_rules('city', 'Miejsce zamieszkania', 'callback__alpha_dash_polish|xss_clean');
			//$this->form_validation->set_rules('sex', 'Płeć', 'required');

			//Pobierz dane zalogowanego użytkownika
			$user = $this->ion_auth->user()->row();

			$this->load->library('upload');
			$this->lang->load('upload');

			$data = array();

			if ($this->form_validation->run() == TRUE)
			{
				/*
				 *  AVATAR FILE
				 */

				$avatar_config['upload_path'] = './' . $this->avatars_path;
				$avatar_config['allowed_types'] = $this->avatars_file_type;
				$avatar_config['max_size'] = $this->avatar_max_size;
				$avatar_config['max_width'] = $this->avatar_max_width;
				$avatar_config['max_height'] = $this->avatar_max_height;
				$avatar_config['encrypt_name'] = TRUE;

				$this->upload->initialize($avatar_config);

				if ((bool)$this->input->post('delete_avatar'))
				{
					//Usun plik z dysku

					if (! is_null($user->avatar))
					{
						if (! @unlink('./' . $this->avatars_path . $user->avatar))
						{
							//problemy z usunięciem avatara
							$this->session->set_flashdata(array('type' => 'error', 'msg' => 'Niestety nie udało się usunąć pliku z avatarem'));
							redirect('profile/edit/', 'refresh');
						}
					}

					//aktualizacja danych po usunięciu avatara
					$data['avatar'] = NULL;
				}
				elseif (!$this->upload->do_upload('avatar_file'))
				{
					//Błąd przy uploadzie pliku

					$msg = 'upload_no_file_selected';

					//jeśli błąd spowodowany został czyms innym niż nie wybranie pliku
					if (!($this->upload->error_msg[0] == ($this->lang->line($msg) ? $this->lang->line($msg) : $msg)))
					{
						//Jakis inny blad... - aktualizacja nie moze zostac wykonana
						$this->session->set_flashdata(array('type' => 'error', 'msg' => $this->upload->display_errors('','')));
						redirect('profile/edit/', 'refresh');
					}
				}
				else
				{
					//Upload OK!

					$avatar_upload_data = $this->upload->data();

					$data['avatar'] = $avatar_upload_data['file_name']; //funkcja generowania sciezki dla avatarow
				}

				/*
				 *  PICTURE FILE
				 */

				$picture_config['upload_path'] = './' . $this->picture_path;
				$picture_config['allowed_types'] = $this->pictures_file_type;
				$picture_config['max_size'] = $this->picture_max_size;
				$picture_config['max_width'] = $this->picture_max_width;
				$picture_config['max_height'] = $this->picture_max_height;
				$picture_config['encrypt_name'] = TRUE;

				$this->upload->initialize($picture_config);

				if ((bool)$this->input->post('delete_picture'))
				{
					//Usun plik z dysku

					if (! is_null($user->picture))
					{
						if (! @unlink('./' . $this->picture_path . $user->picture))
						{
							//problemy z usunięciem zdjęcia
							$this->session->set_flashdata(array('type' => 'error', 'msg' => 'Niestety nie udało się usunąć pliku ze zdjęciem'));
							redirect('profile/edit/', 'refresh');
						}
					}

					//aktualizacja danych po usunięciu zdjęcia
					$data['picture'] = NULL;
				}
				elseif (!$this->upload->do_upload('picture_file'))
				{
					//Błąd przy uploadzie pliku

					$msg = 'upload_no_file_selected';
					//jeśli błąd spowodowany został czyms innym niż nie wybranie pliku
					if (!($this->upload->error_msg[0] == ($this->lang->line($msg) ? $this->lang->line($msg) : $msg)))
					{
						//Jakis inny blad... - aktualizacja nie moze zostac wykonana
						$this->session->set_flashdata(array('type' => 'error', 'msg' => $this->upload->display_errors('','')));
						redirect('profile/edit/', 'refresh');
					}
				}
				else
				{
					//Upload OK!

					$picture_upload_data = $this->upload->data();

					$data['picture'] = $picture_upload_data['file_name']; //funkcja generowania sciezki dla zdjęć
				}

				/*
				 * Update database
				 */

				$data['first_name'] = $this->input->post('first_name');
				$data['last_name'] = $this->input->post('last_name');
				$data['city'] = $this->input->post('city');
				
				// płeć
				$sex = $this->input->post('sex');
				if ($sex && ($sex === 'k' || $sex === 'm'))
				{
					$data['sex'] = $sex;
				}
				else
				{
					$data['sex'] = NULL;
				}
				
				$data['signature_profile'] = $this->input->post('signature_profile');
				$data['signature'] = $this->input->post('signature');
				
				//zapis do bazy
				if ($this->ion_auth->update($user->id, $data))
				{
					if (isset($data['avatar']) && !is_null($user->avatar))
					{
						@unlink('./' . $this->avatars_path . $user->avatar); //usuń stary avatar
					}

					if (isset($data['picture']) && !is_null($user->picture))
					{
						@unlink('./' . $this->picture_path . $user->picture); //usuń stare zdjęcie
					}

					$this->session->set_flashdata(array('type' => 'info', 'msg' => $this->ion_auth->messages()));
					redirect('profile/edit/', 'refresh');
				}
				else
				{
					unlink($avatar_upload_data['full_path']); //usuń wczytanego avatara
					unlink($picture_upload_data['full_path']); //usuń wczytane zdjęcie

					$this->session->set_flashdata(array('type' => 'error', 'msg' => $this->ion_auth->errors()));
					redirect('profile/edit/', 'refresh');
				}
			}
			else
			{
				$this->data['message'] = array(
					'type' => $this->session->flashdata('type'),
					'msg' => $this->session->flashdata('msg'),
				);

				$this->data['form_attr'] = array(
					'id' => 'profile-edit',
					'class' => 'form-horizontal',
				);

				$this->data['first_name'] = array(
					'name' => 'first_name',
					'id' => 'first_name',
					'class' => 'span3',
					'type' => 'text',
					'value' => $this->form_validation->set_value('first_name', $user->first_name),
				);

				$this->data['first_name_label'] = array(
					'for' => 'first_name',
					'text' => 'Imię',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['last_name'] = array(
					'name' => 'last_name',
					'id' => 'last_name',
					'class' => 'span3',
					'type' => 'text',
					'value' => $this->form_validation->set_value('last_name', $user->last_name),
				);

				$this->data['last_name_label'] = array(
					'for' => 'last_name',
					'text' => 'Nazwisko',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['city'] = array(
					'name' => 'city',
					'id' => 'city',
					'class' => 'span3',
					'type' => 'text',
					'value' => $this->form_validation->set_value('city', $user->city),
				);

				$this->data['city_label'] = array(
					'for' => 'city',
					'text' => 'Miejsce zamieszkania',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['sex_man'] = array(
					'name' => 'sex',
					'id' => 'sex_man',
					'value' => 'm',
					'checked' => (bool) $this->form_validation->set_radio('sex', 'm', $user->sex == 'm' ? TRUE : FALSE),
				);

				$this->data['sex_woman'] = array(
					'name' => 'sex',
					'id' => 'sex_woman',
					'value' => 'k',
					'checked' => (bool) $this->form_validation->set_radio('sex', 'k', $user->sex == 'k' ? TRUE : FALSE),
				);

				$this->data['sex_label'] = array(
					'for' => 'sex',
					'text' => 'Płeć',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['avatar_file'] = array(
					'name' => 'avatar_file',
					'id' => 'avatar_file',
					'class' => 'input-file',
				);

				$this->data['avatar_file_label'] = array(
					'for' => 'avatar_file',
					'text' => 'Avatar',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['delete_avatar'] = array(
					'name' => 'delete_avatar',
					'id' => 'delete_avatar',
					'class' => 'checkbox',
					'value' => '1',
				);

				$avatar_properties = array(
					'src' => $user->avatar ? base_url() . $this->avatars_path . $user->avatar : base_url() . $this->avatars_path . $this->default_avatar,
					'alt' => 'Avatar',
					'id' => 'avatar-profile-edit',
					//'width' => '50',
					//'height' => '50',
					'title' => 'Avatar',
				);

				$this->data['avatar_properties'] = $avatar_properties;

				$this->data['picture_file'] = array(
					'name' => 'picture_file',
					'id' => 'picture-profile-edit',
					'class' => 'input-file',
				);

				$this->data['picture_file_label'] = array(
					'for' => 'picture_file',
					'text' => 'Zdjęcie',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['delete_picture'] = array(
					'name' => 'delete_picture',
					'id' => 'delete_picture',
					'class' => 'checkbox',
					'value' => '1',
				);

				$picture_properties = array(
					'src' => $user->picture ? base_url() . $this->picture_path . $user->picture : base_url() . $this->picture_path . $this->default_picture,
					'alt' => 'Zdjęcie',
					'id' => 'picture-profile-edit',
					//'width' => '150',
					//'height' => '150',
					'title' => 'Zdjęcie',
				);

				$this->data['picture_properties'] = $picture_properties;

				$this->data['signature'] = array(
					'name' => 'signature',
					'id' => 'signature',
					'class' => 'span7',
					'rows' => '6',
					'value' => $this->form_validation->set_value('signature', $user->signature),
				);
				
				$this->data['signature_label'] = array(
					'for' => 'signature',
					'text' => 'Sygnaturka',
					'attributes' => array(
						'class' => 'control-label',
					),
				);	
				
				$this->data['signature_profile'] = array(
					'name' => 'signature_profile',
					'id' => 'signature_profile',
					'class' => 'span7',
					'rows' => '6',
					'value' => $this->form_validation->set_value('signature_profile', $user->signature_profile),
				);
				
				$this->data['signature_profile_label'] = array(
					'for' => 'signature_profile',
					'text' => 'Strona główna profilu',
					'attributes' => array(
						'class' => 'control-label',
					),
				);				
				
				$this->data['control_groups'] = array(
					'first_name' => form_error('first_name') ? ' error' : '',
					'last_name' => form_error('last_name') ? ' error' : '',
					'city' => form_error('city') ? ' error' : '',
					'sex' => form_error('sex') ? ' error' : '',
				);

				$this->render();
			}
		}
		else
		{
			redirect('user/login', 'refresh');
		}
	}
	
	public function images($user_id = 0)
	{
		$this->load->helper(array('browse', 'urlslug'));
		$this->load->model('browse_model');			
		
		$user = $this->browse_model->get_user(intval($user_id));
		
		if ($user === FALSE || !$user->active)
		{
			show_error("Użytkownik nie istnieje...", 404, 'Błąd!');
		}
		
		$current_page = is_null($this->input->get('page')) ? 1 : intval($this->input->get('page'));

		$filter = get_filter_param($this->input->get('filter'));
		$sort = get_sort_param($this->input->get('sort'));
		
		if ($current_page < 1)
		{
			show_404();
		}			

		$all_images = $this->browse_model->get_count_thumb_images(0, $filter, $sort, $user_id);
			
		$page_size = 21;
			
		if ($all_images > 0)
		{
			$max_pages = ceil($all_images / $page_size);

			if ($current_page > $max_pages)
			{
				show_404();
			}
			
			$current_page + 1 > $max_pages ? $this->data['next'] = FALSE : $this->data['next'] = TRUE;
			$current_page - 1 == 0 ? $this->data['preview'] = FALSE : $this->data['preview'] = TRUE;
				
			$this->data['thumbs_small'] = $this->browse_model->get_thumb_images(0, $filter, $sort, $user_id, $current_page, $page_size);
		}
		else
		{
			if ($current_page > 1)
			{
				show_404();
			}
			
			$this->data['next'] = FALSE;
			$this->data['preview'] = FALSE;

			$this->data['thumbs_small'] = array();
		}			

		$this->data['user'] = $user;
		
		if ($this->ion_auth->logged_in())
		{
			$this->data['logged_in_user'] = $this->ion_auth->user()->row();			
		}		
			
		$this->data['current_page'] = $current_page;

		$this->data['get_uri'] = create_get_params_uri($filter, $sort);

		$browse_config = $this->config->item('browse', 'digallery');

		$this->data['nav_list_filter'] = $browse_config['filter'];
		$this->data['nav_list_sort'] = $browse_config['sort'];

		$this->data['filter'] = $filter;
		$this->data['sort'] = $sort;

		$this->data['thumb_small_config'] = $this->config->item('thumb_small', 'digallery');
		
		$this->data['adult_user'] = $this->adult_user;

		$this->data['message'] = array('type' => $this->session->flashdata('type'), 'msg' => $this->session->flashdata('msg'));

		$this->render();
	}
	
	public function galleries($user_id = 0)
	{
		$this->load->helper(array('browse', 'urlslug'));
		$this->load->model('browse_model');			
		
		$user = $this->browse_model->get_user(intval($user_id));
		
		if ($user === FALSE || !$user->active)
		{
			show_error("Użytkownik nie istnieje...", 404, 'Błąd!');
		}
		
		$current_page = is_null($this->input->get('page')) ? 1 : intval($this->input->get('page'));

		$filter = get_filter_param($this->input->get('filter'));
		$sort = get_sort_param($this->input->get('sort'));
		
		if ($current_page < 1)
		{
			show_404();
		}			

		$all_galleries = $this->browse_model->get_count_thumb_galleries(0, $filter, $sort, $user_id);
		
		$page_size = 21;
		
		if ($all_galleries > 0)
		{
			$max_pages = ceil($all_galleries / $page_size);

			if ($current_page > $max_pages)
			{
				show_404();
			}

			$current_page + 1 > $max_pages ? $this->data['next'] = FALSE : $this->data['next'] = TRUE;
			$current_page - 1 == 0 ? $this->data['preview'] = FALSE : $this->data['preview'] = TRUE;
			$this->data['thumbs_small_gallery'] = $this->browse_model->get_thumb_galleries(0, $filter, $sort, $user_id, $current_page, $page_size);
			
			foreach ($this->data['thumbs_small_gallery'] as &$thumb)
			{
				$thumb['gallery_thumb_images'] = $this->browse_model->get_imgs_filename_to_gallery_thumb($thumb['id'], 5);
			}
		}
		else
		{
			if ($current_page > 1)
			{
				show_404();
			}

			$this->data['next'] = FALSE;
			$this->data['preview'] = FALSE;

			$this->data['thumbs_small_gallery'] = array();
		}			
		
		$this->data['user'] = $user;
		
		if ($this->ion_auth->logged_in())
		{
			$this->data['logged_in_user'] = $this->ion_auth->user()->row();			
		}		
			
		$this->data['current_page'] = $current_page;

		$this->data['get_uri'] = create_get_params_uri($filter, $sort);

		$browse_config = $this->config->item('browse', 'digallery');

		$this->data['nav_list_filter'] = $browse_config['filter'];
		$this->data['nav_list_sort'] = $browse_config['sort'];

		$this->data['filter'] = $filter;
		$this->data['sort'] = $sort;

		//$this->data['thumb_small_config'] = $this->config->item('thumb_small', 'digallery');
		$this->data['thumb_mini_config'] = $this->config->item('thumb_mini', 'digallery');
		
		$this->data['adult_user'] = $this->adult_user;

		$this->data['message'] = array('type' => $this->session->flashdata('type'), 'msg' => $this->session->flashdata('msg'));

		$this->render();		
	}
	
	public function comments($user_id = 0, $comments_type = 'all', $current_page = 1)
	{	
		$this->load->helper('browse');
		$this->load->library('typography');
		$this->load->helper('urllinker');
		$this->load->model('comments_model');			

		$user = $this->browse_model->get_user(intval($user_id));
		
		if ($user === FALSE || !$user->active)
		{
			show_error("Użytkownik nie istnieje...", 404, 'Błąd!');
		}
		
		if ($comments_type == 'all') 
		{
			$page_segment = 4;
		}
		else
		{
			$page_segment = 5;
		}
		
		if ($current_page == 0) 
		{
			$current_page = 1;
		}
		
		$all_comments = $this->comments_model->counts_user_comments($user_id, $comments_type);

		if ($all_comments > 0)
		{
			$last_page = ceil($all_comments / $this->profile_comments_config['page_size']);

			if ($current_page > $last_page)
			{
				$current_page = $last_page;
			}

			$user_comments = $this->comments_model->get_user_comments($user_id, $current_page, $this->profile_comments_config['page_size'], $comments_type);

			foreach ($user_comments as &$user_comment)
			{
				$user_comment->comment = $this->typography->auto_typography(htmlEscapeAndLinkUrls($user_comment->comment), TRUE);
			}

			$this->data['object_comments'] = $user_comments;
			$this->data['pagination_links'] = $this->pagination_links("/profile/{$user_id}/comments/" . ($comments_type !== 'all' ? $comments_type .'/' : ''), 
					$this->profile_comments_config['page_size'], $all_comments, $page_segment);
		}
		else
		{
			$this->data['object_comments'] = array();
		}
		
		$this->data['user'] = $user;
		
		if ($this->ion_auth->logged_in())
		{
			$this->data['logged_in_user'] = $this->ion_auth->user()->row();			
		}			
		
		$this->data['avatars_config'] = $this->config->item('avatar', 'digallery');
		
		$this->render();
	}
	
}

/* End of file profile.php */
/* Location: ./application/controllers/profile.php */