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

function modify_user($user, $email, $login, $newpassword, $notifications, $oldpassword)
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

		if (isset($notifications) && $notifications = 'yes')
		{
			$stmt = $pdo->prepare(
				"UPDATE users SET status = 1 WHERE login = ?"
			);
			$stmt->execute([$user]);
		}
		else
		{
			$stmt = $pdo->prepare(
				"UPDATE users SET status = 2 WHERE login = ?"
			);
			$stmt->execute([$user]);
		}

		return (0);
	}
	catch(PDOException $e)
	{
	//	echo $e->getMessage() . PHP_EOL;
		return (3);
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

function add_like($login, $image)
{
	include (__DIR__ . '/../config/database.php');
	try
	{
		$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);
		$data = $pdo->query(
			"DELETE FROM likes WHERE user_id=$login AND image_id=$image;"
		);
		$affected = $data->rowCount();
		if ($affected > 0)
			return (0);
		$pdo->query(
			"INSERT INTO likes (user_id, image_id)
			VALUES ($login, $image);"
		);
		return (0);
	}
	catch(PDOException $e)
	{
//		echo $e->getMessage() . PHP_EOL;
		return (1);
	}
	$pdo = null;
}

function add_comment($login, $image, $comment)
{
	include (__DIR__ . '/../config/database.php');
	try
	{
		$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);
		$stmt = $pdo->prepare(
			"INSERT INTO comments (user_id, image_id, content)
			VALUES ($login, $image, ?);"
		);
		$stmt->execute([$comment]);
		return (0);
	}
	catch(PDOException $e)
	{
//		echo $e->getMessage() . PHP_EOL;
		return (1);
	}
	$pdo = null;
}

function has_liked($login, $image)
{
	include (__DIR__ . '/../config/database.php');
	try
	{
		$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);
		$data = $pdo->query(
			"SELECT * FROM likes WHERE user_id=$login AND image_id=$image;"
		)->fetchAll();
		if ($data)
			return (0);
		return (1);
	}
	catch(PDOException $e)
	{
		echo $e->getMessage() . PHP_EOL;
		return (-1);
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
			"SELECT images.id, images.filename FROM images
			INNER JOIN users      
			ON images.user_id = users.id
			WHERE users.login = ? ORDER BY id DESC;"
		);
		$stmt->execute([$login]);
		$data = $stmt->fetchAll();
		return ($data);
	}
	catch(PDOException $e)
	{
	//	echo $e->getMessage() . PHP_EOL;
		return (NULL);
	}
	$pdo = null;
}

function delete_user_image($id)
{
	include (__DIR__ . '/../config/database.php');
	try
	{
		$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);

		$stmt = $pdo->prepare(
			"SELECT * FROM images WHERE id = ?"
		);
		$stmt->execute([$id]);
		$data = $stmt->fetch();
		if (!$data)
		{
			echo 'No image to remove';
			return (-1);
		}
		$file = __DIR__ . '/../user_images/' . $data[filename] . '.png';
		if (!unlink($file)) {  
			echo ("Image cannot be deleted due to an error");
			return (-1); 
		}
		$stmt = $pdo->prepare(
			"DELETE FROM images WHERE id = ?;"
		);
		$stmt->execute([$id]);
	}
	catch(PDOException $e)
	{
	//	echo $e->getMessage() . PHP_EOL;
		return (NULL);
	}
	$pdo = null;
}

function get_all_images($start, $count)
{
	include (__DIR__ . '/../config/database.php');
	try
	{
		$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);

		$data = $pdo->query(
			"SELECT images.id, images.user_id, images.filename, images.date, users.login FROM images
			INNER JOIN users      
			ON images.user_id = users.id
			ORDER BY images.id DESC
			LIMIT $start, $count"
		)->fetchAll();
		return ($data);
	}
	catch(PDOException $e)
	{
	//	echo $e->getMessage() . PHP_EOL;
		return (NULL);
	}
	$pdo = null;
}

function get_all_comments($id)
{
	include (__DIR__ . '/../config/database.php');
	try
	{
		$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);

		$data = $pdo->query(
			"SELECT * FROM comments
			INNER JOIN users      
			ON comments.user_id = users.id
			WHERE image_id = $id
			ORDER BY comments.id DESC;"
		)->fetchAll();
		return ($data);
	}
	catch(PDOException $e)
	{
	//	echo $e->getMessage() . PHP_EOL;
		return (NULL);
	}
	$pdo = null;
}

function get_row_count($table)
{
	include (__DIR__ . '/../config/database.php');
	try
	{
		$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);
		
		$count = $pdo->query(
			"SELECT COUNT(*) FROM $table"
		)->fetchColumn(); 
		return ($count);
	}
	catch(PDOException $e)
	{
	//	echo $e->getMessage() . PHP_EOL;
		return (NULL);
	}
	$pdo = null;
}

function get_row_count_of_image_id($table, $id)
{
	include (__DIR__ . '/../config/database.php');
	try
	{
		$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);
		$count = $pdo->query(
			"SELECT COUNT(*) FROM $table where image_id = $id"
		)->fetchColumn(); 
		return ($count);
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
			return (-1);
		if ($user['status'] == 0)
			return (-2);
		if (password_verify($pass, $user['pass']))
			return ($user['id']);
		return (-1);
	}
	catch(PDOException $e)
	{
	//	echo $e->getMessage() . PHP_EOL;
		return (-1);
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
			{
				// delete expired code
				$stmt = $pdo->prepare(
					"DELETE FROM password_reset_temp WHERE code = ?"
				);
				$stmt->execute([$code]);
				return FALSE;
			}
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

function create_reset_key($email) // this or some cron job could also query and delete expired codes
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
