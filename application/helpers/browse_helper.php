<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('create_hierarchical_sub_cats'))
{
	function create_hierarchical_sub_cats($base_url, $sub_cats, $current_cat = NULL, $uri_segs = array())
	{
		$hierarchical_sub_cats = array();

		if (!empty($uri_segs))
		{
			for ($i = 0; $i <= count($uri_segs) - 1; $i++)
			{
				$base_url .= $uri_segs[$i]['short_name_cat'] . '/';
			}
		}

		for ($i = 0; $i <= count($sub_cats) - 1; $i++)
		{
			$hierarchical_sub_cats[$i]['path'] = $base_url . $sub_cats[$i]['short_name_cat'];
			$hierarchical_sub_cats[$i]['long_name_cat'] = $sub_cats[$i]['name_cat'];
			$hierarchical_sub_cats[$i]['current'] = $sub_cats[$i]['id'] === $current_cat ? TRUE : FALSE;
		}

		return $hierarchical_sub_cats;
	}
}

if ( ! function_exists('create_hierarchical_path'))
{
	function create_hierarchical_path($base_url, $uri_segs)
	{
		$hierarchical_path = array();

		for ($i = 0; $i <= count($uri_segs) - 1; $i++)
		{
			$hierarchical_path[$i] ['path'] =  $base_url . $uri_segs[$i]['short_name_cat'];
			$hierarchical_path[$i] ['long_name_cat'] = $uri_segs[$i]['name_cat'];

			$base_url .= $uri_segs[$i]['short_name_cat'] . '/';
		}

		return $hierarchical_path;
	}		
}

if ( ! function_exists('split_tags'))
{
	function split_tags($text)
	{
		// Remove any apostrophes or dashes which aren't part of words
		$text = substr(preg_replace('%((?<=[^\p{L}\p{N}])[\'\-]|[\'\-](?=[^\p{L}\p{N}]))%u', '', ' ' . $text . ' '), 1, -1);

		// Remove punctuation and symbols (actually anything that isn't a letter or number), allow apostrophes and dashes (and % * if we aren't indexing)
		$text = preg_replace('%(?![\'\-\%\*])[^\p{L}\p{N}]+%u', ' ', $text);

		// Replace multiple whitespace or dashes
		$text = preg_replace('%(\s){2,}%u', '\1', ($text));
				
		// Fill an array with all the words
		$tags = array_unique(explode(' ', trim($text)));

		foreach ($tags as $key => $tag)
		{
			if ($tag === '')
			{
				unset($tags[$key]);
			}
		}
		
		return $tags;
	}
}

if ( ! function_exists('exif_get_fraction'))
{
	function exif_get_fraction($value) 
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
}

if ( ! function_exists('get_exif_data'))
{
	function get_exif_data($imagePath)
	{
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
				$data['camExposure'] = $this->exif_get_fraction($exif_ifd0['ExposureTime']);
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
}

if ( ! function_exists('get_filter_param'))
{
	function get_filter_param($filter)
	{
		if (!($filter === FALSE))
		{
			$f = intval($filter);

			if ($f >= 10 && $f <= 14)
			{
				return $f;
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return 0;
		}
	}
}

if ( ! function_exists('get_sort_param'))
{
	function get_sort_param($sort)
	{
		if (!($sort === FALSE))
		{
			$s = $sort;

			if (($s === 'dd' || $s === 'ul' || $s === 'oc'))
			{
				return $s;
			}
			else
			{
				return 'dd';
			}
		}
		else
		{
			return 'dd';
		}
	}
}

if ( ! function_exists('get_search_tags'))
{
	function get_search_tags($search)
	{
		if ($search !== FALSE && !empty($search))
		{
			$result = split_tags($search);
		}
		else 
		{
			$result = array();
		}
		
		return $result;
	}
}

if ( ! function_exists('create_get_params_uri'))
{
	function create_get_params_uri($filter, $sort = 'dd', $search = '')
	{
		$get_uri = array();
		
		if ($filter != 0)
		{
			$get_uri[] = "filter={$filter}";
		}

		if ($sort != 'dd')
		{
			$get_uri[] = "sort={$sort}";
		}

		if (!empty($search))
		{
			$get_uri[] = "search={$search}";
		}
				
		return empty($get_uri) ? '' : '?' . implode('&', $get_uri);
	}
}