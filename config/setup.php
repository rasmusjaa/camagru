<?php

include ('database.php');
include (__DIR__ . '/../functions/pdo.php');
include (__DIR__ . '/../functions/db_functions.php');

try
{
	$pdo = new PDO($DB_DSN_NO_TABLE, $DB_USER, $DB_PASSWORD);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$pdo->query("DROP DATABASE IF EXISTS " . $DB);
	$pdo->query("CREATE DATABASE " . $DB);
	echo "Database (re)created successfully" . PHP_EOL;
}
catch(PDOException $e)
{
	echo $sql . PHP_EOL . $e->getMessage();
}
$pdo = null;

try
{
	$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);

	// Create user table
	$stmt = $pdo->query(
		"CREATE TABLE users (
		id INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		login VARCHAR(24) NOT NULL UNIQUE,
		pass VARCHAR(64) NOT NULL,
		email VARCHAR(256) NOT NULL UNIQUE,
		verification VARCHAR(64) NOT NULL,
		status INT(1) NOT NULL,
		reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP)"
	);

	echo "Table users created successfully!" . PHP_EOL;

	// Create image table
	$stmt = $pdo->query(
		"CREATE TABLE images (
		id INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		CONSTRAINT fk_image_user FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE,
		title VARCHAR(128),
		date TIMESTAMP DEFAULT CURRENT_TIMESTAMP)"
	);

	echo "Table images created successfully!" . PHP_EOL;

	// Create comments table
	$stmt = $pdo->query(
		"CREATE TABLE comments (
		id INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		CONSTRAINT fk_comment_image FOREIGN KEY (id) REFERENCES images(id) ON DELETE CASCADE,
		CONSTRAINT fk_comment_user FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE,
		content TEXT,
		date TIMESTAMP DEFAULT CURRENT_TIMESTAMP)"
	);

	echo "Table comments created successfully!" . PHP_EOL;

	// Create likes table
	$stmt = $pdo->query(
		"CREATE TABLE likes (
		id INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		CONSTRAINT fk_like_image FOREIGN KEY (id) REFERENCES images(id) ON DELETE CASCADE,
		CONSTRAINT fk_like_user FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE)"
	);

	echo "Table likes created successfully!" . PHP_EOL;
}
catch(PDOException $e)
{
	echo $stmt . PHP_EOL . $e->getMessage();
}
$pdo = null;

try
{
	$pdo = create_pdo_connection($DB_DSN, $DB_USER, $DB_PASSWORD);

	$login = 'user42';
	$pass = 'user42';
	$email = 'rasmuslense@gmail.com';
	$status = 2;

	// Create admin user
	add_user($login, $pass, $email, $status);

	// $user = $stmt->fetch();
	// $affected = $stmt->rowCount();

	echo "Admin user42 created successfully!" . PHP_EOL;

/*
	// Create image table
	$sql = "CREATE TABLE images (
		id INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		CONSTRAINT fk_image_user FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE,
		title VARCHAR(128),
		date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	)";

	$pdo->exec($sql);

	echo "Table images created successfully!" . PHP_EOL;

	// Create comments table
	$sql = "CREATE TABLE comments (
		id INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		CONSTRAINT fk_comment_image FOREIGN KEY (id) REFERENCES images(id) ON DELETE CASCADE,
		CONSTRAINT fk_comment_user FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE,
		content TEXT,
		date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	)";

	$pdo->exec($sql);

	echo "Table comments created successfully!" . PHP_EOL;

	// Create likes table
	$sql = "CREATE TABLE likes (
		id INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		CONSTRAINT fk_like_image FOREIGN KEY (id) REFERENCES images(id) ON DELETE CASCADE,
		CONSTRAINT fk_like_user FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE
	)";

	$pdo->exec($sql);

	echo "Table likes created successfully!" . PHP_EOL;
	*/
}
catch(PDOException $e)
{
	echo $e->getMessage();
}
$pdo = null;

?>
