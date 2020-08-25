<?php

function create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD)
{
	$options = [
		PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
	];
	
	try
	{
		$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $options);
	} catch (Exception $e)
	{
		throw new \PDOException($e->getMessage(), (int)$e->getCode()); // to not show credentials when datbase connect fails
		echo ("Couldn't connect to database");
	}
	return $pdo;
}
