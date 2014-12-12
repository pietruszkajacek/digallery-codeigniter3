<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hcategories_model extends CI_Model
{
	private $table_name;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function category_exist($id_category)
	{
		$this->db->where('id', $id_category);
		$query = $this->db->get($this->table_name);

		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}	
}