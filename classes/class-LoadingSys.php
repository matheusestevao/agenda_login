<?php

class LoadingSys
{

	private $controller;
	private $action;
	private $parameters;
	private $not_found = '/includes/404.php';

	public function __construct ()
	{
		
		$this->get_url();

		if (!$this->controller) {

			/*ADD PATTERN CONTROLLER*/
			require_once BASEDIR.'/controllers/home-controller.php';

			$this->controller = new HomeController();

			$this->controller->index();

			return;

		}

		if (!file_exists(BASEDIR.'/controllers/'.$this->controller.'.php')) {

			//PAGE NOT FOUND
			require_once BASEDIR.$this->not_found;

			return;

		}

		//INCLUDE CONTROLLER FILE
		require_once BASEDIR.'/controllers/'.$this->controller.'.php';

		//CLEAN NAME CONTROLLER
		$this->controller = preg_replace('/[^a-zA-Z]/i', '', $this->controller);

		//NOT FOUND CLASS CONTROLLER
		if (!class_exists($this->controller)) {

			//PAGE NOT FOUND
			require_once BASEDIR.$this->not_found;

			return;

		}

		$this->controller = new $this->controller($this->parameters);
		
		$this->action = preg_replace('/[^a-zA-Z]/i', '', $this->action);
		
		if (method_exists($this->controller, $this->action)) {

			$this->controller->{$this->action}($this->parameters);

			return;

		}

		if (!$this->action && method_exists($this->controller, 'index')) {

			$this->controller->index($this->parameters);

			return;

		}

		//PAGE NOT FOUND
		require_once BASEDIR.$this->not_found;

		return;

	}

	public function get_url ()
	{
		
		if (isset($_GET['path'])) {

			$path = $_GET['path'];

			$path = rtrim($path, '/');
			$path = filter_var($path, FILTER_SANITIZE_URL);

			$path = explode('/', $path);

			$this->controller = checkArray($path, 0);
			$this->controller .= '-controller';
			$this->action 	  = checkArray($path, 1);

			//CONFIG PARAMETERS
			if (checkArray($path, 2)) {

				unset($path[0]);
				unset($path[1]);

				$this->parameters = array_values($path);

			}

		}

	}

}