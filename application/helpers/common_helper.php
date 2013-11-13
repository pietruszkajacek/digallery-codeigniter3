<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_csrf_nonce'))
{
	function get_csrf_nonce()
	{
		$CI =& get_instance();
		
		$CI->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$CI->session->set_flashdata('csrfkey', $key);
		$CI->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);		
	}
}

if ( ! function_exists('valid_csrf_nonce'))
{
	function valid_csrf_nonce()
	{
		$CI =& get_instance();
		
		if ($CI->input->post($CI->session->flashdata('csrfkey')) !== FALSE &&
				$CI->input->post($CI->session->flashdata('csrfkey')) == $CI->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}		
}

if ( ! function_exists('reverse_valid_csrf_nonce'))
{
	function reverse_valid_csrf_nonce()
	{
		$CI =& get_instance();
		
		if ($CI->input->post($CI->session->flashdata('csrfvalue')) !== FALSE &&
				$CI->input->post($CI->session->flashdata('csrfvalue')) == $CI->session->flashdata('csrfkey'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}		
}