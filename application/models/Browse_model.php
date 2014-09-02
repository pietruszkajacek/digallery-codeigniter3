<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Browse_model extends CI_Model
{
	public $filter;
	
	public $categories_tables = array(
		'images'	=> 'images_categories',
		'galleries' => 'galleries_categories',
	);
			
	public function __construct()
	{
		parent::__construct();
		
		$this->filter = $this->config->item('browse', 'digallery')['filter'];
	}

	public function get_images_categories()
	{
		$query = $this->db->get('images_categories');

		return $query->result();		
	}
	
	public function category_exist($id_category, $type)
	{
		$table_name = $this->categories_tables[$type];
		
		$this->db->where('id', $id_category);
		$query = $this->db->get($table_name);

		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}	
	
	public function get_lft_rgt_category($category_id, $type)
	{
		$table_name = $this->categories_tables[$type];
		
		if ($category_id === 0)
		{
			return array();
		}
		else
		{
			$this->db->select('lft, rgt');
			$this->db->from($table_name);
			$this->db->where('id', $category_id);

			$query = $this->db->get();

			if ($query->num_rows() > 0)
			{
				return $query->row_array();
			}
			else
			{
				return array();
			}
		}
	}

	public function get_count_thumb_images($category_id = 0, $filter = 0, $sort = 'dd', $user_id = 0, $search_tags = array())
	{
		return $this->get_images(TRUE, $category_id, $filter, $sort, $user_id, 0, 0, $search_tags);
	}

	public function get_thumb_images($category_id = 0, $filter = 0, $sort = 'dd', $user_id = 0, $page = 1, $page_size = 18, $search_tags = array())
	{
		return $this->get_images(FALSE, $category_id, $filter, $sort, $user_id, $page, $page_size, $search_tags);
	}

	public function get_users_all_images_ids($user_id)
	{
		$query = "SELECT id FROM images WHERE user_id = $user_id";
		
		return $this->db->query($query)->result_array();	
	}
	
	public function get_user_next_image_id_name($user_id, $cur_image_id)
	{
		$query = "SELECT id, title FROM images WHERE user_id = $user_id AND id > $cur_image_id ORDER BY id ASC LIMIT 1";
		
		$result = $this->db->query($query);
		
		return ($result->num_rows() > 0) ? $result->row() : NULL;
	}

	public function get_user_prev_image_id_name($user_id, $cur_image_id)
	{
		$query = "SELECT id, title FROM images WHERE user_id = $user_id AND id < $cur_image_id ORDER BY id DESC LIMIT 1";

		$result = $this->db->query($query);

		return ($result->num_rows() > 0) ? $result->row() : NULL;
	}
	
	private function get_images($just_countings = FALSE, $category_id = 0, $filter = 0, $sort = 'dd', $user_id = 0, $page = 1, $page_size = 18, $search_tags = array())
	{
		$lft_rgt = $this->get_lft_rgt_category($category_id, 'images');

		$sub_query = "SELECT imgs.id AS imgs_id, imgs.user_id AS owner, submitted, title, file_name, plus_18, name_cat, cats.lft, cats.rgt "
					."FROM images as imgs JOIN images_categories as cats ON cats.id = imgs.category_id WHERE hidden = 0";

		if(!empty($lft_rgt))
		{
			$sub_query .= " AND (cats.lft BETWEEN {$lft_rgt['lft']} AND {$lft_rgt['rgt']})";
		}

		if ($filter >= 10 && $filter <= 14)
		{
			$now = time();
			
			$from = $now - $this->filter[$filter]['sec'];

			$sub_query .= " AND (submitted BETWEEN '" . date('Y-m-d H:i:s', $from) . "' AND '" . date('Y-m-d H:i:s', $now) . "')";
		}

		if ($user_id != 0)
		{
			$sub_query .= " AND user_id = {$user_id}";
		}

		if (!empty($search_tags))
		{
			$sub_sub_query = $sub_query;

			$sub_query  = "SELECT * FROM (" . $sub_sub_query . ") AS filtered_imgs";

			$sub_query .= " WHERE EXISTS (SELECT * FROM images_tags AS imtg JOIN tags ON tags.id = imtg.tag_id WHERE filtered_imgs.imgs_id = image_id AND tag =";
			$sub_query .= $this->db->escape($search_tags[0]) . ')';

			for ($i = 1; $i < count($search_tags); $i++)
			{
				$sub_query .= " AND EXISTS (SELECT * FROM images_tags AS imtg JOIN tags ON tags.id = imtg.tag_id WHERE filtered_imgs.imgs_id = image_id AND tag =";
				$sub_query .= $this->db->escape($search_tags[$i]) . ')';
			}
		}

		if ($sort == 'dd')
		{
			$query  = "SELECT imgs_id, owner, submitted, title, file_name, plus_18, name_cat FROM (" . $sub_query . ") AS tagged_imgs";
			$query .= " ORDER BY submitted desc";
		}
		elseif ($sort == 'ul')
		{
			$query = "SELECT imgs_id, owner, fav_imgs.image_id, submitted, title, file_name, plus_18, name_cat, COUNT(*) AS counter FROM (" . $sub_query . ") AS tagged_imgs";
			$query .= " LEFT JOIN favorites AS fav_imgs ON tagged_imgs.imgs_id = fav_imgs.image_id";
			$query .= " GROUP BY tagged_imgs.imgs_id";
			$query .= " ORDER BY counter desc, fav_imgs.image_id desc, submitted desc";
		}
		elseif ($sort == 'oc')
		{
			$query = "SELECT imgs_id, owner, submitted, title, file_name, plus_18, name_cat, AVG(rate) AS average FROM (" . $sub_query . ") AS tagged_imgs";
			$query .= " LEFT JOIN images_evaluations AS rated_imgs ON tagged_imgs.imgs_id = rated_imgs.image_id";
			$query .= " GROUP BY tagged_imgs.imgs_id";
			$query .= " ORDER BY average desc, rated_imgs.image_id desc, submitted desc";
		}
		else
		{
			$query = "SELECT imgs_id, owner, submitted, title, file_name, plus_18, name_cat FROM (" . $sub_query . ") AS tagged_imgs";
		}

		if ($just_countings)
		{
			$result_rows = $this->db->query($query);

			return $result_rows->num_rows();
		}
		else
		{
			$offset = ($page - 1) * $page_size;
			$query .= " LIMIT {$offset}, {$page_size}";
			$result_rows = $this->db->query($query);

			//echo $query;

			return $result_rows->result_array();
		}
	}

	public function get_count_thumb_galleries($category_id = 0, $filter = 0, $sort = 'dd', $user_id = 0, $search_tags = array())
	{
		return $this->get_galleries(TRUE, $category_id, $filter, $sort, $user_id, 0, 0, $search_tags);
	}

	public function get_thumb_galleries($category_id = 0, $filter = 0, $sort = 'dd', $user_id = 0, $page = 1, $page_size = 18, $search_tags = array())
	{
		return $this->get_galleries(FALSE, $category_id, $filter, $sort, $user_id, $page, $page_size, $search_tags);
	}	
	
	private function get_galleries($just_countings = FALSE, $category_id = 0, $filter = 0, $sort = 'dd', $user_id = 0, $page = 1, $page_size = 18, $search_tags = array())
	{
		$lft_rgt = $this->get_lft_rgt_category($category_id, 'galleries');

		$sub_query = "SELECT gals.id, gals.user_id AS owner, created, name, name_cat, cats.lft, cats.rgt "
					."FROM galleries as gals JOIN galleries_categories as cats ON cats.id = gals.category_id WHERE hidden = 0";

		if(!empty($lft_rgt))
		{
			$sub_query .= " AND (cats.lft BETWEEN {$lft_rgt['lft']} AND {$lft_rgt['rgt']})";
		}

		if ($filter >= 10 && $filter <= 14)
		{
			$now = time();

			$from = $now - $this->filter[$filter]['sec'];

			$sub_query .= " AND (created BETWEEN '" . date('Y-m-d H:i:s', $from) . "' AND '" . date('Y-m-d H:i:s', $now) . "')";
		}

		if ($user_id != 0)
		{
			$sub_query .= " AND user_id = {$user_id}";
		}

		if (!empty($search_tags))
		{
			$sub_sub_query = $sub_query;

			$sub_query  = "SELECT * FROM (" . $sub_sub_query . ") AS filtered_gals";

			$sub_query .= " WHERE EXISTS (SELECT * FROM galleries_tags AS galtg JOIN tags ON tags.id = galtg.tag_id WHERE filtered_gals.id = gallery_id AND tag = ";
			$sub_query .= $this->db->escape($search_tags[0]) . ')';

			for ($i = 1; $i < count($search_tags); $i++)
			{
				$sub_query .= " AND EXISTS (SELECT * FROM galleries_tags AS galtg JOIN tags ON tags.id = galtg.tag_id WHERE filtered_gals.id = gallery_id AND tag = ";
				$sub_query .= $this->db->escape($search_tags[$i]) . ')';
			}
		}

		if ($sort == 'dd')
		{
			$query  = "SELECT id, owner, created, name, name_cat FROM (" . $sub_query . ") AS tagged_gals";
			$query .= " ORDER BY created desc";
		}
		
		/*
		elseif ($sort == 'ul')
		{
			$query = "SELECT imgs_id, fav_imgs.image_id, submitted, title, file_name, name_cat, COUNT(*) AS counter FROM (" . $sub_query . ") AS tagged_imgs";
			$query .= " LEFT JOIN favorites AS fav_imgs ON tagged_imgs.imgs_id = fav_imgs.image_id";
			$query .= " GROUP BY tagged_imgs.imgs_id";
			$query .= " ORDER BY counter desc, fav_imgs.image_id desc, submitted desc";
		}
		elseif ($sort == 'oc')
		{
			$query = "SELECT imgs_id, submitted, title, file_name, name_cat, AVG(rate) AS average FROM (" . $sub_query . ") AS tagged_imgs";
			$query .= " LEFT JOIN images_evaluations AS rated_imgs ON tagged_imgs.imgs_id = rated_imgs.image_id";
			$query .= " GROUP BY tagged_imgs.imgs_id";
			$query .= " ORDER BY average desc, rated_imgs.image_id desc, submitted desc";
		}
		 * 
		 */
		
		else
		{
			$query = "SELECT id, created, name, name_cat FROM (" . $sub_query . ") AS tagged_gals";
		}

		if ($just_countings)
		{
			$result_rows = $this->db->query($query);

			return $result_rows->num_rows();
		}
		else
		{
			$offset = ($page - 1) * $page_size;
			$query .= " LIMIT {$offset}, {$page_size}";
			$result_rows = $this->db->query($query);

			//echo $query;

			return $result_rows->result_array();
		}
	}	
	
	public function get_cats_uri_rows($segs, $type)
	{
		$table_name = $this->categories_tables[$type];
		$result = array();
		
		end($segs);
		
		$this->db->where('short_name_cat', current($segs));
		$query = $this->db->get($table_name);

		if ($query->num_rows() > 0)
		{
			foreach($query->result_array() as $node)
			{
				$path = $this->build_path_cats($node['id'], $type);
				
				if ($path === $segs)
				{
					$result = $this->build_path_cats($node['id'], $type, TRUE);
					break;
				}
			}
		}

		return $result;
		
		/*
		$parent_id = NULL;
		$result = array();

		for ($i = 0; $i <= sizeof($segs) - 1; $i++)
		{
			$this->db->where('short_name_cat', $segs[$i]);
			$this->db->where('parent_cat_id', $parent_id);
			$query = $this->db->get($table_name);

			if ($query->num_rows() > 0)
			{
				$row = $query->row_array();
				$parent_id = $row['id'];
				$result[] = $row;
			}
			else
			{
				$result = array();
				break;
			}
		}
		return $result;
		 * 
		 */
	}

	public function get_sub_categories($parent_id, $type)
	{
		$table_name = $this->categories_tables[$type];
		
		$this->db->where('parent_cat_id', $parent_id);
		$query = $this->db->get($table_name);

		return $query->result_array();
	}

	public function sub_cats_exist($parent_id, $type)
	{
		$table_name = $this->categories_tables[$type];
		
		$this->db->where('parent_cat_id', $parent_id);
		$query = $this->db->get($table_name);

		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function build_path_cats($category_id, $type, $full = FALSE)
	{
		$table_name = $this->categories_tables[$type];
				
		$query  = "SELECT " . ($full ? '*' : 'parent.short_name_cat') . " FROM {$table_name} AS node, {$table_name} AS parent ";
		$query .= "WHERE node.lft BETWEEN parent.lft AND parent.rgt ";
		$query .= "AND node.id = {$category_id};";
		
		$result = array();
		
		if ($full)
		{
			$result = $this->db->query($query)->result_array();
		}
		else
		{
			foreach ($this->db->query($query)->result_array() as $node)
			{
				$result[] = $node['short_name_cat'];
			}
		}

		return $result;
		
		/*
		do
		{
			$this->db->where('id', $category_id);
			$query = $this->db->get($table_name);

			if ($query->num_rows() > 0)
			{
				$row = $query->row();
				$result[] = $row->short_name_cat;
				$category_id = $row->parent_cat_id;
			}
			else
			{
				$result = array();
				break;
			}
		}
		while ($category_id !== NULL);

		return empty($result) ? $result : array_reverse($result);
		 * 
		 */
	}

	public function get_image($image_id)
	{
		$this->db->where('id', $image_id);
		$query = $this->db->get('images');

		if ($query->num_rows() > 0)
		{
			return $query->row();
		}
		else
		{
			return FALSE;
		}
	}

	public function get_user($user_id)
	{
		$this->db->where('id', $user_id);
		$query = $this->db->get('users');

		if ($query->num_rows() > 0)
		{
			return $query->row();
		}
		else
		{
			return FALSE;
		}
	}

	public function get_active_user($user_id)
	{
		$this->db->where('id', $user_id);
		$query = $this->db->get('users');

		if ($query->num_rows() > 0)
		{
			return $query->row();
		}
		else
		{
			return FALSE;
		}
	}
	
	public function counts_favorites_today($image_id)
	{
		return $this->counts_favorites($image_id, TRUE);
	}

	public function counts_favorites($image_id, $today = FALSE)
	{
		$query  = "SELECT COUNT(*) AS number_favs FROM favorites ";
		$query .= "WHERE image_id = {$image_id} ";

		if ($today)
		{
			$now = date("Y-m-d", time());

			$from = $now . ' 00:00:00';
			$to   = $now . ' 23:59:59';

			$query .= "AND add_to_favorites BETWEEN '" . $from . "' AND '" . $to . "'";
		}

		$result = $this->db->query($query);

		return $result->row()->number_favs;
	}

	public function increment_views($image_id)
	{
		$this->db->insert('views', array('image_id' => $image_id));

		return $this->db->affected_rows() == 1;
	}

	public function counts_views_today($image_id)
	{
		return $this->counts_views($image_id, TRUE);
	}

	public function counts_views($image_id, $today = FALSE)
	{
		$query  = "SELECT COUNT(*) AS number_views FROM views ";
		$query .= "WHERE image_id = {$image_id} ";

		if ($today)
		{
			$now = date("Y-m-d", time());

			$from = $now . ' 00:00:00';
			$to   = $now . ' 23:59:59';

			$query .= "AND viewed BETWEEN '" . $from . "' AND '" . $to . "'";
		}

		$result = $this->db->query($query);

		return $result->row()->number_views;
	}

	public function increment_downloads($image_id)
	{
		$this->db->insert('downloads', array('image_id' => $image_id));

		return $this->db->affected_rows() == 1;
	}

	public function counts_downloads_today($image_id)
	{
		return $this->counts_downloads($image_id, TRUE);
	}

	public function counts_downloads($image_id, $today = FALSE)
	{
		$query  = "SELECT COUNT(*) AS number_downloads FROM downloads ";
		$query .= "WHERE image_id = {$image_id} ";

		if ($today)
		{
			$now = date("Y-m-d", time());

			$from = $now . ' 00:00:00';
			$to   = $now . ' 23:59:59';

			$query .= "AND downloaded BETWEEN '" . $from . "' AND '" . $to . "'";
		}

		$result = $this->db->query($query);

		return $result->row()->number_downloads;
	}

	public function who_favorites($image_id, $page_size = 100, $page = 1)
	{
		$query  = "SELECT users.id AS user_id, favorites.id AS favorites_id, add_to_favorites, email, image_id FROM favorites ";
		$query .= "JOIN users ON favorites.user_id = users.id ";
		$query .= "WHERE image_id = {$image_id} ";
		$query .= "ORDER BY add_to_favorites desc ";
		
		$offset = ($page - 1) * $page_size;

		$query .= "LIMIT {$offset}, {$page_size}";

		$result_rows = $this->db->query($query);

		return $result_rows->result();
	}

	public function added_to_favorites($image_id, $user_id)
	{
		$query = $this->db->get_where('favorites', array('user_id' => $user_id, 'image_id' => $image_id), 1, 0);

		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function add_to_favorites($image_id, $user_id)
	{
		$data = array(
			'image_id' => $image_id,
			'user_id' => $user_id
		);

		$this->db->insert('favorites', $data);

		return $this->db->affected_rows() == 1;
	}

	public function remove_from_favorites($image_id, $user_id)
	{
		$this->db->where('image_id', $image_id);
		$this->db->where('user_id', $user_id);

		$this->db->delete('favorites');

		return $this->db->affected_rows() == 1;
	}

	public function delete_image($image_id)
	{
		$this->db->where('id', $image_id);
		$this->db->delete('images');

		return $this->db->affected_rows() == 1;
	}
	
	public function soft_delete_image($image_id)
	{
		$this->db->where('id', $image_id);
		$this->db->update('images', array('hidden' => 1));

		return $this->db->affected_rows() == 1;
	}	
	
	public function soft_delete_gallery($gallery_id)
	{
		$this->db->where('id', $gallery_id);
		$this->db->update('galleries', array('hidden' => 1));

		return $this->db->affected_rows() == 1;
	}	
	
	public function get_tags_id(array $tags)
	{
		if (!empty($tags)) 
		{
			array_walk($tags, function (&$tag, $key) {
				$tag = $this->db->escape($tag);
			});
			
			$query = "SELECT id FROM tags WHERE tag IN (" . implode(', ', $tags) . ');';
			
			$result = $this->db->query($query);

			if ($result->num_rows() > 0)
			{
				foreach($result->result_array() as $row)
				{
					$tags_id[] = $row['id'];
				}
				
				return $tags_id;
			}
			else
			{
				return FALSE;
			}
		}
		else 
		{
			return FALSE;
		}
	}
	/*
	public function associate_tags_image($image_id, array $tags = array(), array $user_tags = array())
	{			
		$this->db->trans_begin();
		
		$query  = "SELECT images_tags.id as id, tag ";
		$query .= "FROM images_tags JOIN tags ON tags.id = images_tags.tag_id ";
		$query .= "WHERE image_id = {$image_id} LOCK IN SHARE MODE";
		
		$tags_image = $this->db->query($query)->result_array();
				
		$old_tags_image = array();
		
		foreach ($tags_image as $tag_image) 
		{
			$old_tags_image[] = $tag_image['tag'];
		}		
		
		$tags_added = array_diff($tags, $old_tags_image);
		$tags_removed = array_diff($old_tags_image, $tags);
		
		if (!empty($tags_added))
		{
			if (!$this->add_tags($tags_added))
			{
				$this->db->trans_rollback();
				return FALSE;
			}
			
			$tags_added_id = $this->get_tags_id($tags_added);
		}
		
		if (!empty($tags_removed))
		{
			foreach ($tags_removed as $tag_remove)
			{
				foreach ($tags_image as $tag_image)
				{
					if ($tag_image['tag'] == $tag_remove)
					{
						$ids_to_delete[] = $tag_image['id'];
						break;
					}
				}
			}
			
			$query = "DELETE FROM images_tags WHERE id IN (" . implode(', ', $ids_to_delete) . ');';
			$this->db->query($query);
		}
		
		if (isset($tags_added_id))
		{
			$query = "INSERT INTO images_tags (image_id, tag_id) VALUES ";

			$end = end($tags_added_id);
			foreach ($tags_added_id as $tag_added_id) 
			{
				$query .= "({$image_id}, {$tag_added_id})";

				if ($tag_added_id !== $end) 
				{
					$query .= ', ';
				} 
				else 
				{
					$query .= ';';
				}
			}

			$this->db->query($query);
		}
				
		$query = "UPDATE images_tags SET user_tag = 0 WHERE image_id = {$image_id};";
		$this->db->query($query);
		
		if (!empty($user_tags) && ($user_tags_id = $this->get_tags_id($user_tags)))
		{
			$query  = "UPDATE images_tags SET user_tag = 1 WHERE image_id = {$image_id} AND tag_id IN ";
			$query .= "(" . implode(', ', $user_tags_id) . ");";
		
			$this->db->query($query);
		}
				
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
	}
	*/
	
	public function associate_tags_object($object_id, $type, array $tags = array(), array $user_tags = array())
	{			
		$this->db->trans_begin();
		
		switch ($type)
		{
			case 'image':
				$ass_tab = 'images_tags';
				//$col_obj = 'image_id';
				break;
			case 'gallery':
				$ass_tab = 'galleries_tags';
				//$col_obj = 'gallery_id';
				break;
		}
		
		$query  = "SELECT {$ass_tab}.id as id, tag ";
		$query .= "FROM {$ass_tab} JOIN tags ON tags.id = {$ass_tab}.tag_id ";
		$query .= "WHERE {$type}_id = {$object_id} LOCK IN SHARE MODE";
		
		$tags_object = $this->db->query($query)->result_array();
				
		$old_tags_object = array();
		
		foreach ($tags_object as $tag_object) 
		{
			$old_tags_object[] = $tag_object['tag'];
		}		
		
		$tags_added = array_diff($tags, $old_tags_object);
		$tags_removed = array_diff($old_tags_object, $tags);
		
		if (!empty($tags_added))
		{
			if (!$this->add_tags($tags_added))
			{
				$this->db->trans_rollback();
				return FALSE;
			}
			
			$tags_added_id = $this->get_tags_id($tags_added);
		}
		
		if (!empty($tags_removed))
		{
			foreach ($tags_removed as $tag_remove)
			{
				foreach ($tags_object as $tag_object)
				{
					if ($tag_object['tag'] == $tag_remove)
					{
						$ids_to_delete[] = $tag_object['id'];
						break;
					}
				}
			}
			
			$query = "DELETE FROM {$ass_tab} WHERE id IN (" . implode(', ', $ids_to_delete) . ');';
			$this->db->query($query);
		}
		
		if (isset($tags_added_id))
		{
			$query = "INSERT INTO {$ass_tab} ({$type}_id, tag_id) VALUES ";

			$end = end($tags_added_id);
			foreach ($tags_added_id as $tag_added_id) 
			{
				$query .= "({$object_id}, {$tag_added_id})";

				if ($tag_added_id !== $end) 
				{
					$query .= ', ';
				} 
				else 
				{
					$query .= ';';
				}
			}

			$this->db->query($query);
		}
				
		$query = "UPDATE {$ass_tab} SET user_tag = 0 WHERE {$type}_id = {$object_id};";
		$this->db->query($query);
		
		if (!empty($user_tags) && ($user_tags_id = $this->get_tags_id($user_tags)))
		{
			$query  = "UPDATE {$ass_tab} SET user_tag = 1 WHERE {$type}_id = {$object_id} AND tag_id IN ";
			$query .= "(" . implode(', ', $user_tags_id) . ");";
		
			$this->db->query($query);
		}
				
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return FALSE;
		}
		else
		{
			$this->db->trans_commit();
			return TRUE;
		}
	}	
	
	
	
	
	
	public function add_tags(array $tags)
	{
		$result = TRUE;
		
		if (!empty($tags)) 
		{
			$this->db->trans_begin();
			
			$temp_tags = $tags;
			
			array_walk($temp_tags, function (&$tag, $key) {
				$tag = $this->db->escape($tag);
			});
					
			$query = "SELECT tag FROM tags WHERE tag IN (" . implode(', ', $temp_tags) . ');';

			$old_tags = array();
			
			foreach ($this->db->query($query)->result_array() as $tag_in_db)
			{
				$old_tags[] = $tag_in_db['tag'];
			}

			$new_tags = array_diff($tags, $old_tags);
			
			if (!empty($new_tags)) 
			{				
				$query = "INSERT IGNORE INTO tags (tag) VALUES ";

				$end = end($new_tags);
				
				foreach ($new_tags as $new_tag) {
					$query .= '(' . $this->db->escape($new_tag) . ')';

					if ($new_tag !== $end) 
					{
						$query .= ', ';
					} 
					else 
					{
						$query .= ';';
					}
				}
				
				$this->db->query($query);
			}
			
			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				$result = FALSE;
			}
			else
			{
				$this->db->trans_commit();
			}					
		}
		
		return $result;
	}

	public function get_object_user_tags($object_id, $type)
	{
		switch ($type)
		{
			case 'image':
				$ass_tab = 'images_tags';
				//$col_obj = 'image_id';
				break;
			case 'gallery':
				$ass_tab = 'galleries_tags';
				//$col_obj = 'gallery_id';
				break;
		}		
		
		$this->db->select("{$ass_tab}.id as id, tag");
		$this->db->from($ass_tab);
		$this->db->join("tags", "tags.id = {$ass_tab}.tag_id");
		$this->db->where("{$type}_id", $object_id);
		$this->db->where('user_tag', 1);

		$query = $this->db->get();

		return $query->result_array();
	}
		
	public function get_imgs_filename_to_gallery_thumb($gallery_id, $number_images)
	{
		$query  = "SELECT images.file_name, images.plus_18 FROM galleries_images, images ";
		$query .= "WHERE galleries_images.gallery_id = {$gallery_id} AND galleries_images.image_id = images.id ";
		$query .= "ORDER BY galleries_images.order ";
		$query .= "LIMIT 0, {$number_images};";
				
		$result_rows = $this->db->query($query);

		return $result_rows->result_array();
	}
	
	public function get_gallery_images($gallery_id)
	{
		$query  = "SELECT * FROM galleries_images, images ";
		$query .= "WHERE galleries_images.gallery_id = {$gallery_id} AND galleries_images.image_id = images.id ";
		$query .= "ORDER BY galleries_images.order";

		$result_rows = $this->db->query($query);

		return $result_rows->result();	
	}
	
	public function get_images_by_id_order_by_field(array $ids)
	{
		$ids_list = '';
		
		foreach ($ids as $idx => $id)
		{
			$ids_list .= "{$id}";
			
			if ($idx < count($ids) - 1)
			{
				$ids_list .= ', ';
			}
			else 
			{
				$ids_list .= ')';
			}			
		}
		
		$query  = "SELECT * FROM images ";
		$query .= "WHERE id IN (";
		$query .= $ids_list . ' ORDER BY FIELD(';
		$query .= 'id, ' . $ids_list . ';';
				
		$result_rows = $this->db->query($query);

		return $result_rows->result_array();			
	}
	
	public function get_gallery($gallery_id)
	{
		$this->db->where('id', $gallery_id);
		$query = $this->db->get('galleries');

		if ($query->num_rows() > 0)
		{
			return  $query->row();
		}
		else
		{
			return FALSE;
		}		
	}
	
	public function add_gallery($data)
	{
		$this->db->trans_begin();

		// zablokowanie wierszy z pracami, które dodawane są do galerii...
		$query = "SELECT * FROM images WHERE id IN (" . implode(', ', $data['images']) . ') LOCK IN SHARE MODE;';
		
		if ($this->db->query($query)->num_rows() !== count($data['images']))
		{
			$this->db->trans_rollback();
			return FALSE;		
		}
		
		$data_gallery = array(
			'user_id' => $data['user_id'],
			'name' => $data['name'],
			'description' => $data['description'],
			'category_id' => $data['category_id'],
		);
		
		$this->db->insert('galleries', $data_gallery);
		$gallery_id = $this->db->insert_id();
		
		$query  = "INSERT INTO galleries_images (gallery_id, image_id, `order`) VALUES ";
		
		foreach ($data['images'] as $order_index => $image_id)
		{
			$query .= '(' . $gallery_id . ', ' . $image_id . ', ' . $order_index . ')';
			
			if ($order_index < count($data['images']) - 1)
			{
				$query .= ', ';
			}
			else 
			{
				$query .= ';';				
			}
		}
		
		$this->db->query($query);		
		
		if (!$this->associate_tags_object($gallery_id, 'gallery', $data['tags'], $data['user_tags']) || $this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$return = FALSE;
		}
		else
		{
			$this->db->trans_commit();
			$return = TRUE;
		}
		
		return $return;
	}

	public function update_gallery($gallery_id, $data)
	{
		$this->db->trans_begin();
		
		// zablokowanie wiersza galerii...
		$query = "SELECT * FROM galleries WHERE id = {$gallery_id} LOCK IN SHARE MODE;";
		
		if ($this->db->query($query)->num_rows() !== 1)
		{
			$this->db->trans_rollback();
			return FALSE;		
		}				

		// zablokowanie wierszy z pracami, które dodawane są do galerii...
		$query = "SELECT * FROM images WHERE id IN (" . implode(', ', $data['images']) . ') LOCK IN SHARE MODE;';
		
		if ($this->db->query($query)->num_rows() !== count($data['images']))
		{
			$this->db->trans_rollback();
			return FALSE;		
		}
		
		$update_data_gallery = array(
			'name' => $data['name'],
			'description' => $data['description'],
			'category_id' => $data['category_id'],
			'can_comment' => $data['can_comment']
		);		
		
		$this->db->where('id', $gallery_id);
		$this->db->update('galleries', $update_data_gallery);
				
		$this->db->where('gallery_id', $gallery_id);
		$this->db->delete('galleries_images');
		
		$query  = "INSERT INTO galleries_images (gallery_id, image_id, `order`) VALUES ";
		
		foreach ($data['images'] as $order_index => $image_id)
		{
			$query .= '(' . $gallery_id . ', ' . $image_id . ', ' . $order_index . ')';
			
			if ($order_index < count($data['images']) - 1)
			{
				$query .= ', ';
			}
			else 
			{
				$query .= ';';				
			}
		}
		
		$this->db->query($query);		

		if (!$this->associate_tags_object($gallery_id, 'gallery', $data['tags'], $data['user_tags']) || $this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$return = FALSE;
		}
		else
		{
			$this->db->trans_commit();
			$return = TRUE;
		}
		
		return $return;
	}
	
	
	
}
