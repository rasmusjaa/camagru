<?php

session_start();
if (!empty($_SESSION['user']))
	header("Location: /index.php");

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<script type="text/javascript" src="scripts/script.js"></script>
	<link rel="stylesheet" type="text/css" href="/styles/vital.css"/>
	<link rel="stylesheet" type="text/css" href="/styles/style.css"/>
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
						<h3 class="center">Reset Password</h3>
						<div class="box bg-white no-first-last">
							<form action="resetuser.php" method="post">
								<p>
									<label><small>Enter your email</small></label>
									<input placeholder="Email" type="email" name="email">
								</p>
								<p>
								<input type="submit" value="Send reset link" class="btn red solid">
								</p>
							</form>
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
</div>

<?php require 'footer.php';?>

</body>
</html>
