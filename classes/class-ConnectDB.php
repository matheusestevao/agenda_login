<?php

class ConnectDB
{

	public $host 	 = HOSTNAME,
		   $dbName 	 = DB_NAME,
		   $password = DB_PASSWORD,
		   $user 	 = DB_USER,
		   $charset  = DB_CHARSET,
		   $pdo 	 = null,
		   $error 	 = null,
		   $debug 	 = DEBUG,
		   $lastId 	 = null;

	public function __construct ($host = null, $dbName = null, $password = null, $user = null, $charset = null, $debug = null)
	{

		$this->connect();

	}

	final protected function connect ()
	{

		$pdoDetails = "mysql:host={$this->host};";
		$pdoDetails .= "dbname={$this->dbName};";
		$pdoDetails .= "charset={$this->charset};";

		try {

			$this->pdo = new PDO($pdoDetails, $this->user, $this->password);

			if ($this->debug === true) {

				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

			}

			unset($this->host);
			unset($this->dbName);
			unset($this->password);
			unset($this->user);
			unset($this->charset);

		} catch (PDOException $e) {

			if ($this->debug === true) {

				echo "Erro: ".$e->getMensage();

			}

			die();

		}

	}

	public function query ($stmt, $dataArray = null)
	{

		$query = $this->pdo->prepare($stmt);
		$checkExec = $query->execute($dataArray);

		if ($checkExec) {

			return $query;

		} else {

			$error = $query->errorInfo();
			$this->error = $error[2];

			return false;

		}

	}

	public function insert ($table)
	{

		/*CONFIG INSERT*/
		$cols = array();
		$placeHolders = '(';
		$value = array();
		$j = 1;

		$data = func_get_args();

		if (!isset($data[1]) || !is_array($data[1])) {

			return;

		}

		for ($i = 1; $i < count($data); $i++) {

			foreach ($data[$i] as $col => $val) {

				/*CONFIG COLS*/
				if ($i === 1) {

					$cols[] = "`$col`";

				}

				if ($j <> $i) {

					$placeHolders .= '), (';

				}

				$placeHolders .= '?, ';

				$values[] = $val;

				$j = $i;

			}

			$placeHolders = substr($placeHolders, 0, strlen($placeHolders)-2);

		}

		$cols = implode(', ', $cols);

		$stmt = "INSERT INTO `$table` ($cols) VALUES $placeHolders) ";

		$insert = $this->query($stmt, $values);

		if ($insert) {

			if (method_exists($this->pdo, 'lastInsertId') && $this->pdo->lastInsertId()) {

				$this->lastId = $this->pdo->lastInsertId();

			} 

			return $insert;

		}

		return;

	}

	public function update ($table, $whereField, $whereFieldValue, $values)
	{

		if (empty($table) || empty($whereField) || empty($whereFieldValue)) {

			return;

		}

		$stmt = " UPDATE {$table} SET ";

		$set = array();

		$where = " WHERE {$whereField} = ? ";

		if (!is_array($values)) {
			
			return;

		}

		foreach ($values as $column => $value) {
			
			$set[] = " {$column} = ?";

		}

		$set = implode(', ', $set);

		$stmt .= $set . $where;

		$values[] = $whereFieldValue;
		$value = array_values($values);

		$update = $this->query($stmt, $values);

		if ($update) {

			return $update;

		}

		return;

	}

	public function delete ($table, $whereField, $whereFieldValue)
	{

		if (empty($table) || empty($whereField) || empty($whereFieldValue)) {

			return;

		}

		$stmt = " DELETE FROM {$table} ";
		$where = " WHERE {$whereField} = ?";
		$stmt .= $where;

		$values = array($whereFieldValue);

		$delete = $this->query($stmt, $values);

		if ($deete) {

			return $delete;

		}

		return;

	}


}