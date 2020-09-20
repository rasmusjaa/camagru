<?php
require_once "Mail.php";
// used for bonus, I could use mail($to, $subject, $message, $headers);
// but that mostly goes to spam, so using this I can set my own mail as SMTP server and pass spam filters

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
	//	echo($mail->getMessage());
		return FALSE;
	} else {
	//	echo("Message successfully sent!\n");
		return TRUE;
	}
}


function reset_user_mail($user, $email, $key)
{
	global $host;
	global $port;
	global $username;
	global $password;
	global $from;

	$to = $email;
	$subject = "Reset Camagru password";
	$url = "http://localhost:8080/reset.php?verify=" . $key;
	$body = '
	<!doctype html>
	<html>
	<body>
		<h3>Hello ' . $user . '!</h3>
		<p>Password reset request was made with your email address, to change password go to
		<a href="' . $url . '">' . $url . '</a> and reset password within 30 minutes.</p>
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
	//	echo($mail->getMessage());
		return FALSE;
	} else {
	//	echo("Message successfully sent!\n");
		return TRUE;
	}
}

function new_comment_mail($user, $email, $comment)
{
	global $host;
	global $port;
	global $username;
	global $password;
	global $from;

	$to = $email;
	$subject = "New comment on your Camagru photo";
	$url = "http://localhost:8080/";
	$body = '
	<!doctype html>
	<html>
	<body>
		<h3>Hello ' . $user . '!</h3>
		<p>Your image has new comment: "' . $comment . '", see it on
		<a href="' . $url . '">' . $url . '</a>.</p>
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
	//	echo($mail->getMessage());
		return FALSE;
	} else {
	//	echo("Message successfully sent!\n");
		return TRUE;
	}
}

?>
