<?php
require_once "Mail.php";

$host = "ssl://smtp.gmail.com";
$port = "465";
$username = 'rasmuslense@gmail.com';
$password = file_get_contents(__DIR__ . "/../../../test.txt");
$from = "rasmuslense@gmail.com";

function new_user_mail($user, $email, $verification)
{
	global $host;
	global $port;
	global $username;
	global $password;
	global $from;

	$to = $email;
	$subject = "Welcome to Camagru!";
	$url = "http://localhost:8080/login.php?verify=" . $verification;
	$body = '
	<!doctype html>
	<html>
	<body>
		<h3>Hello ' . $user . '!</h3>
		<p>Account was created with your email, to confirm your account and log in go to
		<a href="' . $url . '">' . $url . '</a></p>
	</body>
	</html>
		';

	$headers = array ('Content-Type' => "text/html; charset=ISO-8859-1rn", 'From' => $from, 'To' => $to,'Subject' => $subject);
	$smtp = Mail::factory('smtp',
	array ('host' => $host,
		'port' => $port,
		'auth' => true,
		'username' => $username,
		'password' => $password));

	$mail = $smtp->send($to, $headers, $body);

	if (PEAR::isError($mail)) {
		echo($mail->getMessage());
	} else {
		echo("Message successfully sent!\n");
	}
}
