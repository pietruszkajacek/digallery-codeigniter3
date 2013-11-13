<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comments_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function counts_user_comments($user_id, $type = 'all')
	{
		$query  = "SELECT COUNT(*) AS number_comments FROM comments ";
		$query .= "WHERE user_id = {$user_id} ";

		if ($type !== 'all')
		{
			$query .= "AND type = '{$type}' ";
		}		
		
		$result = $this->db->query($query);

		return $result->row()->number_comments;
	}	
	
	public function counts_image_comments($image_id)
	{
		$query  = "SELECT COUNT(*) AS number_comments FROM comments ";
		$query .= "WHERE object_id = {$image_id} AND type = 'image'";

		$result = $this->db->query($query);

		return $result->row()->number_comments;
	}

	public function counts_gallery_comments($gallery_id)
	{
		$query  = "SELECT COUNT(*) AS number_comments FROM comments ";
		$query .= "WHERE object_id = {$gallery_id} AND type = 'gallery'";

		$result = $this->db->query($query);

		return $result->row()->number_comments;
	}	
	
	public function counts_profile_comments($profile_user_id)
	{
		$query  = "SELECT COUNT(*) AS number_comments FROM comments ";
		$query .= "WHERE object_id = {$profile_user_id} AND type = 'profile'";

		$result = $this->db->query($query);

		return $result->row()->number_comments;
	}
	
	public function add_comment($object_id, $user_id, $comment, $time, $type, $rate = 0)
	{
		$comment_data = array(
			'user_id' => $user_id,
			'comment' => $comment,
			'object_id' => $object_id,
			'commenting_time' => $time,
			'type' => $type,
		);
	
		$this->db->trans_begin();
		
		$this->db->insert('comments', $comment_data);
		$comment_id = $this->db->insert_id();
		
		if ($rate > 0)
		{
			$eval_data  = array(
				'user_id' => $user_id,
				'comment_id' => $comment_id,
				'rate' => $rate,
			);
			
			switch ($type)
			{
				case 'image':
					$eval_data['image_id'] = $object_id;
					$this->db->insert('images_evaluations', $eval_data);
					break;
				case 'gallery':
					$eval_data['gallery_id'] = $object_id;
					$this->db->insert('galleries_evaluations', $eval_data);
					break;
			}
			
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

	public function get_user_comments($user_id, $page, $page_size = 20, $type = 'all')
	{
		$query  = "SELECT id as comment_id, object_id, comment, commenting_time, last_edit, type FROM comments ";
		$query .= "WHERE user_id = {$user_id} ";
		
		if ($type !== 'all')
		{
			$query .= "AND type = '{$type}' ";
		}
		
		$query .= "ORDER BY commenting_time desc";
		$offset = ($page - 1) * $page_size;

		$query .= " LIMIT {$offset}, {$page_size}";

		$result_rows = $this->db->query($query);

		return $result_rows->result();		
	}
	
	public function get_profile_comments($profile_user_id, $page_size = 20, $page = 1)
	{
		$query  = "SELECT users.avatar, users.email, users.signature, comments.id AS comment_id, ";
		$query .= "comments.comment, comments.commenting_time, comments.last_edit, comments.user_id, comments.object_id FROM comments ";
		$query .= "JOIN users ON comments.user_id = users.id ";
		$query .= "WHERE object_id = {$profile_user_id} AND type = 'profile' ";
		$query .= "ORDER BY commenting_time desc";
		$offset = ($page - 1) * $page_size;

		$query .= " LIMIT {$offset}, {$page_size}";

		$result_rows = $this->db->query($query);

		return $result_rows->result();
	}	
	
	public function get_image_comments($image_id, $page_size = 20, $page = 1)
	{
		$sub_query  = "SELECT users.avatar, users.email, users.signature, comments.id, ";
		$sub_query .= "comments.comment, comments.commenting_time, comments.last_edit, comments.user_id, comments.object_id FROM comments ";
		$sub_query .= "JOIN users ON comments.user_id = users.id ";
		$sub_query .= "WHERE object_id = {$image_id} AND type = 'image' ";

		$query  = "SELECT avatar, email, signature, image_comments.id AS comment_id, comment, commenting_time, last_edit, image_comments.user_id, object_id, rate ";
		$query .= "FROM (" . $sub_query . ") AS image_comments ";
		$query .= "LEFT JOIN images_evaluations AS evals_imgs ON image_comments.id = evals_imgs.comment_id ";
		$query .= "ORDER BY commenting_time desc";

		$offset = ($page - 1) * $page_size;

		$query .= " LIMIT {$offset}, {$page_size}";

		$result_rows = $this->db->query($query);

		return $result_rows->result();
	}
	
	public function get_gallery_comments($gallery_id, $page_size = 20, $page = 1)
	{
		$sub_query  = "SELECT users.avatar, users.email, users.signature, comments.id, ";
		$sub_query .= "comments.comment, comments.commenting_time, comments.last_edit, comments.user_id, comments.object_id FROM comments ";
		$sub_query .= "JOIN users ON comments.user_id = users.id ";
		$sub_query .= "WHERE object_id = {$gallery_id} AND type = 'gallery' ";

		$query  = "SELECT avatar, email, signature, gallery_comments.id AS comment_id, comment, commenting_time, last_edit, gallery_comments.user_id, object_id, rate ";
		$query .= "FROM (" . $sub_query . ") AS gallery_comments ";
		$query .= "LEFT JOIN galleries_evaluations AS evals_gals ON gallery_comments.id = evals_gals.comment_id ";
		$query .= "ORDER BY commenting_time desc";

		$offset = ($page - 1) * $page_size;

		$query .= " LIMIT {$offset}, {$page_size}";

		$result_rows = $this->db->query($query);

		return $result_rows->result();
	}	

	public function get_comment($comment_id)
	{
		$query = $this->db->get_where('comments', array('id' => $comment_id), 1, 0);

		if ($query->num_rows() > 0)
		{
			return $query->row();
		}
		else
		{
			return FALSE;
		}
	}

	public function delete_comment($comment_id)
	{
		$this->db->where(array('id' => $comment_id))->delete('comments');

		return $this->db->affected_rows() == 1;
	}

	public function update_comment($comment_id, $comment)
	{
		$this->db->where('id', $comment_id);
		$this->db->update('comments', array('comment' => $comment));

		return $this->db->affected_rows() == 1;
	}
}
