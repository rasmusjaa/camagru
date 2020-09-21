<?php
if (!isset($_SESSION))
	session_start();
if (!empty($_SESSION['user']))
	header("Location: /index.php");

include ('functions/db_functions.php');

$form = '<form action="reset.php?status=reset" method="post">
		<p>
			<label><small>Enter your email</small></label>
			<input placeholder="Email" type="email" name="email">
		</p>
		<p>
			<input type="submit" value="Submit" class="btn red solid">
		</p>
	</form>';

if ($_GET['status'] == 'reset')
{
	$msg = '<p style="color: green;">Reset instructions sent if account with given email exists.</p>';
	if (!empty($_POST['email']))
	{
		$user_data = get_user($_POST['email']);
		if ($user_data)
		{
			$code = create_reset_key($_POST['email']);
			if (reset_user_mail($user_data['login'], $user_data['email'], $code) == FALSE)
				$msg = '<p style="color: green;">Error sending reset email, try again.</p>';
		}
	}
}

if (!empty($_GET['verify']))
{
	$form = '<form action="reset.php?verify=' . $_GET['verify'] . '" method="post">
			<p>
				<label><small>Enter new password</small></label>
				<input placeholder="Password" type="password" name="newpassword">
			</p>
			<p>
				<input type="submit" value="Submit" class="btn red solid">
			</p>
		</form>';

	if (!empty($_POST['newpassword']))
	{
		$uppercase = preg_match('@[A-Z]@', $_POST['newpassword']);
		$lowercase = preg_match('@[a-z]@', $_POST['newpassword']);
		$number    = preg_match('@[0-9]@', $_POST['newpassword']);
		// validate password
		if (!empty($_POST['newpassword']) && (!$uppercase || !$lowercase || !$number || strlen($_POST['newpassword']) < 8 || strlen($_POST['newpassword']) > 64))
			$msg = '<p style="color: red;">Invalid password, include at least 1 uppercase letter, 1 lowercase letter and 1 number.</p>';
		elseif (reset_pass($_POST['newpassword'], $_GET['verify']) == FALSE)
			$msg = '<p style="color: red;">Password not reseted, check that link is correct and not expired.</p>';
		else
		{
			$msg = '<p style="color: green;">Password changed successfully, redirecting to login in 5 seconds.</p>';
			header("refresh:5;url=/login.php");
		}
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" type="text/css" href="/styles/vital.css"/>
	<link rel="stylesheet" type="text/css" href="/styles/style.css"/>
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
					<?php echo $msg ?>
					<div class="form">
						<h3 class="center">Reset Password</h3>
						<div class="box bg-white no-first-last">
							<?php echo $form ?>
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
</div>

<?php require 'footer.php';?>

</body>
</html>
