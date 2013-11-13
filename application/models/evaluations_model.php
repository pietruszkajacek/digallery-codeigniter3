<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Evaluations_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function rated_image($image_id, $user_id)
	{
		$query = $this->db->get_where('images_evaluations', array('user_id' => $user_id, 'image_id' => $image_id), 1, 0);

		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}			
	}

	public function rated_gallery($gallery_id, $user_id)
	{
		$query = $this->db->get_where('galleries_evaluations', array('user_id' => $user_id, 'gallery_id' => $gallery_id), 1, 0);

		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}			
	}	
	
	public function get_image_evaluation_rate($image_id, $user_id)
	{
		$query = $this->db->get_where('images_evaluations', array('user_id' => $user_id, 'image_id' => $image_id), 1, 0);

		if ($query->num_rows() > 0)
		{
			return intval($query->row()->rate);
		}
		else
		{
			return FALSE;
		}	
	}
}
