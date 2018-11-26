<?php

class LoginController extends MainController
{

	public $formMsg;

	public function index ()
	{
		
		$this->title = 'Login';
		$this->parameters = (func_num_args() >= 1) ? func_get_arg(0) : array();

		require_once BASEDIR.'/views/login/login-view.php';

	}


}