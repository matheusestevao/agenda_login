<?php

/**
*ALL CONTROLLER EXTENDS OF THAT CLASS 
*/
class MainController extends UserLogin
{

	public $db;
	public $phpass;
	public $title;
	public $loginRequired = false;
	public $permissionRequired = 'any';
	public $parameters = array();
	
	public function __construct($parameters = array())
	{
		
		$this->db = new ConnectDB();
		$this->phpass = new PasswordHash(8, false);
		$this->parameters = $parameters;
		$this->checkUserLogin();

	}

	public function loadModel ($modelName = false)
	{

		if (!$modelName) {
			return;
		}

		$modelName = strtolower($modelName);
		$modelPath = BASEDIR.'/models/'.$modelName.'.php';

		if (file_exists($modelPath)) {

			require_once $modelPath;

			$modelName = explode('/', $modelName);
			$modelName = end($modelName);
			$modelName = preg_replace('/[^a-zA-z0-9]/is', '', $modelName);

			if (class_exists($modelName)) {

				return new $modelName($this->db, $this);

			}

			return;

		}

	}

}