<?php

function getTables()
{
	$db = getDBConnection();

	$tables=array();
	foreach($db->query("show tables") as $row)
	{
		$tables[]=$row[0];
	}
	return $tables;
}

function getCols($table)
{
	$db = getDBConnection();

	$tables = getTables();
	$injection = 1;
	foreach($tables as $t)
	{
		if($t == $table)
		{
			$injection = 0;
			break;
		}
	}
	if($injection === 1)
	{
		die("Just prevented SQL injection at getCols(".$table.")");
	}

	$stmt = $db->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='sudoku' AND TABLE_NAME=:t;");
	$np = array();
	$np[':t'] = $table;
	$stmt->execute($np);
	$cols=array();
	while($x = $stmt->fetch())
	{
		$cols[]=$x[0];
	}
	return $cols;
}

function getDifficulties()
{
	$db = getDBConnection();
	$stmt = $db->prepare("SELECT * FROM difficulty;");
	$stmt->execute();

	$diffs = array();

	while($x = $stmt->fetch())
	{
		$diffs[$x['difficulty_id']]=$x['difficulty'];
	}
	return $diffs;
}

function getDBConnection()
{
	$host = "localhost";
	$username = "api";
	$password = "";
	$dbname="sudoku";
	try
	{
		//Creating database connection
		$dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
		// Setting Errorhandling to Exception
		$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch (PDOException $e)
	{
		die("There was some problem connecting to the database! Error: $e");
	}
	return $dbConn;
}
?>