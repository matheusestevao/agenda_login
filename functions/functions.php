<?php
	
	function __autoload($class_name)
	{
		
		$file = BASEDIR . '/classes/class-' . $class_name . '.php';

		if (!file_exists($file)) {

			require_once BASEDIR . '/includes/404.php';
			return;

		}
		
		//INCLUDE FILE CLASSES
	    require_once $file;
	
	}

	/**
	*Check Array keys
	* @param array  $array the array
	* @param string $key   the key of array
	* @return string|null  the value key of that array or null
	*/
	function checkArray ($array, $key)
	{

		if (isset($array[$key]) && !empty($array[$key])) {

			return $array[$key];

		}

		//RETURN NULL DEFAULT
		return null;

	}
