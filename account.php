<?php

session_start();
if (empty($_SESSION['user']))
header("Location: /index.php");

include ('functions/db_functions.php');

$msg = '<p>Fill at least one field that you want to modify and your old password</p>';

if ($_GET['status'] == 'verify')
{
	if (!empty($_POST['newpassword']))
	{
		$uppercase = preg_match('@[A-Z]@', $_POST['newpassword']);
		$lowercase = preg_match('@[a-z]@', $_POST['newpassword']);
		$number    = preg_match('@[0-9]@', $_POST['newpassword']);
	}

	// validate username
	if (empty($_POST['oldpassword']))
		$msg = '<p style="color: red;">Need current password to confirm changes.</p>';
	elseif (!empty($_POST['username']) && strlen($_POST['username']) < 4)
		$msg = '<p style="color: red;">Username too short.</p>';
	elseif (!empty($_POST['username']) && strlen($_POST['username']) > 24)
		$msg = '<p style="color: red;">Username too long.</p>';
	// validate mail
	elseif (!empty($_POST['email']) && strlen($_POST['email']) > 256)
		$msg = '<p style="color: red;">Email too long.</p>';
	elseif (!empty($_POST['email']) && !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
		$msg = '<p style="color: red;">Invalid email format.</p>';
	// validate password
	elseif (!empty($_POST['newpassword']) && (!$uppercase || !$lowercase || !$number || strlen($_POST['newpassword']) < 8 || strlen($_POST['newpassword']) > 64))
		$msg = '<p style="color: red;">Invalid password, include at least 1 uppercase letter, 1 lowercase letter and 1 number.</p>';
	elseif (login_user($_POST['username'], $_POST['password']) == FALSE)
		$msg = '<p style="color: red;">Current password incorrect.</p>';
	else
	{
		$ret = modify_user($_SESSION['user'], $_POST['email'], $_POST['username'], $_POST['newpassword'], $_POST['notifications'], $_POST['oldpassword']);
		if ($ret == 0)
		{
			if (!empty($_POST['username']))
				$_SESSION['user'] = $_POST['username'];
			header("Location: account.php?status=done");
		}
		if ($ret == 1)
		$msg = '<p style="color: red;">Incorrect password</p>';
		if ($ret == 2)
		$msg = '<p style="color: red;">Username or email already already in use, try again.</p>';
	}
}
if ($_GET['status'] == 'done')
{
	$msg = '<p style="color: green;">Account modified successfully.</p>';
}

$cur_login = 'no data';
$cur_email = 'no data';
$user_data = get_user($_SESSION['user']);
if ($user_data)
{
	$cur_login = $user_data['login'];
	$cur_email = $user_data['email'];
	if ($user_data['status'] == 1)
	{
		$checked = 'checked';
		$cur_comments = 'On';
	}
	else
	{
		$checked = '';
		$cur_comments = 'Off';
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
						<h3 class="center">Modify account</h3>
						<div class="box bg-white no-first-last">
							<p>Current username: <?php echo $cur_login ?><br>Current email: <?php echo $cur_email ?>
								<br>Email notifications from new comments: <?php echo $cur_comments ?></p>
							<?php echo $msg ?>
							<form action="account.php?status=verify" method="post">
								<p>
									<input placeholder="New Email" type="email" name="email"><br>
									<label><small>Username must be 4-24 characters</small></label>
									<input placeholder="New Username" type="text" name="username"><br>
									<label><small>New password must be 8-64 characters</small></label>
									<input placeholder="New Password" type="password" name="newpassword"><br>
									<label><small>Email notifications </small></label>
									<input type="checkbox" name="notifications" <?php echo $checked ?> value="yes"><br><br>
									<label><small>Current password to confirm changes</small></label>
									<input placeholder="Current Password" type="password" name="oldpassword">
								</p>
								<p>
								<input type="submit" value="Modify" class="btn red solid">
								</p>
							</form>
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
