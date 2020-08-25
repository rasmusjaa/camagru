<?php

include ('sendmail.php');

function add_user($login, $pass, $email, $status)
{
	include ('config/database.php');
	try
	{
		$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);
		$hashed_pw = password_hash($pass, PASSWORD_BCRYPT);
		$verification = md5($login);

		$stmt = $pdo->prepare(
			"INSERT INTO users (login, pass, email, verification, status)
			VALUES (?, ?, ?, ?, ?)"
		);
		$stmt->execute([$login, $hashed_pw, $email, $verification, $status]);
		new_user_mail($login, $email, md5($login));
		return (TRUE);
	}
	catch(PDOException $e)
	{
		echo $e->getMessage() . PHP_EOL;
		return (FALSE);
	}
	$pdo = null;
}

function verify_user($string)
{
	include ('config/database.php');
	try
	{
		$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);
		
		$stmt = $pdo->prepare(
			"UPDATE users SET status = 1 WHERE verification = ?"
		);
		$stmt->execute([$string]);
		$affected = $stmt->rowCount();
		if ($affected == 1)
			return (TRUE);
		return (FALSE);
	}
	catch(PDOException $e)
	{
		echo $e->getMessage() . PHP_EOL;
		return (FALSE);
	}
	$pdo = null;
}

function login_user($login, $pass)
{
	include ('config/database.php');
	try
	{
		$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);
		
		$stmt = $pdo->prepare(
			"SELECT * FROM users WHERE login = ?"
		);
		$stmt->execute([$login]);
		$user = $stmt->fetch();
		if (!$user) // tai status = 0
			return (FALSE);
		return (password_verify($pass, $user['pass']));
	}
	catch(PDOException $e)
	{
		echo $e->getMessage() . PHP_EOL;
		return (FALSE);
	}
	$pdo = null;
}
?>
