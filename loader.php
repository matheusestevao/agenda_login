<?php
	if (!defined('BASEDIR')) {
		exit;
	}
	
	//START SESSION
	session_start();

	//CHECK DEBUG
	if (!defined('DEBUG') || DEBUG === false) {

		//HIDDEN ERROR
		error_reporting(0);
		ini_set("display_errors", 0);

	} else {

		//SHOW ERROR
		error_reporting(E_ALL);
		ini_set("display_errors", 1);

	}

	//GLOBAL FUNCTIONS
	require_once BASEDIR.'/functions/functions.php';

	//LOADER SYSTEM
	$loadingSys = new LoadingSys();