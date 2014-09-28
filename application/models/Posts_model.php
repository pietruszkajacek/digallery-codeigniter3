<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posts_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_recipients($match)
	{	
		$query = "SELECT email FROM users WHERE email REGEXP '^[[:alnum:][._.][.period.][.-.]]*{$match}[[:alnum:][._.][.period.][.-.]]*[[.@.]]'";
		$result_rows = $this->db->query($query);
		
		$match_recipients = array();
		foreach ($result_rows->result_array() as $match_recipient)
		{
			$match_recipients[] = $match_recipient['email'];
		}
		
		return $match_recipients;
	}
	
	public function counts_posts($box, $user_id)
	{
		$query = "SELECT * FROM posts WHERE posts.user_id_".($box == 'inbox' ? 'to' : 'from')." = {$user_id} AND {$box} = 1";
		$result_rows = $this->db->query($query);

		return $result_rows->num_rows();
	}

	public function delete_posts($box, $posts, $logged_in_user)
	{
		$this->db->trans_begin();

		foreach ($posts as $post_id)
		{
			$post_id = (int) $post_id;
			$this->db->update('posts', array($box => 0), array('id' => $post_id, ($box == 'inbox' ? 'user_id_to' : 'user_id_from') => $logged_in_user));
		}

		if ($this->db->trans_status() === FALSE)
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

	public function get_post($box, $post_id, $user_id)
	{
		$query  = "SELECT * FROM posts WHERE id = {$post_id} AND {$box} = 1 ";
		$query .= "AND user_id_".($box == 'inbox' ? 'to' : 'from')." = {$user_id}";

		$result_rows = $this->db->query($query);

		if ($result_rows->num_rows() > 0)
		{
			$post = $result_rows->row();

			// oznaczenie wiadomości jako przeczytanej
			if ($box == 'inbox' && !$post->viewed)
			{
				$this->db->update('posts', array('viewed' => 1), "id = {$post_id}");
			}
		}
		else
		{
			$post = FALSE;
		}

		return $post;
	}

	public function get_posts($box, $user_id, $page_size = 10, $page = 1)
	{
		$query  = "SELECT users_from.username as 'from', users_to.username as 'to', posts.message, posts.subject,";
		$query .= " posts.date, posts.viewed, posts.id as message_id,";
		$query .= " users_from.id as from_id, users_to.id as to_id";
		$query .= " FROM posts, users as users_from, users as users_to";
		$query .= " WHERE posts.{$box} = 1 AND posts.user_id_from = users_from.id AND posts.user_id_to = users_to.id";
		$query .= " AND posts.user_id_".($box == 'inbox' ? 'to' : 'from')." = {$user_id}";
		$query .= " ORDER BY date desc";

		$offset = ($page - 1) * $page_size;

		$query .= " LIMIT {$offset}, {$page_size}";

		$result_rows = $this->db->query($query);

		return $result_rows->result_array();
	}

	public function send_post($user_id_from, $user_id_to, $subject, $date, $message)
	{
		$post_data = array(
			'user_id_from' => $user_id_from,
			'user_id_to' => $user_id_to,
			'subject' => $subject,
			'date' => $date,
			'viewed' => 0,
			'message' => $message,
			'inbox' => 1,
			'outbox' => 1,
		);

		$this->db->insert('posts', $post_data);

		return $this->db->affected_rows() == 1;
	}
	
	public function counts_unreaded_posts($user_id) 
	{
		$this->db->where(array('user_id_to' => $user_id, 'viewed' => 0, 'inbox' => 1));
		$this->db->from('posts');
		
		return $this->db->count_all_results();
	}
}
