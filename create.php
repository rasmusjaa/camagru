<?php

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<script type="text/javascript" src="scripts/script.js"></script>
	<link rel="stylesheet" type="text/css" href="/camagru/styles/vital.css"/>
	<link rel="stylesheet" type="text/css" href="/camagru/styles/style.css"/>
	<link rel="icon" type="image/ico" href="/favicon.ico"/>
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
							<p>
								<input placeholder="Username" type="text">
								<input placeholder="Email" type="email">
								<label><small>Password must include ****</small></label>
								<input placeholder="Password" type="password">
							</p>
							<p>
								<a href="#" class="btn red solid">Create</a>
							</p>
							<p>
								Have an account?
								<a href="/camagru/login.php" class="btn gray-medium no-outline small">Sign In</a>
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
