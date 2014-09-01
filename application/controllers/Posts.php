<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Posts extends MY_Controller
{
	const page_size = 10;

	public function __construct()
	{
		parent::__construct();

		$this->load->library('ion_auth');
		$this->load->driver('session');
		$this->load->library('form_validation');
		$this->load->library('pagination');
		$this->load->database();
		$this->load->helper(array('url', 'html'));
		$this->lang->load('digallery', 'polish');

		$this->load->model('posts_model');
	}

	public function _user_exist($user_email)
	{
		if ($this->ion_auth->identity_check($user_email))
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('_user_exist', 'Nie ma użytkownika o podanym adresie email.');
			return FALSE;
		}
	}

	public function show_post_out($post_id = 0)
	{
		$this->load->library('typography');
		$this->load->helper('urllinker');
		
		if ($this->ion_auth->logged_in())
		{
			$user = $this->ion_auth->user()->row();

			if (($post = $this->posts_model->get_post('outbox', $post_id, $user->id)) === FALSE)
			{
				$this->session->set_flashdata(array('type' => 'error', 'msg' => 'Wiadomość nie istnieje lub nie jesteś jej autorem...'));
				redirect('posts/outbox', 'refresh');
			}

			$user_to = $this->ion_auth->user($post->user_id_to)->row();

			if (empty($user_to))
			{
				//@todo
				// Zastanowic sie co robic gdy user nie wystepuje już w bazie? Moze jakis statyczny ANONIM.
				$this->session->set_flashdata(array('type' => 'error', 'msg' => 'Adresat nie istnieje...'));
				redirect('posts/inbox', 'refresh');
			}

			$this->data['message'] = array(
				'type' => $this->session->flashdata('type'),
				'msg' => $this->session->flashdata('msg'),
			);

			$this->data['form_attr'] = array(
				'id' => 'posts-message-outbox',
				'class' => 'form-horizontal',
			);

			$post->message = $this->typography->auto_typography(htmlEscapeAndLinkUrls($post->message), TRUE);
			$this->data['post'] = $post;			
			
			$this->data['user_to'] = $user_to;
			$this->data['user_from'] = $user;

			// hidden input
			$this->data['hidden_post_id'] = array(
				'posts[]' => $post->id,
			);

			$this->render();
		}
		else
		{
			redirect('user/login');
		}
	}

	public function show_post_in($post_id = 0)
	{
		$this->load->library('typography');
		$this->load->helper('urllinker');
		
		if ($this->ion_auth->logged_in())
		{
			$user = $this->ion_auth->user()->row();

			if (($post = $this->posts_model->get_post('inbox', $post_id, $user->id)) === FALSE)
			{
				$this->session->set_flashdata(array('type' => 'error', 'msg' => 'Wiadomość nie istnieje lub nie jesteś jej adresatem...'));
				redirect('posts/inbox', 'refresh');
			}

			$user_to = $this->ion_auth->user($post->user_id_from)->row();

			if (empty($user_to))
			{
				//@todo
				// Zastanowic sie co robic gdy user nie wystepuje już w bazie? Moze jakis statyczny ANONIM.
				// Pamiętać należy by wyłączyć możliwość odpowiedzi do takiego użytkownika...
				$this->session->set_flashdata(array('type' => 'error', 'msg' => 'Adresat nie istnieje...'));
				redirect('posts/inbox', 'refresh');
			}

			$this->form_validation->set_error_delimiters('', '');

			$this->form_validation->set_rules('subject', 'Tytuł', 'required|xss_clean');
			$this->form_validation->set_rules('post_message', 'Wiadomość', 'required|xss_clean');

			if ($this->form_validation->run() == TRUE)
			{
				if ($this->posts_model->send_post($user->id, $user_to->id, $this->input->post('subject'), date('Y-m-d H:i:s'), $this->input->post('post_message')))
				{
					$this->session->set_flashdata(array('type' => 'info', 'msg' => 'Wiadomość została wysłana...'));
					redirect('posts/inbox/', 'refresh');
				}
				else
				{
					$this->session->set_flashdata(array('type' => 'error', 'msg' => 'Nie udało się wysłać wiadomości...'));
					redirect('posts/inbox/', 'refresh');
				}
			}
			else
			{
				$this->data['message'] = array(
					'type' => $this->session->flashdata('type'),
					'msg' => $this->session->flashdata('msg'),
				);

				$this->data['form_attr'] = array(
					'id' => 'posts-message-inbox',
					'class' => 'form-horizontal',
				);

				$this->data['subject'] = array(
					'name' => 'subject',
					'id' => 'subject',
					'class' => 'span7',
					'type' => 'text',
					'value' => $this->form_validation->set_value('subject', '[RE]: ' . $post->subject),
				);

				$this->data['subject_label'] = array(
					'for' => 'subject',
					'text' => 'Temat:',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['post_message'] = array(
					'name' => 'post_message',
					'id' => 'post_message',
					'class' => 'span7',
					'rows' => '6',
					'value' => $this->form_validation->set_value('post_message'),
				);

				$this->data['post_label'] = array(
					'for' => 'post',
					'text' => 'Treść wiadomości:',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['del_post_form_attr'] = array(
					'id' => 'posts-message-inbox-delete',
				);

				// hidden input
				$this->data['hidden_post_id'] = array(
					'posts[]' => $post->id,
				);

				$this->data['control_groups'] = array(
					'subject' => form_error('subject') ? ' error' : '',
					'post_message' => form_error('post_message') ? ' error' : '',
				);

				$post->message = $this->typography->auto_typography(htmlEscapeAndLinkUrls($post->message), TRUE);
				$this->data['post'] = $post;
				
				$this->data['user_from'] = $user_to;

				$this->data['js'] = 'show_post_in.js';

				$this->render();
			}
		}
		else
		{
			redirect('user/login');
		}
	}

	public function compose_autocomplete()
	{
		if ($this->input->is_ajax_request())
		{
			if ($this->ion_auth->logged_in())
			{
				$typeahead  = $this->input->post('typeahead');

				$this->output
						->set_content_type('application/json')
						->set_output(json_encode($this->posts_model->get_recipients($typeahead)));
				
			}
			else
			{
				$this->output->set_status_header('403');
			}
		}		
	}
	
	public function compose()
	{
		if ($this->ion_auth->logged_in())
		{
			$user = $this->ion_auth->user()->row();

			$this->form_validation->set_error_delimiters('', '');

			$this->form_validation->set_rules('recipient', 'Adresat', 'required|valid_email|callback__user_exist|xss_clean');
			$this->form_validation->set_rules('subject', 'Temat', 'required|xss_clean');
			$this->form_validation->set_rules('post_message', 'Wiadomość', 'required|xss_clean');

			if ($this->form_validation->run() == TRUE)
			{
				$user_to = $this->db->get_where('users', array('email' => $this->input->post('recipient')))->row();

				if ($this->posts_model->send_post($user->id, $user_to->id, $this->input->post('subject'), date('Y-m-d H:i:s'), $this->input->post('post_message')))
				{
					$this->session->set_flashdata(array('type' => 'info', 'msg' => 'Wiadomość została wysłana...'));
					redirect('posts/inbox/', 'refresh');
				}
				else
				{
					$this->session->set_flashdata(array('type' => 'error', 'msg' => 'Nie udało się wysłać wiadomości...'));
					redirect('posts/inbox/', 'refresh');
				}
			}
			else
			{
				$this->data['message'] = array(
					'type' => $this->session->flashdata('type'),
					'msg' => $this->session->flashdata('msg'),
				);

				$this->data['form_attr'] = array(
					'id' => 'posts-compose-message',
					'class' => 'form-horizontal',
				);

				$this->data['recipient'] = array(
					'name' => 'recipient',
					'id' => 'recipient',
					'class' => 'span7',
					'type' => 'text',
					'value' => $this->form_validation->set_value('recipient'),
				);

				$this->data['recipient_label'] = array(
					'for' => 'recipient',
					'text' => 'Adresat:',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['subject'] = array(
					'name' => 'subject',
					'id' => 'subject',
					'class' => 'span7',
					'type' => 'text',
					'value' => $this->form_validation->set_value('subject'),
				);

				$this->data['subject_label'] = array(
					'for' => 'subject',
					'text' => 'Temat:',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['post_message'] = array(
					'name' => 'post_message',
					'id' => 'post_message',
					'class' => 'span7',
					'rows' => '6',
					'value' => $this->form_validation->set_value('post_message'),
				);

				$this->data['post_label'] = array(
					'for' => 'post',
					'text' => 'Treść wiadomości:',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['control_groups'] = array(
					'recipient' => form_error('recipient') ? ' error' : '',
					'subject' => form_error('subject') ? ' error' : '',
					'post_message' => form_error('post_message') ? ' error' : '',
				);

				$this->data['js'] = 'posts.js';

				$this->render();
			}
		}
		else
		{
			redirect('user/login');
		}
	}

	private function current_page()
	{
		$current_page = (int) $this->uri->segment(3);

		if ($current_page == 0)
		{
			$current_page = 1;
		}
		elseif ($current_page < 0)
		{
			$current_page = FALSE;
		}

		return $current_page;
	}

	public function pagination_links($url, $total_posts)
	{
		$config['base_url'] = base_url() . $url;
		$config['use_page_numbers'] = TRUE;
		$config['num_links'] = 2;
		$config['per_page'] = self::page_size;

		$config['total_rows'] = $total_posts;

		$config['full_tag_open'] = '<ul>';
		$config['full_tag_close'] = '</ul>';

		$config['first_link'] = 'Pierw.';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';

		$config['last_link'] = 'Ostat.';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		$config['next_link'] = '»';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '«';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';

		$config['cur_tag_open'] = '<li class="active"><a href"#">';
		$config['cur_tag_close'] = '</a></li>';

		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);

		return $this->pagination->create_links();
	}

	public function outbox()
	{
		if ($this->ion_auth->logged_in())
		{
			$user = $this->ion_auth->user()->row();

			if (($current_page = $this->current_page()) === FALSE)
			{
				show_error("Strona nie występuje...", 500, 'Błąd!');
			}

			if ($this->input->post('posts'))
			{
				$this->posts_model->delete_posts('outbox', $this->input->post('posts'), $user->id);
			}

			$all_posts = $this->posts_model->counts_posts('outbox', $user->id);

			if ($all_posts > 0)
			{
				$last_page = ceil($all_posts / self::page_size);

				if ($current_page > $last_page)
				{
					$current_page = $last_page;
				}

				$this->data['posts'] = $this->posts_model->get_posts('outbox', $user->id, self::page_size, $current_page);
				$this->data['offset'] = ($current_page - 1) * self::page_size;
				$this->data['current_page'] = $current_page;
			}
			else
			{
				$this->data['posts'] = array();
				$this->data['current_page'] = '';
			}

			$this->data['js'] = 'posts.js';

			$this->data['form_attr'] = array(
				'id' => 'posts-box',
			);

			$this->data['message'] = array(
				'type' => $this->session->flashdata('type'),
				'msg' => $this->session->flashdata('msg'),
			);

			$this->data['pagination_links'] = $this->pagination_links('/posts/outbox', $all_posts);
			$this->render();
		}
		else
		{
			redirect('user/login');
		}
	}

	public function inbox()
	{
		if ($this->ion_auth->logged_in())
		{
			$user = $this->ion_auth->user()->row();

			if (($current_page = $this->current_page()) === FALSE)
			{
				show_error("Strona nie występuje...", 500, 'Błąd!');
			}

			if ($this->input->post('posts'))
			{
				$this->posts_model->delete_posts('inbox', $this->input->post('posts'), $user->id);
			}

			$all_posts = $this->posts_model->counts_posts('inbox', $user->id);

			if ($all_posts > 0)
			{
				$last_page = ceil($all_posts / self::page_size);

				if ($current_page > $last_page)
				{
					$current_page = $last_page;
				}

				$this->data['posts'] = $this->posts_model->get_posts('inbox', $user->id, self::page_size, $current_page);
				$this->data['offset'] = ($current_page - 1) * self::page_size;
				$this->data['current_page'] = $current_page;
			}
			else
			{
				$this->data['posts'] = array();
				$this->data['current_page'] = '';
			}

			$this->data['js'] = 'posts.js';

			$this->data['form_attr'] = array(
				'id' => 'posts-box',
			);

			$this->data['message'] = array(
				'type' => $this->session->flashdata('type'),
				'msg' => $this->session->flashdata('msg'),
			);

			$this->data['pagination_links'] = $this->pagination_links('/posts/inbox', $all_posts);
			$this->render();
		}
		else
		{
			redirect('user/login');
		}
	}
}

/* End of file posts.php */
/* Location: ./application/controllers/posts.php */