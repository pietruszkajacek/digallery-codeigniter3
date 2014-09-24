<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Testowy extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function cartest()
	{
		//$this->data['no_container_class'] = TRUE;
		//$this->data['js'][] = 'testdiv.js';
		//$this->render();		
		echo 'sfs';
	}
	
	public function divtest()
	{
		$this->data['no_container_class'] = TRUE;
		$this->data['js'][] = 'testdiv.js';
		$this->render();
	}
	
	
	public function widget()
	{
		//$this->load->model('browse_model');
		//echo json_encode($this->browse_model->get_images_categories());
		//$this->data['js'][] = 'widget.js';
		
		
		$this->render();
	}
	
	public function ionn()
	{
		if ($this->ion_auth->logged_in())
		{
			$i = 100;
		}
		
		$this->render();
	}
	
	public function arr_to()
	{
		$tab = $this->input->post('tabela');
		
		if ($tab[0] == 94 && $tab[1] == 93 && $tab[2] == 92)
		{
			$raxx = 1;
		}
		else
		{
			$raxx = 0;
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array("status" => $raxx)));
	
	}
	
	public function galthu()
	{
		$this->render();
	}	
	
	public function jcar()
	{
		$this->data['js'][] = 'jquery.jcarousel.min.js';
		$this->data['js'][] = 'jcar.js';
		
		$this->render();
	}
	
	function rebuild_tree($parent, $left)
	{
		$right = $left+1;

		$this->db->where('parent_cat_id', $parent);
		$query = $this->db->get('images_categories');

		foreach ($query->result() as $row)
		{
			$right = $this->rebuild_tree($row->id, $right);
		}

		$data = array(
               'lft' => $left,
               'rgt' => $right,
            );

		$this->db->where('id', $parent);
		$this->db->update('images_categories', $data);

		return $right + 1;
	}

	public function reb()
	{
		$this->rebuild_tree(NULL, 1);
	}
	
	public function valid_imgs()
	{
		$this->config->load('digallery', TRUE);
		$this->load->database();
		$this->load->library(array('session','ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'html'));
		$this->lang->load('digallery', 'polish');
				
		$this->load->model('browse_model');
		
		$images_ids = $this->browse_model->get_users_all_images_ids(11);
		
		$images_in_gallery = array(
			0 => array('id' => '98'),
			1 => array('id' => '75'),
		);
		
		echo var_dump($images_ids);
		echo '<br /><br />';
		echo var_dump($images_in_gallery);
		
		echo '<br /><br />';
		
		foreach ($images_ids as $image_id)
		{
			echo $image_id;
		}
		
		return;
		
		$check = TRUE;
		foreach ($images_in_gallery as $image_id)
		{
			if (!in_array($image_id, $images_ids))
			{
				$check = FALSE;
				break;
			}
		}
		
		if ($check)
		{
			echo 'TRUE';
		}
		else
		{
			
			echo 'FALSE';
		}		
	}
	
	public function ruter() {
		
		echo $this->uri->uri_string() . '<br />';
		echo '0: ' . $this->uri->segment(0) . '<br />';
		echo '1: ' . $this->uri->segment(1) . '<br />';
		echo '2: ' . $this->uri->segment(2) . '<br /> ';
		echo '3: ' . $this->uri->segment(3) . '<br /> ';
		echo '4: ' . $this->uri->segment(4) . '<br /> ';
		echo '5: ' . $this->uri->segment(5) . '<br /> ';
		echo '6: ' . $this->uri->segment(6) . '<br /> ';
		
		echo $this->uri->ruri_string() . '<br />';
		echo '0: ' . $this->uri->rsegment(0) . '<br />';
		echo '1: ' . $this->uri->rsegment(1) . '<br />';
		echo '2: ' . $this->uri->rsegment(2) . '<br /> ';
		echo '3: ' . $this->uri->rsegment(3) . '<br /> ';
		echo '4: ' . $this->uri->rsegment(4) . '<br /> ';
		echo '5: ' . $this->uri->rsegment(5) . '<br /> ';
		echo '6: ' . $this->uri->rsegment(6) . '<br /> ';

	}
	
	protected $zm = 'Testowy obiekt string';
	
	public function lay ()
	{
		$this->zm->content = 'DUPA';
		
		echo $this->zm;
		
		echo 'ley';
	}
	
	function klopoty($param = 'd!!!+ą---pol\'ską') {
		
		
		
		$param = substr(preg_replace('%((?<=[^\p{L}\p{N}])[\'\-]|[\'\-](?=[^\p{L}\p{N}]))%u', '', ' ' . $param . ' '), 1, -1);
		
		$param = preg_replace('%(?![\'\-\%\*])[^\p{L}\p{N}]+%u', ' ', $param);
		
		echo $param;
	}
	
	
}

/* End of file testowy.php */
/* Location: ./application/controllers/Testowy.php */
