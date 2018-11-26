<?php

class MainModel
{

	public $formData;
	public $formMsg;
	public $formConfirm;
	public $db;
	public $controller;
	public $parameters;
	public $userData;

	public function DateFormat ($date, $local = 'db')
	{

		$newDate = null;

		if ($date) {

			$date = preg_split('/-|/|s|:/', $date);;
			$date = array_map('trim', $date);

			$newDate .= checkArray($date, 2).'-';
			$newDate .= checkArray($date, 1).'-';
			$newDate .= checkArray($date, 0);

		}

		if (checkArray($date, 3)) {

			$nweDate .= ' '.checkArray($date, 3);

		}

		if (checkArray($date, 4)) {

			$newDate .= ':'.checkArray($date, 4)

		}

		if (checkArray($date, 5)) {

			$newDate .= ':'.checkArray($date, 5)

		}

	}

	
	function __construct(argument)
	{
		# code...
	}
}