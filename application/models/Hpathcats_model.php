<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Hpathcats_model extends CI_Model
{
	//const ALL_CATEGORIES = 1;
	
	private $table_name;
	//private $path = array();
	private $categories_info = array();
	private $valid_cats_path = FALSE;
		
	public function __construct()
	{
		parent::__construct();
	}

	public function init_by_path($table_name, $path = array())
	{
		$this->table_name = $table_name;
		
		if (empty($path))
		{
			$this->init_by_cat_id($table_name);
		}
		else if ($this->parse_path_categories($path) === FALSE)
		{
			$this->valid_cats_path = FALSE;
		}
		else
		{
			$this->valid_cats_path = TRUE;
		}
	}

	public function init_by_cat_id($table_name, $cat_id = 0)
	{
		$this->table_name = $table_name;
		
		if ($cat_id === 0)
		{
			$this->valid_cats_path = TRUE;
		}
		else
		{
			$this->categories_info = $this->build_path_cats($cat_id, TRUE);

			if (empty($this->categories_info))
			{
				$this->valid_cats_path = FALSE;
			}
			else
			{
				$this->valid_cats_path = TRUE;
			}
		}
	}
	
	protected function parse_path_categories($path)
	{
		$result = FALSE;

		if (!empty($path))
		{
			end($path);

			// znajdz wszystkie kategorie, których skrocona nazwa odpowiada ostatniemu segmentowi URI,
			// w tym wypadku ostaniemu elementowi przekazanemu w tablicy $segs		
			$this->db->where('short_name_cat', current($path));
			$query = $this->db->get($this->table_name);

			// Sprawdz poprawnosc sciezki hierarchi URI z baza. Jesli taka sciezka zostanie znaleziona
			// to jest zwracana jako tablica rekordow z tabeli kategorii
			if ($query->num_rows() > 0)
			{
				foreach ($query->result_array() as $node)
				{
					$tmp_path = $this->build_path_cats($node['id']);

					// Jeśli sciezka URI jest identyczna jak ta zwrocona z bazy
					// pobierz pelna informacje o sciezce kategorii
					if ($tmp_path === $path)
					{
						$this->categories_info = $this->build_path_cats($node['id'], TRUE);
						$result = TRUE;
						break;
					}
				}
			}
		}
		return $result;
	}
	
	// Zwraca pojedyncza sciezke do kategorii okreslonej parametrem $category_id
	public function build_path_cats($category_id, $full = FALSE)
	{
		$query  = "SELECT " . ($full ? '*' : 'parent.short_name_cat') . " FROM {$this->table_name} AS node, {$this->table_name} AS parent ";
		$query .= "WHERE node.lft BETWEEN parent.lft AND parent.rgt ";
		$query .= "AND node.id = {$category_id} ORDER BY parent.lft;";
		
		$result_array = $this->db->query($query)->result_array();
		array_shift($result_array); // usuniecie korzenia - kategorii nadrzednej dla wszystkich kategorii
		
		if ($full)
		{
			$result = $result_array;
		}
		else
		{
			foreach ($result_array as $node)
			{
				$result[] = $node['short_name_cat'];
			}
		}

		return $result;
	}
	
	public function validate_categories_path()
	{
		return $this->valid_cats_path;
	}
	
	public function get_current_cat_id()
	{
		if ($this->validate_categories_path())
		{
			if (empty($this->categories_info))
			{
				$result = 0;
			}
			else
			{
				//$last_row = end($this->categories_info);
				//$result = $last_row['id'];
				$result = end($this->categories_info)['id'];
			}
		}
		else
		{
			$result = NULL;
		}
		
		return $result;
	}
}
