<?php
if ($_GET['status'] == 'verify')
{
	include ('functions/db_functions.php');

	// validate username
	if (empty($_POST['username']))
		$msg = '<p style="color: red;">Missing username</p>';
	elseif (strlen($_POST['username']) < 4)
		$msg = '<p style="color: red;">Username too short</p>';
	elseif (strlen($_POST['username']) > 24)
		$msg = '<p style="color: red;">Username too long</p>';

	elseif (empty($_POST['email']))
		$msg = '<p style="color: red;">Missing email</p>';
	elseif (strlen($_POST['username']) > 256)
		$msg = '<p style="color: red;">Email too long</p>';
	elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
		$msg = '<p style="color: red;">Invalid email format</p>';
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<script type="text/javascript" src="scripts/script.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/vital.css"/>
	<link rel="stylesheet" type="text/css" href="styles/style.css"/>
	<link rel="icon" type="image/ico" href="favicon.ico"/>
	<title>Camagru</title>
</head>
<body class="layouts layouts_index">

<?php require 'header.php';?>

<div class="contents light-text" id="contents">
	<div class="row center">
		<div class="section">
			<div class="autogrid">
				<div class="col-1-4 left">
					<p>sidebar</p>
				</div>
				<div class="col-1-2">
					<div class="form">
						<h3 class="center">Create account</h3>
						<div class="box bg-white no-first-last">
							<?php echo $msg ?>
							<form action="create.php?status=verify" method="post">
								<p>
									<label><small>Username must be 4-24 characters</small></label>
									<input placeholder="Username" type="text" name="username">
									<input placeholder="Email" type="email" name="email">
									<label><small>Password must include ****</small></label>
									<input placeholder="Password" type="password" name="password">
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
					<p>sidebar</p>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require 'footer.php';?>

</body>
</html>
