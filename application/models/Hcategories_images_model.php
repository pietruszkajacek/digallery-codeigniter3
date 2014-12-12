<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hcategories_images_model extends Hcategories_model
{
	private $table_name;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->table_name = 'images_categories';
	}
}