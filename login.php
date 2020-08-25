<?php

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
						<h3 class="center">Log In</h3>
						<div class="box bg-white no-first-last">
							<form action="loginuser.php" method="post">
								<p>
									<input placeholder="Username" type="text" name="username">
									<input placeholder="Password" type="password" name="password">
								</p>
								<p>
									<input type="submit" value="Go" class="btn red solid">
								</p>
							</form>
							<hr>
								<a href="/create.php" class="btn gray-medium no-outline small">Sign Up</a>
								<a href="/reset.php" class="btn gray-medium no-outline small">Forgot Password?</a>
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
