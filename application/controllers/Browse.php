<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Browse extends MY_Controller
{
	private $get_uri = '';

	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		if ($this->ion_auth->logged_in())
		{
			//gdy zalogowany
			redirect($this->config->item('base_url') . 'browse/images/', 'refresh');	
		}		
		
		$this->load->model('browse_model');
		$this->load->helper(array('common', 'urlslug'));
		
		$this->data['thumbs_mini'] = $this->browse_model->get_thumb_images(0, 0, 'dd', 0, 1, 14);
		
		$this->data['thumbs_small_gallery'] = $this->browse_model->get_thumb_galleries(0, 0, 'dd', 0, 1, 7);
		
		foreach ($this->data['thumbs_small_gallery'] as &$thumb)
		{
			$thumb['gallery_thumb_images'] = $this->browse_model->get_imgs_filename_to_gallery_thumb($thumb['id'], 5);	
		}
		
		if ($this->ion_auth->logged_in())
		{
			$this->data['logged_in_user'] = $this->ion_auth->user()->row();
		}		
		
		$this->data['adult_user'] = $this->adult_user;
		$this->data['thumb_mini_config'] = $this->config->item('thumb_mini', 'digallery');
		
		$this->data['message'] = array('type' => $this->session->flashdata('type'), 'msg' => $this->session->flashdata('msg'));
		
		$this->data['csrf'] = get_csrf_nonce();
		
		$this->data['js'][] = 'jquery.placeholder.min.js';
		$this->data['js'][] = 'start_page.js';
		
		$this->data['no_container_class'] = TRUE;
		
		$this->render();
	}
	
	private function generate_nav_list($cats, $base_url, $path = array(), $generate_header = false)
	{
		$padding = 15;
		$padding_indent = 10;
		$font_weight = 'font-weight: bold;';
		$padding_left = 'padding-left: ';
		$nav_list = '<ul class="nav nav-list">';

		$nav_list .= '<li class="nav-header">Kategorie</li>';

		if ($generate_header)
		{
			$nav_list .= '<li>';
			$nav_list .= anchor($base_url . $this->get_uri, 'Wszystkie kategorie:', array('style' => $font_weight));
			$nav_list .= '</li>';

			$nav_list .= '<li class="divider"></li>';

			$padding += $padding_indent * 2;
		}

		if (!empty($path))
		{
			foreach ($path as $seg_path)
			{
				$nav_list .= '<li>';
				$nav_list .= anchor($seg_path['path'] . $this->get_uri, $seg_path['long_name_cat'], array('style' => $padding_left . $padding . 'px; ' . $font_weight));
				$nav_list .= '</li>';

				$padding += $padding_indent;
			}
			$nav_list .= '<li class="divider"></li>';
		}

		foreach ($cats as $cat)
		{
			$nav_list .= '<li>';
			$nav_list .= anchor($cat['path'] . $this->get_uri, $cat['long_name_cat'], array('style' => $padding_left . $padding . 'px;' . ($cat['current'] ? ' ' . $font_weight : '')));
			$nav_list .= '</li>';
		}

		return $nav_list .= '</ul>';
	}

	private function create_navigation_cats($cats_uri_rows, $base_url, $type)
	{
		if (count($this->uri->segment_array()) <= 2)
		{
			// URI .../browse/[images | galleries]/ - albo sama domena...

			$sub_cats = $this->browse_model->get_sub_categories(NULL, $type);

			$cats = create_hierarchical_sub_cats($base_url, $sub_cats); //base_url() . 'browse/images/'

			return $this->generate_nav_list($cats, $base_url);
		}
		else
		{
			if (!empty($cats_uri_rows))
			{
				// URI OK!

				$last_row = end($cats_uri_rows);
				$last_but_one_row = prev($cats_uri_rows);

				// czy ostatni segment URI jest rodzicem dla innych kategorii
				if ($this->browse_model->sub_cats_exist($last_row['id'], $type))
				{
					//podkategorie występują...
					//pobierz podkategorie
					$sub_cats = $this->browse_model->get_sub_categories($last_row['id'], $type);

					$path = create_hierarchical_path($base_url, $cats_uri_rows);
					$cats = create_hierarchical_sub_cats($base_url, $sub_cats, NULL, $cats_uri_rows);

					return $this->generate_nav_list($cats, $base_url, $path, true);
				}
				else
				{
					//podkategorie nie występują...

					if (count($this->uri->segment_array()) == 3) // .../browse/podkategoria/
					{
						//pobierz główne kategorie id rodzica == NULL
						$sub_cats = $this->browse_model->get_sub_categories(NULL, $type);

						$cats = create_hierarchical_sub_cats($base_url, $sub_cats, $last_row['id']);

						return $this->generate_nav_list($cats, $base_url, array(), true);
					}
					else
					{
						//pobierz podkategorie
						$sub_cats = $this->browse_model->get_sub_categories($last_but_one_row['id'], $type);
						array_pop($cats_uri_rows);

						$path = create_hierarchical_path($base_url, $cats_uri_rows);
						$cats = create_hierarchical_sub_cats($base_url, $sub_cats, $last_row['id'], $cats_uri_rows);

						return $this->generate_nav_list($cats, $base_url, $path, true);
					}
				}
			}
			else
			{
				//...nieprawidlowa ścieżka kategorii w URI;
				return array();
			}
		}
	}

	public function images()
	{		
		$this->load->helper(array('browse', 'urlslug'));
		$this->load->model('browse_model');

		$this->data['adult_user'] = $this->adult_user;
		
		$cats_uri_rows = $this->browse_model->get_cats_uri_rows(array_slice($this->uri->segment_array(), 2), 'images');
		
		$search_tags = get_search_tags($this->input->get('search'));

		$filter = get_filter_param($this->input->get('filter'));
		$sort = get_sort_param($this->input->get('sort'));
		$search = implode("+", $search_tags);

		$this->get_uri = create_get_params_uri($filter, $sort, $search);
				
		// menu nawigacyjne kategorii
		$this->data['navi_cats'] = $this->create_navigation_cats($cats_uri_rows, base_url() . 'browse/images/', 'images');

		if (empty($this->data['navi_cats']))
		{
			show_404();
		}

		if ($this->ion_auth->logged_in())
		{
			//gdy zalogowany
			$this->data['logged_in_user'] = $this->ion_auth->user()->row();
		}

		$page_size = 18;
		$current_page = is_null($this->input->get('page')) ? 1 : intval($this->input->get('page'));

		if ($current_page < 1)
		{
			show_404();
		}

		$count_uri_segs = count($this->uri->segment_array());

		if ($count_uri_segs <= 2)
		{
			$all_images = $this->browse_model->get_count_thumb_images(0, $filter, $sort, 0, $search_tags);
		}
		else
		{
			$last_row = end($cats_uri_rows);
			$all_images =  $this->browse_model->get_count_thumb_images($last_row['id'], $filter, $sort, 0, $search_tags);
		}

		if ($all_images > 0)
		{
			$max_pages = ceil($all_images / $page_size);

			if ($current_page > $max_pages)
			{
				show_404();
			}

			$current_page + 1 > $max_pages ? $this->data['next'] = FALSE : $this->data['next'] = TRUE;
			$current_page - 1 == 0 ? $this->data['preview'] = FALSE : $this->data['preview'] = TRUE;
			$this->data['thumbs_small'] = $this->browse_model->get_thumb_images(($count_uri_segs <= 2 ? 0 : $last_row['id']), $filter, $sort, 0, $current_page, $page_size, $search_tags);
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

		$this->data['current_page'] = $current_page;

		$this->data['get_uri'] = $this->get_uri;
		$this->data['get_uri_clear_search'] = create_get_params_uri($filter, $sort);

		$browse_config = $this->config->item('browse', 'digallery');
		
		$this->data['nav_list_filter'] = $browse_config['filter'];
		$this->data['nav_list_sort'] = $browse_config['sort'];

		$this->data['filter'] = $filter;
		$this->data['sort'] = $sort;
		$this->data['search'] = $search;
			
		$this->data['thumb_small_config'] = $this->config->item('thumb_small', 'digallery');

		$this->data['message'] = array('type' => $this->session->flashdata('type'), 'msg' => $this->session->flashdata('msg'));

		//formularz wyszukiwania
		$this->data['form_attr'] = array(
			'id' => 'search-form',
			'class' => 'form-search',
			'method' => 'get'
		);

		//dane pola wyszukiwania...
		$this->data['search_input'] = array(
			'name' => 'search',
			'id' => 'search',
			'class' => 'input-medium search-query',
			'type' => 'text',
			'value' => implode(" ", $search_tags),
		);
		
		$this->render();
	}
		
	public function galleries()
	{		
		$this->load->helper(array('browse', 'urlslug'));
		$this->load->model('browse_model');

		$cats_uri_rows = $this->browse_model->get_cats_uri_rows(array_slice($this->uri->segment_array(), 2), 'galleries');

		$search_tags = get_search_tags($this->input->get('search'));
		
		$filter = get_filter_param($this->input->get('filter'));
		$sort = get_sort_param($this->input->get('sort'));
		$search = implode("+", $search_tags);

		$this->get_uri = create_get_params_uri($filter, $sort, $search);

		// menu nawigacyjne kategorii
		$this->data['navi_cats'] = $this->create_navigation_cats($cats_uri_rows, base_url() . 'browse/galleries/', 'galleries');

		if (empty($this->data['navi_cats']))
		{
			show_404();
		}

		$this->data['adult_user'] = $this->adult_user;
		
		if ($this->ion_auth->logged_in())
		{
			//gdy zalogowany
			$this->data['logged_in_user'] = $this->ion_auth->user()->row();
		}

		$page_size = 18;
		$current_page = is_null($this->input->get('page')) ? 1 : intval($this->input->get('page'));

		if ($current_page < 1)
		{
			show_404();
		}

		$count_uri_segs = count($this->uri->segment_array());

		if ($count_uri_segs <= 2)
		{
			$all_galleries = $this->browse_model->get_count_thumb_galleries(0, $filter, $sort, 0, $search_tags);
		}
		else
		{
			$last_row = end($cats_uri_rows);
			$all_galleries =  $this->browse_model->get_count_thumb_galleries($last_row['id'], $filter, $sort, 0, $search_tags);
		}

		if ($all_galleries > 0)
		{
			$max_pages = ceil($all_galleries / $page_size);

			if ($current_page > $max_pages)
			{
				show_404();
			}

			$current_page + 1 > $max_pages ? $this->data['next'] = FALSE : $this->data['next'] = TRUE;
			$current_page - 1 == 0 ? $this->data['preview'] = FALSE : $this->data['preview'] = TRUE;
			$this->data['thumbs_small_gallery'] = $this->browse_model->get_thumb_galleries(($count_uri_segs <= 2 ? 0 : $last_row['id']), $filter, $sort, 0, $current_page, $page_size, $search_tags);
			
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

		$this->data['current_page'] = $current_page;

		$this->data['get_uri'] = $this->get_uri;
		$this->data['get_uri_clear_search'] = create_get_params_uri($filter, $sort);
		
		$browse_config = $this->config->item('browse', 'digallery');
		
		$this->data['nav_list_filter'] = $browse_config['filter'];
		$this->data['nav_list_sort'] = $browse_config['sort'];

		$this->data['filter'] = $filter;
		$this->data['sort'] = $sort;
		$this->data['search'] = $search;

		//$this->data['thumb_small_config'] = $this->config->item('thumb_small', 'digallery');
		$this->data['thumb_mini_config'] = $this->config->item('thumb_mini', 'digallery');

		$this->data['message'] = array('type' => $this->session->flashdata('type'), 'msg' => $this->session->flashdata('msg'));

		//formularz wyszukiwania
		$this->data['form_attr'] = array(
			'id' => 'search-form',
			'class' => 'form-search',
			'method' => 'get'
		);

		//dane pola wyszukiwania...
		$this->data['search_input'] = array(
			'name' => 'search',
			'id' => 'search',
			'class' => 'input-medium search-query',
			'type' => 'text',
			'value' => implode(" ", $search_tags),
		);		
		
		$this->render();
	}
}

/* End of file browse.php */
/* Location: ./application/controllers/browse.php */