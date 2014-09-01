<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Evaluations extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->config->load('digicomm', TRUE);
		$this->load->database();
		$this->load->driver('session');
		$this->load->library(array('ion_auth', 'form_validation'));
		$this->load->helper(array('url', 'html'));
		$this->lang->load('dc', 'polish');

		$this->load->model('evaluations_model');
		$this->load->model('browse_model');
	}

	public function _rate_valid($rate)
    {
        if ($this->input->post('evaluation_rate'))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }		
	}
	
	public function rated_image($image_id, $user_id)
	{
		if ($this->input->is_ajax_request())
		{
			$image_id = intval($image_id);

			if ($this->ion_auth->logged_in())
			{
				$image = $this->browse_model->get_image($image_id);

				if ($image === FALSE || !$image->can_evaluated)
				{
					$this->output->set_status_header('500');
					return;
				}

				$logged_in_user = $this->ion_auth->user()->row();

							
				
				if (($this->input->post('evaluation_rate')) && ($this->input->post('evaluation_rate') != ''))
				{
					$data = array(
						'comment' => $this->input->post('comment'),
						'object_id' => $image_id,
						'user_id' => $logged_in_user->id,
						'time' => date('Y-m-d H:i:s')
					);

					if ($this->comments_model->add_image_comment($data))
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
				$this->output->set_status_header('401');
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
					$this->output->set_status_header('500');
					return;
				}

				if ($this->session->userdata('user_id') == $comment->user_id)
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
					$this->output->set_status_header('500');
				}
			}
			else
			{
				$this->output->set_status_header('401');
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
					$this->output->set_status_header('500');
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
					$this->output->set_status_header('500');
				}
			}
			else
			{
				$this->output->set_status_header('401');
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
					$this->output->set_status_header('500');
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
				$this->output->set_status_header('401');
			}
		}
	}
}
/* End of file evaluations.php */
/* Location: ./application/controllers/evaluations.php */
