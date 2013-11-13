<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Testowy2 extends CI_Controller
{
	private $stop18;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('session');
	}

	public function reque()
	{
		$this->load->library('session');
		
		//$this->session->set_userdata('item', 'pięć');
		//$this->session->set_userdata('item', 'pięć222');
		//$this->session->set_userdata('item', 'pięć2223434');
		
		//$this->session->set_flashdata('f2','d');
		//$this->session->set_flashdata('flash_1','s');
		
		$data_sess = array('f1' => '1', 'f2' => '2');
		//$this->session->set_flashdata($data_sess);
		$this->session->set_userdata($data_sess);
	}
	
	public function json()
	{
		$tab = ['Jacek', 'Placek', 'na@oleju.com.pl', 'mama@wola.ty.zlodzieju.org'];
		
		echo json_encode($tab);
		
	}
	
	private function exif_get_fraction($value) 
	{ 
		$fraction = explode('/', $value);
		
		if (count($fraction) === 1)
		{
			return $fraction[0];
		}
		else
		{
			$counter = floatval($fraction[0]);
			$denominator = floatval($fraction[1]);
			
			return ($denominator == 0) ? $counter : round($counter / $denominator, 2);
		}
	}	
	
	function getmicrotime()
	{ 
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
    }
	
	public function get_exif_data($imagePath = './uploads/132.jpg')
	{
		$time_start = $this->getmicrotime();
		$exif = read_exif_data($imagePath);
		$time_end = $this->getmicrotime();
		$time = $time_end - $time_start;		
		echo $time;
		
		//echo var_dump(read_exif_data($imagePath));
		return;
		
		$exif_ifd0 = read_exif_data($imagePath, 'IFD0', 0);
		$exif_exif = read_exif_data($imagePath, 'EXIF', 0);

		$notFound = NULL;

		$data = array();

		if ($exif_ifd0 !== FALSE)
		{
			// Make
			if (array_key_exists('Make', $exif_ifd0))
			{
				$data['camMake'] = $exif_ifd0['Make'];
			}
			else
			{
				$data['camMake'] = $notFound;
			}

			// Model
			if (array_key_exists('Model', $exif_ifd0))
			{
				$data['camModel'] = $exif_ifd0['Model'];
			}
			else
			{
				$data['camModel'] = $notFound;
			}

			// Exposure
			if (array_key_exists('ExposureTime', $exif_ifd0))
			{
				$data['camExposure'] = $exif_ifd0['ExposureTime'];
			}
			else
			{
				$data['camExposure'] = $notFound;
			}

			// Aperture - przesłona
			if (array_key_exists('ApertureFNumber', $exif_ifd0['COMPUTED']))
			{
				$data['camAperture'] = $exif_ifd0['COMPUTED']['ApertureFNumber'];
			}
			else
			{
				$data['camAperture'] = $notFound;
			}

			// Date
			if (array_key_exists('DateTime', $exif_ifd0))
			{
				$data['camDate'] = $exif_ifd0['DateTime'];
			}
			else
			{
				$data['camDate'] = $notFound;
			}
			
			// Software
			if (array_key_exists('Software', $exif_ifd0))
			{
				$data['camSoftware'] = $exif_ifd0['Software'];
			}
			else
			{
				$data['camSoftware'] = $notFound;
			}
			
			// Focal - ogniskowa
			if (array_key_exists('FocalLength', $exif_ifd0))
			{
				$data['camFocal'] = $this->exif_get_fraction($exif_ifd0['FocalLength']);
			}
			else
			{
				$data['camFocal'] = $notFound;
			}			
			
		}

		if ($exif_exif !== FALSE)
		{
			// ISO
			if (array_key_exists('ISOSpeedRatings', $exif_exif))
			{
				$data['camIso'] = $exif_exif['ISOSpeedRatings'];
			}
			else
			{
				$data['camIso'] = $notFound;
			}
		}

		return $data;
	}	
	
	public function test4()
	{
		$this->load->database();
		
		$this->db->trans_begin();
		
		$this->db->where('id', 96);
		$this->db->delete('images');

		//$this->db->query($query);

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			echo 'not OK';
		}
		else
		{
			$this->db->trans_commit();
			echo 'OK';
		}
	}
	
	public function tags()
	{
		$this->load->model('browse_model');
		$this->load->helper('browse');
		
		$text = "";
		
		//$this->browse_model->add_tags(split_tags($text), 12);
		//$this->browse_model->get_tags_id(array("sdf'", "sddsdsd's"));
		//$this->associates_tags_images()
		$this->browse_model->associate_tags_image(58, array(0 => ''), []);
		
		//echo array_search('test', array(0 => 'test')) !== FALSE ? '1' : '0';
	}
	
	
function split_words($text, $idx)
{
	// Remove BBCode
	$text = preg_replace('%\[/?(b|u|s|ins|del|em|i|h|colou?r|quote|code|img|url|email|list|topic|post|forum|user)(?:\=[^\]]*)?\]%', ' ', $text);

	// Remove any apostrophes or dashes which aren't part of words
	$text = substr(ucp_preg_replace('%((?<=[^\p{L}\p{N}])[\'\-]|[\'\-](?=[^\p{L}\p{N}]))%u', '', ' '.$text.' '), 1, -1);

	// Remove punctuation and symbols (actually anything that isn't a letter or number), allow apostrophes and dashes (and % * if we aren't indexing)
	$text = ucp_preg_replace('%(?![\'\-'.($idx ? '' : '\%\*').'])[^\p{L}\p{N}]+%u', ' ', $text);

	// Replace multiple whitespace or dashes
	$text = preg_replace('%(\s){2,}%u', '\1', $text);

	// Fill an array with all the words
	$words = array_unique(explode(' ', $text));

	// Remove any words that should not be indexed
	foreach ($words as $key => $value)
	{
		// If the word shouldn't be indexed, remove it
		if (!validate_search_word($value, $idx))
			unset($words[$key]);
	}

	return $words;
}	
	
	public function input_test()
	{
		$this->load->library('form_validation');
		
		$tags = array();
		
		$this->form_validation->set_rules('tags[0]', 'Tag', 'min_length[0]|min_length[8]');
		$this->form_validation->set_rules('tags[1]', 'Tag', 'min_length[2]');
		$this->form_validation->set_rules('tags[2]', 'Tag', 'min_length[3]');

		//if ($this->input->post('tags'))
		
		if ($this->form_validation->run() == TRUE)
		{		
			foreach ($this->input->post('tags') as $tag)
			{
				echo $tag;
				echo '<br />';
			}
		}
		else
		{
			$data['form_attr'] = array(
				'id' => 'tagi_form',
			);

			$data['tag_1'] = array(
				'name' => 'tags[]',
				'id' => 'tag_1',
				'class' => 'span2',
				'value' => $this->form_validation->set_value('tags[0]', isset($tags['0']) ? $tags['0']['tag'] : ''),
			);

			$data['tag_2'] = array(
				'name' => 'tags[]',
				'id' => 'tag_2',
				'class' => 'span2',
				'value' => $this->form_validation->set_value('tags[1]', isset($tags['1']) ? $tags['1']['tag'] : ''),
			);

			$data['tag_3'] = array(
				'name' => 'tags[]',
				'id' => 'tag_3',
				'class' => 'span2',
				'value' => $this->form_validation->set_value('tags[2]', isset($tags['2']) ? $tags['2']['tag'] : ''),
			);
			
			$this->load->view('input_t', $data);
		}
	}	
	
	private function create_ses_stop18($value)
	{
		$value = intval($value);
		
		$this->session->set_userdata('stop18', $value);
		$this->stop18 = $value;
	}
	
	public function get_ses()
	{
		if ($this->session->userdata('stop18') === FALSE)
		{
			$this->create_ses_stop18(0);
			echo var_dump($this->stop18) . '- dana została utworzona.';
		}
		else
		{
			$this->stop18 = $this->session->userdata('stop18');
			echo var_dump($this->stop18) . '- dana sesyjna istniała.';
		}
	}
	
	public function set_ses($value = 0)
	{
		$this->create_ses_stop18($value);
	}
}