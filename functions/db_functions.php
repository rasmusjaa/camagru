<?php

include ('pdo.php');
include ('sendmail.php');

date_default_timezone_set('Europe/Helsinki');

function delete_user($user)
{
	include (__DIR__ . '/../config/database.php');
	try
	{
		$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);
		
		$stmt = $pdo->prepare(
			"DELETE FROM users WHERE username = ?"
		);
		$stmt->execute([$user]);
		$affected = $stmt->rowCount();
		if ($affected == 1)
			return (TRUE);
		return (FALSE);
	}
	catch(PDOException $e)
	{
	//	echo $e->getMessage() . PHP_EOL;
		return (FALSE);
	}
	$pdo = null;
}

function modify_user($user, $email, $login, $newpassword, $oldpassword)
{
	include (__DIR__ . '/../config/database.php');
	try
	{
		$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);
		$stmt = $pdo->prepare(
			"SELECT * FROM users WHERE login = ?"
		);
		$stmt->execute([$user]);
		$line = $stmt->fetch();
		if (!$line || !password_verify($oldpassword, $line['pass']))
			return (1);
		
		if (!empty($email) || !empty($login))
		{
			$stmt = $pdo->prepare(
				"SELECT * FROM users WHERE email = ? OR login = ?"
			);
			$stmt->execute([$email, $login]);
			$line = $stmt->fetch();
			if ($line)
				return (2);
		}
		if (!empty($email))
		{
			$stmt = $pdo->prepare(
				"UPDATE users SET email = ? WHERE login = ?"
			);
			$stmt->execute([$email, $user]);
		}
		
		if (!empty($login))
		{
			$stmt = $pdo->prepare(
				"UPDATE users SET login = ? WHERE login = ?"
			);
			$stmt->execute([$login, $user]);
		}

		if (!empty($newpassword))
		{
			$hashed_pw = password_hash($newpassword, PASSWORD_BCRYPT);

			$stmt = $pdo->prepare(
				"UPDATE users SET pass = ? WHERE login = ?"
			);
			$stmt->execute([$hashed_pw, $user]);
		}

		return (0);
	}
	catch(PDOException $e)
	{
	//	echo $e->getMessage() . PHP_EOL;
		return (FALSE);
	}
	$pdo = null;
}

function add_user($login, $pass, $email, $status)
{
	include (__DIR__ . '/../config/database.php');
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
		if (!new_user_mail($login, $email, md5($login))) {
			delete_user($login);
			return (2);
		}
		return (0);
	}
	catch(PDOException $e)
	{
	//	echo $e->getMessage() . PHP_EOL;
		return (1);
	}
	$pdo = null;
}

function add_image($login, $filename)
{
	include (__DIR__ . '/../config/database.php');
	try
	{
		$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);

		$stmt = $pdo->prepare(
			"SELECT * FROM users WHERE login = ?"
		);
		$stmt->execute([$login]);
		$user = $stmt->fetch();
		if (!$user)
			return (3);
		
		$id = ($user['id']);
		$stmt = $pdo->prepare(
			"INSERT INTO images (user_id, filename)
			VALUES (?, ?)"
		);
		$stmt->execute([$id, $filename]);
		$affected = $stmt->rowCount();
		if ($affected == 1)
			return (0);
		return (2);
	}
	catch(PDOException $e)
	{
	//	echo $e->getMessage() . PHP_EOL;
		return (1);
	}
	$pdo = null;
}

function get_user_images($login)
{
	include (__DIR__ . '/../config/database.php');
	try
	{
		$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);

		$stmt = $pdo->prepare(
			"SELECT * FROM users WHERE login = ?"
		);
		$stmt->execute([$login]);
		$user = $stmt->fetch();
		if (!$user)
			return (3);
		
		$id = ($user['id']);
		$stmt = $pdo->prepare(
			"SELECT id, filename FROM images WHERE user_id = ?"
		);
		$stmt->execute([$id]);
		$pairs = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
		return ($pairs);
	}
	catch(PDOException $e)
	{
	//	echo $e->getMessage() . PHP_EOL;
		return (NULL);
	}
	$pdo = null;
}

function verify_user($string)
{
	include (__DIR__ . '/../config/database.php');
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
	//	echo $e->getMessage() . PHP_EOL;
		return (FALSE);
	}
	$pdo = null;
}

function login_user($login, $pass)
{
	include (__DIR__ . '/../config/database.php');
	try
	{
		$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);
		
		$stmt = $pdo->prepare(
			"SELECT * FROM users WHERE login = ?"
		);
		$stmt->execute([$login]);
		$user = $stmt->fetch();
		if (!$user)
			return (1);
		if ($user['status'] == 0)
			return (2);
		if (password_verify($pass, $user['pass']))
			return (0);
		return (1);
	}
	catch(PDOException $e)
	{
	//	echo $e->getMessage() . PHP_EOL;
		return (1);
	}
	$pdo = null;
}

function get_user($data)
{
	include (__DIR__ . '/../config/database.php');
	try
	{
		$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);
		$stmt = $pdo->prepare(
			"SELECT * FROM users WHERE login = ?"
		);
		$stmt->execute([$data]);
		$user = $stmt->fetch();
		if ($user)
			return ($user);

		$stmt = $pdo->prepare(
			"SELECT * FROM users WHERE email = ?"
		);
		$stmt->execute([$data]);
		$user = $stmt->fetch();
		if ($user)
			return ($user);

		return (NULL);
	}
	catch(PDOException $e)
	{
	//	echo $e->getMessage() . PHP_EOL;
		return (NULL);
	}
	$pdo = null;
}

function reset_pass($newpassword, $code)
{
	include (__DIR__ . '/../config/database.php');
	try
	{
		$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);
		
		if (!empty($newpassword))
		{
			$stmt = $pdo->prepare(
				"SELECT * FROM password_reset_temp WHERE code = ?"
			);
			$stmt->execute([$code]);
			$line = $stmt->fetch();
			if (!$line)
				return (FALSE);
			$email = $line['email'];
			$hashed_pw = password_hash($newpassword, PASSWORD_BCRYPT);
			
			if (strtotime($line['expDate']) - time() < 0)
				return FALSE;
			$stmt = $pdo->prepare(
				"UPDATE users SET pass = ? WHERE email = ?"
			);
			$stmt->execute([$hashed_pw, $email]);

			// delete reset code after password change
			$stmt = $pdo->prepare(
				"DELETE FROM password_reset_temp WHERE code = ?"
			);
			$stmt->execute([$code]);

			return (TRUE);
		}

		return (FALSE);
	}
	catch(PDOException $e)
	{
	//	echo $e->getMessage() . PHP_EOL;
		return (FALSE);
	}
	$pdo = null;
}

function create_reset_key($email)
{
	include (__DIR__ . '/../config/database.php');
	try
	{
		$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);
		
		if (!empty($email))
		{
			$key = md5($email);
			$addKey = substr(md5(uniqid(rand(),1)),3,10);
			$key = $key . $addKey;
			
			$expDate = date("Y-m-d H:i:s", strtotime("+30 minutes"));

			// delete possible previous reset codes
			$stmt = $pdo->prepare(
				"DELETE FROM password_reset_temp WHERE email = ?"
			);
			$stmt->execute([$email]);
			
			$stmt = $pdo->prepare(
				"INSERT INTO password_reset_temp (email, code, expDate)
				VALUES (?, ?, ?)"
			);
			$stmt->execute([$email, $key, $expDate]);
			return ($key);
		}

		return (NULL);
	}
	catch(PDOException $e)
	{
	//	echo $e->getMessage() . PHP_EOL;
		return (NULL);
	}
	$pdo = null;
}

?>
