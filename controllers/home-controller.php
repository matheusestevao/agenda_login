<?php

class HomeController extends MainController
{

	/*LOADING PAGE "/views/home/home-view.php"*/
	public function index ()
	{

		$this->title = 'Home';

		//PARAMETERS OF FUNCTION
		$parameters = (func_num_args() >= 1) ? func_get_arg(0) : array();

		require BASEDIR.'/views/layout/header.php';
		require BASEDIR.'/views/layout/menu.php';
		require BASEDIR.'/views/layout/footer.php';

	}

}