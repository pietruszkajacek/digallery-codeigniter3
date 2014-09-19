<?php

require(APPPATH . '/libraries/REST_Controller.php');

class Rests extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();


	}

	function user_get()
    {
        $data = array('returned: '. $this->get('id') . ' / ' . $this->get('param') . ' / ' . $this->get('pol'));
        $this->response($data);
    }
}
