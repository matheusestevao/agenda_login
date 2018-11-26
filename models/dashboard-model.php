<?php

class DashboardModel extends MainModel
{

	public $db;

	function __construct($db= false)
	{
		
		$this->db = $db;

	}

	

}