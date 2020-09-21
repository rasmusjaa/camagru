<?php
if (!isset($_SESSION))
	session_start();
if (!empty($_SESSION['user']))
	header("Location: /index.php");

if ($_GET['status'] == 'verify')
{
	include ('functions/db_functions.php');

	$uppercase = preg_match('@[A-Z]@', $_POST['password']);
	$lowercase = preg_match('@[a-z]@', $_POST['password']);
	$number    = preg_match('@[0-9]@', $_POST['password']);

	$msg = '<p style="color: green;">Account created, check your email "' . $_POST['email'] . '" for confirmation mail.</p>';

	// validate username
	if (empty($_POST['username']))
		$msg = '<p style="color: red;">Missing username.</p>';
	elseif(preg_match('/[^a-z_\-0-9]/i', $_POST['username']))
		$msg = '<p style="color: red;">Invalid characters in username.</p>';
	// validate mail
	elseif (empty($_POST['email']))
		$msg = '<p style="color: red;">Missing email.</p>';
	elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
		$msg = '<p style="color: red;">Invalid email format.</p>';
	// validate password
	elseif (empty($_POST['password']))
		$msg = '<p style="color: red;">Missing password</p>';
	elseif (!$uppercase || !$lowercase || !$number)
		$msg = '<p style="color: red;">Invalid password, include at least 1 uppercase letter, 1 lowercase letter and 1 number.</p>';
	else
	{
		$ret = add_user($_POST['username'], $_POST['password'], $_POST['email'], 0);
		if ($ret == 1)
			$msg = '<p style="color: red;">User or email already exists, try again.</p>';
		if ($ret == 2)
			$msg = '<p style="color: red;">Error sending confirmation email, try again.</p>';
	}
		

}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" type="text/css" href="styles/vital.css"/>
	<link rel="stylesheet" type="text/css" href="styles/style.css"/>
	<link rel="icon" type="image/ico" href="favicon.ico"/>
	<title>Camagru</title>
</head>
<body class="layouts layouts_index">

<?php require 'header.php';?>

<div class="contents" id="contents">
	<div class="row center">
		<div class="section">
			<div class="autogrid">
				<div class="col-1-4 left">
					<p></p>
				</div>
				<div class="col-1-2">
					<div class="form">
						<h3 class="center">Create account</h3>
						<div class="box bg-white no-first-last">
							<?php echo $msg ?>
							<form action="create.php?status=verify" method="post">
								<p>
									<input placeholder="Email" maxlength="256" type="email" name="email">
									<label><small>Username must be 4-24 characters. Alphanumerics, hyphens and underscores allowed</small></label>
									<input placeholder="Username" minlength="4" maxlength="24" type="text" name="username">
									<label><small>Password must be 8-64 characters</small></label>
									<input placeholder="Password" minlength="8" maxlength="64" type="password" name="password">
								</p>
								<p>
									<input type="submit" value="Create" class="btn red solid">
								</p>
							</form>
							<hr>
							<p>
								Have an account?
								<a href="/login.php" class="btn gray-medium no-outline small">Sign In</a>
							</p>
						</div>
					</div>
				</div>
				<div class="col-1-4 right">
					<p></p>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require 'footer.php';?>

</body>
</html>
