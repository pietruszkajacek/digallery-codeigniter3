<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Comments extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->config->load('digallery', TRUE);
		$this->load->database();
		$this->load->library(array('session','ion_auth', 'form_validation', 'typography'));
		$this->load->helper(array('url', 'html', 'urllinker'));
		$this->lang->load('digallery', 'polish');

		$this->load->model('comments_model');
		$this->load->model('browse_model');
		$this->load->model('evaluations_model');	
	}
	
	public function add_profile_comment($profile_user_id)
	{
		if ($this->input->is_ajax_request())
		{
			$profile_user_id = intval($profile_user_id);

			if ($this->ion_auth->logged_in())
			{
				$user = $this->browse_model->get_user($profile_user_id);

				if ($user === FALSE || !$user->profile_can_comment)
				{
					$this->output->set_status_header('404');
					return;
				}

				$logged_in_user = $this->ion_auth->user()->row();

				if (($this->input->post('comment')) && ($this->input->post('comment') != ''))
				{
					$comment = $this->input->post('comment');
					$user_id = $logged_in_user->id;
					$time = date('Y-m-d H:i:s');
					$type = 'profile';
					$rate = 0;
					
					if ($this->comments_model->add_comment($profile_user_id, $user_id, $comment, $time, $type))
					{
						$this->output
								->set_content_type('application/json')
								->set_output(json_encode(array("status" => 1)));

					}
					else
					{
						$this->output
								->set_content_type('application/json')
								->set_output(json_encode(array("status" => 0)));
					}
				}
			}
			else
			{
				$this->output->set_status_header('403');
			}
		}
	}

	public function add_gallery_comment($gallery_id)
	{
		if ($this->input->is_ajax_request())
		{
			$gallery_id = intval($gallery_id);

			if ($this->ion_auth->logged_in())
			{
				$gallery = $this->browse_model->get_gallery($gallery_id);

				if ($gallery === FALSE || !$gallery->can_comment)
				{
					$this->output->set_status_header('404');
					return;
				}

				$logged_in_user = $this->ion_auth->user()->row();

				if (($this->input->post('comment')) && ($this->input->post('comment') != ''))
				{
					$comment = $this->input->post('comment');
					$user_id = $logged_in_user->id;
					$time = date('Y-m-d H:i:s');
					$type = 'gallery';
					$rate = intval($this->input->post('rate'));
					$max_rate = count($this->config->item('name_of_ratings', 'digallery')) - 1;
					$rated_gallery = $this->evaluations_model->rated_gallery($gallery_id, $logged_in_user->id);
					
					if ($gallery->user_id === $logged_in_user->id || $rate <= 0 || $rate > $max_rate || $rated_gallery)
					{
						$rate = 0;
					}
					
					if ($this->comments_model->add_comment($gallery_id, $user_id, $comment, $time, $type, $rate))
					{
						$this->output
								->set_content_type('application/json')
								->set_output(json_encode(array("status" => 1)));

					}
					else
					{
						$this->output
								->set_content_type('application/json')
								->set_output(json_encode(array("status" => 0)));
					}
				}
			}
			else
			{
				$this->output->set_status_header('403');
			}
		}
	}
	
	public function add_image_comment($image_id)
	{
		if ($this->input->is_ajax_request())
		{
			$image_id = intval($image_id);

			if ($this->ion_auth->logged_in())
			{
				$image = $this->browse_model->get_image($image_id);

				if ($image === FALSE || !$image->can_comment)
				{
					$this->output->set_status_header('404');
					return;
				}

				$logged_in_user = $this->ion_auth->user()->row();

				if (($this->input->post('comment')) && ($this->input->post('comment') != ''))
				{
					$comment = $this->input->post('comment');
					$user_id = $logged_in_user->id;
					$time = date('Y-m-d H:i:s');
					$type = 'image';
					$rate = intval($this->input->post('rate'));
					$max_rate = count($this->config->item('name_of_ratings', 'digallery')) - 1;
					$rated_image = $this->evaluations_model->rated_image($image_id, $logged_in_user->id);
					
					if (!$image->can_evaluated || $image->user_id === $logged_in_user->id || $rate <= 0 || $rate > $max_rate || $rated_image)
					{
						$rate = 0;
					}
					
					if ($this->comments_model->add_comment($image_id, $user_id, $comment, $time, $type, $rate))
					{
						$this->output
								->set_content_type('application/json')
								->set_output(json_encode(array("status" => 1)));

					}
					else
					{
						$this->output
								->set_content_type('application/json')
								->set_output(json_encode(array("status" => 0)));
					}
				}
			}
			else
			{
				$this->output->set_status_header('403');
			}
		}
	}

	public function delete_comment($comment_id)
	{
		if ($this->input->is_ajax_request())
		{
			if ($this->ion_auth->logged_in())
			{
				$comment_id = intval($comment_id);

				$comment = $this->comments_model->get_comment($comment_id);

				if ($comment === FALSE)
				{
					$this->output->set_status_header('404');
					return;
				}
				
				switch ($comment->type)
				{
					case "image":
						$image = $this->browse_model->get_image($comment->object_id);
						$comment_object_owner = $image->user_id;
						break;
					case "gallery":
						$gallery = $this->browse_model->get_gallery($comment->object_id);
						$comment_object_owner = $gallery->user_id;
						break;
					default:
						$comment_object_owner = $comment->object_id;
				}

				$logged_in_user = $this->ion_auth->user()->row();

				if ($logged_in_user->id === $comment->user_id || $logged_in_user->id === $comment_object_owner)
				{				
					if ($this->comments_model->delete_comment($comment_id))
					{
						$this->output
							 ->set_content_type('application/json')
							 ->set_output(json_encode(array("status" => 1)));
					}
					else
					{
						$this->output
							 ->set_content_type('application/json')
							 ->set_output(json_encode(array("status" => 0)));
					}
				}
				else
				{
					$this->output->set_status_header('404');
				}
			}
			else
			{
				$this->output->set_status_header('403');
			}
		}
	}

	public function get_comment($comment_id)
	{
		if ($this->input->is_ajax_request())
		{
			$comment_id = intval($comment_id);

			if ($this->ion_auth->logged_in())
			{
				$comment = $this->comments_model->get_comment($comment_id);

				if ($comment === FALSE)
				{
					$this->output->set_status_header('404');
					return;
				}

				$logged_in_user = $this->ion_auth->user()->row();

				if ($logged_in_user->id === $comment->user_id)
				{
					$this->output
							->set_content_type('application/json')
							->set_output(json_encode(array("comment" => $comment->comment)));
				}
				else
				{
					$this->output->set_status_header('404');
				}
			}
			else
			{
				$this->output->set_status_header('403');
			}
		}
	}

	public function edit_comment($comment_id)
	{
		if ($this->input->is_ajax_request())
		{
			$comment_id = intval($comment_id);

			if ($this->ion_auth->logged_in())
			{
				$old_comment = $this->comments_model->get_comment($comment_id);

				if ($old_comment === FALSE)
				{
					$this->output->set_status_header('404');
					return;
				}

				$logged_in_user = $this->ion_auth->user()->row();

				if ($logged_in_user->id === $old_comment->user_id)
				{
					if (($this->input->post('comment')) && ($this->input->post('comment') != ''))
					{
						if ($old_comment->comment !== $this->input->post('comment'))
						{
							if ($this->comments_model->update_comment($comment_id, $this->input->post('comment')))
							{
								$updated_comment = $this->comments_model->get_comment($comment_id);
								$typography_comment = $this->typography->auto_typography(htmlEscapeAndLinkUrls($updated_comment->comment), TRUE);
								//$typography_comment = nl2br($this->typography->format_characters(htmlEscapeAndLinkUrls($updated_comment->comment)));

								$this->output
										->set_content_type('application/json')
										->set_output(json_encode(array("status" => 1, "comment" => $typography_comment, "last_edit" => $updated_comment->last_edit)));
							}
							else
							{
								$this->output
										->set_content_type('application/json')
										->set_output(json_encode(array("status" => 0)));
							}
						}
					}
				}
			}
			else
			{
				$this->output->set_status_header('403');
			}
		}
	}
}
/* End of file comments.php */
/* Location: ./application/controllers/comments.php */
