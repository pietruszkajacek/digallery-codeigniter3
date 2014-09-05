<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller
{
	/**
	 *  konstruktor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		
	}

	function _check_math_captcha($str)
	{
		if ($this->mathcaptcha->check_answer($str)) // sprawdzamy, czy podana przez nas odpowiedź jest prawidłowa
		{
			return TRUE; // jeśli tak zwracamy wartość TRUE
		}
		else
		{
			// jeśli nie, ustawiamy wiadomość błędu dla pola formularza
			$this->form_validation->set_message('_check_math_captcha', 'Musisz podać poprawny wynik działania.');
			// i zwracamy wartość FALSE
			return FALSE;
		}
	}

	/**
	 * rejestracja nowego użytkownika ajax (strona startowa)
	 */
	public function register_ajax()
	{	
		if ($this->input->is_ajax_request())
		{
			$this->load->helper('common');
			
			if ($this->ion_auth->logged_in())
			{
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode(array("status" => "1")));
			}
			else
			{
				if (reverse_valid_csrf_nonce())
				{
					//reguły walidacji formularza logowania
					$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
					$this->form_validation->set_rules('password', 'Hasło', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length['
						. $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
					$this->form_validation->set_rules('password_confirm', 'Powtórzenie hasła', 'required');
					
					//walidacja danych formularza rejestracji nowego uzytkownika
					if ($this->form_validation->run())
					{
						//walidacja przeszła pomyślnie
						$username = strtolower($this->input->post('email'));
						$email = $this->input->post('email');
						$password = $this->input->post('password');
					}

					//próba rejestracji nowego użytkownika
					if ($this->form_validation->run() && $this->ion_auth->register($username, $password, $email))
					{
						//rejestracja powiodła się
						$this->session->set_flashdata(array('type' => 'info', 'msg' => 'Konto zostało utworzone. Link aktywacyjny został wysłany na podany adres email.'));
						$this->output
								->set_content_type('application/json')
								->set_output(json_encode(array("status" => "1")));
					}
					else
					{
						$this->output
								->set_content_type('application/json')
								->set_output(json_encode(array(
									"status" => "0", 
									"error_type" => ($this->ion_auth->errors()) ? 'register' : 'valid', 
									"msg" => ($this->ion_auth->errors()) ? $this->ion_auth->errors() : validation_errors('', '<br />'),
									"csrf_key"  => get_csrf_nonce()
								)));
					}
				}
				else
				{
					show_404();
				}
			}
		}
		else
		{
			
		}
	}
	
	/**
	 * rejestracja nowego użytkownika
	 */
	public function register()
	{
		//jeżeli użytkownik jest zalogowany to nastąpi przekierowanie na stronę bazową
		if ($this->ion_auth->logged_in())
		{
			redirect($this->config->item('base_url'), 'refresh');
		}

		$this->load->library('mathcaptcha');
		 
		$config = array(
			'language' => 'polish',
			'operation' => 'random',
			'question_format' => 'random',
			'answer_format' => 'either'
		);
		
		$this->mathcaptcha->init($config); // inicjujemy bibliotekę z powyższą konfiguracją
		
		$this->data['math_captcha_question'] = $this->mathcaptcha->get_question();
		
		//reguły walidacji formularza logowania
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Hasło', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length['
				. $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', 'Powtórzenie hasła', 'required');
		$this->form_validation->set_rules('math_captcha', 'Wynik działania', 'required|callback__check_math_captcha'); 
		
		
		//walidacja danych formularza rejestracji nowego uzytkownika
		if ($this->form_validation->run())
		{
			//walidacja przeszła pomyślnie
			$username = strtolower($this->input->post('email'));
			$email = $this->input->post('email');
			$password = $this->input->post('password');
		}

		//próba rejestracji nowego użytkownika
		if ($this->form_validation->run() && $this->ion_auth->register($username, $password, $email))
		{
			//rejestracja powiodła się
			//...wiadomość dla użytkownika o udanej rejestracji
			//...i przekierowanie na stronę bazową
			$this->session->set_flashdata(array('type' => 'info', 'msg' => 'Konto zostało utworzone. Link aktywacyjny został wysłany na podany adres email.'));
			redirect('/browse/images/', 'refresh');
		}
		else
		{
			//walidacja danych lub rejestracja nowego użytkownika nie powiodła się
			//gdy wystąpiły błędy podczas rejestracji przekaż je użytkownikowi
			//wyświetl formularz rejestracji
			$this->data['message'] = $this->ion_auth->errors() ? array('type' => 'error', 'msg' => $this->ion_auth->errors()) :
					array('type' => $this->session->flashdata('type'), 'msg' => $this->session->flashdata('msg'));

			//atrybuty formularza logowania
			$this->data['form_attr'] = array(
				'class' => 'form-horizontal',
				'id' => 'register-form'
			);

			$this->data['fieldset'] = array(
			);

			//dane pola email...
			$this->data['email'] = array(
				'name' => 'email',
				'id' => 'email',
				'class' => 'input-xlarge',
				'type' => 'text',
				'value' => $this->form_validation->set_value('email'), //przypisz wartość poprzedniego wywołania
			);

			//dane etykiety pola email
			$this->data['email_label'] = array(
				'for' => 'email',
				'text' => 'Email',
				'attributes' => array(
					'class' => 'control-label',
				),
			);

			//dane pola password...
			$this->data['password'] = array(
				'name' => 'password',
				'id' => 'password',
				'class' => 'input-xlarge',
				'type' => 'password',
			);

			//dane etykiety pola password
			$this->data['password_label'] = array(
				'for' => 'password',
				'text' => 'Hasło',
				'attributes' => array(
					'class' => 'control-label',
				),
			);

			//dane pola password_confirm ...
			$this->data['password_confirm'] = array(
				'name' => 'password_confirm',
				'id' => 'password_confirm',
				'class' => 'input-xlarge',
				'type' => 'password',
			);

			//dane etykiety pola confirm_password
			$this->data['password_confirm_label'] = array(
				'for' => 'password_confrm',
				'text' => 'Powtórz hasło',
				'attributes' => array(
					'class' => 'control-label',
				),
			);

			//dane pola math_captcha ...
			$this->data['math_captcha'] = array(
				'name' => 'math_captcha',
				'id' => 'math_captcha',
				'class' => 'input-mini',
				'type' => 'text',
			);

			//dane etykiety pola math_captcha
			$this->data['math_captcha_label'] = array(
				'for' => 'math_captcha',
				'text' => 'Wynik działania',
				'attributes' => array(
					'class' => 'control-label',
				),
			);			
			
			$this->data['control_groups'] = array(
				'email' => form_error('email') ? ' error' : '',
				'password' => form_error('password') ? ' error' : '',
				'password_confirm' => form_error('password_confirm') ? ' error' : '',
				'math_captcha' => form_error('math_captcha') ? ' error' : '',
			);
			
			$this->render();
		}
	}

	/**
	 * aktywacja użytkownika
	 *
	 * @param type $id
	 * @param type $code
	 */
	function activate($id, $code = false)
	{
		if ($code !== false)
		{
			$activation = $this->ion_auth->activate($id, $code);
		}
		else if ($this->ion_auth->is_admin())
		{
			$activation = $this->ion_auth->activate($id);
		}

		if ($activation)
		{
			$this->session->set_flashdata(array('type' => 'info', 'msg' => $this->ion_auth->messages() . ' Możesz zalogować się do portalu.'));
			redirect('user/login', 'refresh');
		}
		else
		{
			$this->session->set_flashdata(array('type' => 'error', 'msg' => $this->ion_auth->errors()));
			redirect('user/forgot_password', 'refresh');
		}
	}

	/**
	 * logowanie użytkownika
	 *
	 */
	public function login()
	{
		//jesli użytkownik jest zalogowany to przekieruj na bazowy adres
		if ($this->ion_auth->logged_in())
		{
			if ($this->input->is_ajax_request())
			{
				$this->output
						->set_content_type('application/json')
						->set_output(json_encode(array("status" => "1")));
			}
			else
			{
				redirect($this->config->item('base_url'), 'refresh');
			}
		}

		//reguły walidacji formularza logowania
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Hasło', 'required');

		//walidacja danych formularza logowania
		if ($this->form_validation->run() == TRUE)
		{
			//walidacja przebiegła pomyślnie
			//checkbox zapamiętaj mnie do zmiennej...
			$remember = (bool) $this->input->post('remember');

			//próba zalogowania - przekazanie emaila, hasła i pola zapamiętaj mnie
			if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'), $remember))
			{
				if ($this->input->is_ajax_request())
				{
					$this->output
							->set_content_type('application/json')
							->set_output(json_encode(array("status" => "1")));
				}
				else
				{
					//logowanie przebiegło pomyślnie przekaż wiadomość użytkownikowi o udanym logowaniu...
					$this->session->set_flashdata(array('type' => 'info', 'msg' => $this->ion_auth->messages()));
					//...i przekieruj na bazowy adres
					redirect($this->config->item('base_url'), 'refresh');
				}
			}
			//próba logowania nie udana
			else
			{
				if ($this->input->is_ajax_request())
				{
					$this->output
							->set_content_type('application/json')
							->set_output(json_encode(array("status" => "0", "error_type" => "login", "msg" => $this->ion_auth->errors())));
				}
				else
				{
					//logowanie nie przebiegło pomyślnie przekaż wiadomość dla użytkownika o nieudanej próbie logowania...
					$this->session->set_flashdata(array('type' => 'error', 'msg' => $this->ion_auth->errors()));
					//...i przekieruj ponownie na stronę logowania
					redirect('user/login/', 'refresh');
				}
			}
		}
		else
		{
			//walidacja przebiegła niepomyślnie
			if ($this->input->is_ajax_request())
			{
				$this->output
						->set_content_type('application/json')
						->set_output(json_encode(array("status" => "0", "error_type" => "valid", "msg" => validation_errors('', '<br />'))));
			}
			else
			{
				$this->data['message'] = array(
					'type' => $this->session->flashdata('type'),
					'msg' => $this->session->flashdata('msg'),
				);

				//atrybuty formularza logowania
				$this->data['form_attr'] = array(
					'class' => 'form-horizontal',
					'id' => 'login-form'
				);

				$this->data['fieldset'] = array(
				);

				//dane pola email...
				$this->data['email'] = array(
					'name' => 'email',
					'id' => 'email',
					'class' => 'input-xlarge',
					'type' => 'text',
					'value' => $this->form_validation->set_value('email'), //przypisz wartość poprzedniego wywołania
				);

				//dane etykiety pola email
				$this->data['email_label'] = array(
					'for' => 'email',
					'text' => 'Email',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				//dane pola hasło...
				$this->data['password'] = array(
					'name' => 'password',
					'id' => 'password',
					'class' => 'input-xlarge',
					'type' => 'password',
				);

				//dane etykiety pola password
				$this->data['password_label'] = array(
					'for' => 'password',
					'text' => 'Hasło',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				//dane pola zapamiętaj mnie...
				$remember = array(
					'name' => 'remember',
					'id' => 'remember',
					'value' => '1',
					'checked' => (bool) $this->input->post('remember') ? TRUE : FALSE,
				);

				$remember_checkbox = form_checkbox($remember);
				
				//dane etykiety pola zapamiętaj mnie...
				$this->data['remember_label'] = array(
					'for' => 'remember',
					'text' => $remember_checkbox . 'Zapamiętaj mnie',
					'attributes' => array(
						'class' => 'checkbox',
					),
				);

				$this->data['control_groups'] = array(
					'email' => form_error('email') ? ' error' : '',
					'password' => form_error('password') ? ' error' : '',
				);

				$this->render();
			}
		}
	}

	public function _captcha_valid($captcha, $expiration)
	{
		// sprawdzamy czy captcha wystepuje w bazie i czy jest jeszcze aktualna
		$sql = "SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
		$binds = array($captcha, $this->input->ip_address(), $expiration);
		$query = $this->db->query($sql, $binds);
		$row = $query->row();

		if ($row->count == 0)
		{
			$this->form_validation->set_message('_captcha_valid', 'Nieprawidłowy tekst captchy.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function _email_exist($email)
	{
		if ($this->ion_auth->email_check($email))
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('_email_exist', 'Podany adres email nie wystepuje.');
			return FALSE;
		}
	}

	public function _captcha($expiration)
	{
		$vals = array(
			'img_path' => './captcha/',
			'img_url' => $this->config->item('base_url') . '/captcha/',
			'img_width' => 280,
			'img_height' => 30,
			'expiration' => $expiration,
		);

		$cap = create_captcha($vals);

		$data = array(
			'captcha_time' => $cap['time'],
			'ip_address' => $this->input->ip_address(),
			'word' => $cap['word']
		);

		$query = $this->db->insert_string('captcha', $data);
		$this->db->query($query);

		// usunięcie przeterminowanych captch
		$this->db->query("DELETE FROM captcha WHERE captcha_time < " . $expiration);

		return $cap;
	}

	public function forgot_password()
	{
		$this->load->helper('captcha');

		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__email_exist');

		if ($this->form_validation->run())
		{
			// email został znaleziony w bazie...
			// ustaw wazność czasu captchy na 30 sek.
			$expiration_captcha = time() - 30;

			if (isset($_POST['captcha']))
			{
				// reguły dla captchy
				$this->form_validation->set_rules('captcha', 'Captcha', 'required|callback__captcha_valid[' . $expiration_captcha . ']');

				// sprawdź występowanie i poprawność kodu captchy
				if ($this->form_validation->run())
				{
					$forgotten = $this->ion_auth->forgotten_password($this->input->post('email'));

					if ($forgotten)
					{
						//if there were no errors
						$this->session->set_flashdata(array('type' => 'info', 'msg' => $this->ion_auth->messages()));
						redirect('user/login', 'refresh');
					}
					else
					{
						$this->session->set_flashdata(array('type' => 'error', 'msg' => $this->ion_auth->errors()));
						redirect('user/forgot_password', 'refresh');
					}
				}
			}

			$this->data['email_OK'] = TRUE;

			//dane ukrytego pola email...
			$this->data['email'] = array(
				'email' => $this->form_validation->set_value('email'),
			);

			$this->data['captcha'] = array(
				'name' => 'captcha',
				'id' => 'captcha',
				'class' => 'input-xlarge',
				'type' => 'text',
			);

			//dane etykiety pola captcha
			$this->data['captcha_label'] = array(
				'for' => 'captcha',
				'text' => 'Captcha',
				'attributes' => array(
					'class' => 'control-label',
				),
			);

			$cap = $this->_captcha($expiration_captcha);
			$this->data['cap_image'] = $cap['image'];
		}
		else
		{
			// nie znaleziono emaila w bazie...
			//dane pola email...
			$this->data['email'] = array(
				'name' => 'email',
				'id' => 'email',
				'class' => 'input-xlarge',
				'type' => 'text',
					//'value' => $this->form_validation->set_value('email'), //przypisz wartość poprzedniego wywołania
			);

			//dane etykiety pola email
			$this->data['email_label'] = array(
				'for' => 'email',
				'text' => 'Email',
				'attributes' => array(
					'class' => 'control-label',
				),
			);
		}

		$this->data['message'] = array(
			'type' => $this->session->flashdata('type'),
			'msg' => $this->session->flashdata('msg'),
		);

		$this->data['form_attr'] = array(
			'class' => 'form-horizontal',
			'id' => 'forgot-password-form',
		);

		$this->data['control_groups'] = array(
			'email' => form_error('email') ? ' error' : '',
			'captcha' => form_error('captcha') ? ' error' : '',
		);

		$this->render();
	}

	/**
	 * Końcowy etap zmiany hasła użytkownika
	 *
	 * @param type $code
	 */
	public function reset_password($code = 0)
	{
		if (!$code)
		{
			show_404();
		}

		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			// jeśli kod jest prawidłowy
			$this->form_validation->set_rules('new', 'Nowe hasło', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth')
					. ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', 'Potwierdzenie nowego hasła', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				// wyświetl formularz
				$this->data['message'] = array(
					'type' => $this->session->flashdata('type'),
					'msg' => $this->session->flashdata('msg'),
				);

				$this->data['form_attr'] = array(
					'class' => 'form-horizontal',
					'id' => 'reset-password-form',
				);

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');

				$this->data['new'] = array(
					'name' => 'new',
					'id' => 'new',
					'type' => 'password',
					'class' => 'input-xlarge',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				);

				//dane etykiety pola new
				$this->data['new_label'] = array(
					'for' => 'new',
					'text' => 'Hasło',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['new_confirm'] = array(
					'name' => 'new_confirm',
					'id' => 'new_confirm',
					'type' => 'password',
					'class' => 'input-xlarge',
					'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
				);

				//dane etykiety pola new_confirm
				$this->data['new_confirm_label'] = array(
					'for' => 'new_confirm',
					'text' => 'Powtórz hasło',
					'attributes' => array(
						'class' => 'control-label',
					),
				);

				$this->data['user_id'] = array(
					'name' => 'user_id',
					'id' => 'user_id',
					'type' => 'hidden',
					'value' => $user->id,
				);

				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				$this->data['control_groups'] = array(
					'new' => form_error('new') ? ' error' : '',
					'new_confirm' => form_error('new_confirm') ? ' error' : '',
				);

				//render
				$this->render();
			}
			else
			{
				// czy prawidłowe żądanie?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{
					// coś podejrzanego mogło mieć miejsce
					$this->ion_auth->clear_forgotten_password_code($code);

					show_404();
				}
				else
				{
					// zmiana hasła...
					$identity = $user->{$this->config->item('identity', 'ion_auth')};

					$change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						// jeśli udało zmienić się hasło
						$this->session->set_flashdata(array('type' => 'info', 'msg' => $this->ion_auth->messages()));

						//przekierowanie na stronę logowania
						redirect('user/login/', 'refresh');
					}
					else
					{
						$this->session->set_flashdata(array('type' => 'error', 'msg' => $this->ion_auth->errors()));
						redirect('user/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			// jeśli kod jest nieprawidłowy przekieruj na stronę resetu hasła
			$this->session->set_flashdata(array('type' => 'error', 'msg' => $this->ion_auth->errors()));
			redirect("user/forgot_password", 'refresh');
		}
	}

	/**
	 * Wylogowanie
	 *
	 */
	public function logout()
	{
		//wylogowanie użytkownika
		$logout = $this->ion_auth->logout();

		$this->session->set_flashdata(array('type' => 'info', 'msg' => $this->ion_auth->messages()));	
		
		//przekierowanie na główną stronę
		redirect('', 'refresh');
	}

	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if (!is_null($this->input->post($this->session->flashdata('csrfkey'))) &&
				$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */
