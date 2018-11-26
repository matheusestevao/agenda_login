<?php

class ContatoController extends MainController
{

	public $db;
	public $formMsg;
	public $formData;

	public function index ()
	{

		$this->title = 'Contato';

		$parameters = (func_num_args() >= 1) ? func_get_arg(0) : array();

		$model = $this->loadModel('contato-model');

		require BASEDIR.'/views/dashboard/index.php';


	}


}