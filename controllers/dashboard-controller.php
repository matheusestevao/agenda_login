<?php

class DashboardController extends MainController
{
	
	public function index () 
	{

		$this->title = 'Dashboard';

		$parameters = (func_num_args() >= 1) ? func_get_arg(0) : array();

		$model = $this->loadModel('dashboard-model');

		require BASEDIR.'/views/dashboard/index.php';

	}

}