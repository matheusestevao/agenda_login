<?php
	/** SYSTEM CONFIG **/

	//ROOT
	define('BASEDIR', dirname( __FILE__ ));

	//UPLOADS FOLDER
	define('UPLOAD_BASEDIR', BASEDIR.'/views/upload');

	//HOME
	define('HOME_SYS', 'http://localhost/PROJETOS/agenda_curitiba');

	//DB CONFIG
	define('HOSTNAME', 'localhost');
	define('DB_NAME', 'schedule');
	define('DB_USER', 'root');
	define('DB_PASSWORD', '');
	define('DB_CHARSET', 'utf8');
	
	//DEBUG
	define('DEBUG', false);

	//NOT EDIT
	require_once BASEDIR.'/loader.php';

?>