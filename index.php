<?php

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" type="text/css" href="/camagru/styles/vital.css"/>
	<link rel="stylesheet" type="text/css" href="/camagru/styles/style.css"/>
	<link rel="icon" type="image/ico" href="/favicon.ico"/>
	<title>Camagru</title>
</head>
<body class="layouts layouts_index">

<div class="row header light-text" id="header">
	<div class="section">
		<nav>
			<a href="/" class="logo"><img src="/camagru/favicon.ico" alt="Logo"></a>
			<label for="menu-toggle" id="menu-toggle-label"></label>
			<input id="menu-toggle" type="checkbox">
			<div class="icon-menu"></div>
			
			<ul class="menu">
				<li><a href="#">Log In / Log Out</a></li>
				<li><a href="#">Menu</a></li>
				<li><a href="#">Menu</a></li>
				<li><a href="#">Menu</a></li>
			</ul>
		</nav>
	</div>
</div>

<div class="contents light-text" id="contents">
	<div class="row center">
		<div class="section">
			<div class="autogrid">
				<div class="col-1-4 left">
					<p>sidebar</p>
				</div>
				<div class="col-1-2">
					<p>main</p>
				</div>
				<div class="col-1-4 right">
					<div class="form">
						<h3 class="center">Log In</h3>
						<div class="box bg-white no-first-last">
							<p>
								<input placeholder="Username" type="text">
								<input placeholder="Password" type="password">
							</p>
							<p>
								<a href="#" class="btn red solid">Go</a>
							</p>
							<hr>
								<a href="#" class="btn gray-medium no-outline small">Sign Up</a>
								<a href="#" class="btn gray-medium no-outline small">Forgot Password?</a>
						</div>
					</div>

					<div class="form">
					<h3 class="center">Reset Password</h3>
					<div class="box bg-white no-first-last">
						<p>
							<label><small>Enter your email</small></label>
							<input placeholder="Email" type="email">
						</p>
						<p>
							<a href="#" class="btn red solid">Reset</a>
						</p>
					</div>

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
							<a href="#" class="btn gray-medium no-outline small">Sign In</a>
						</p>
					</div>

					<div class="form">
					<h3 class="center">Modify account</h3>
					<p>Fill at least one field that you want to modify</p>
					<div class="box bg-white no-first-last">
						<p>
							<input placeholder="New Username" type="text">
							<input placeholder="New Email" type="email">
							<label><small>Password must include ****</small></label>
							<input placeholder="New Password" type="password">
						</p>
						<p>
							<a href="#" class="btn red solid">Modify</a>
						</p>
						<p>
							Have an account?
							<a href="#" class="btn gray-medium no-outline small">Sign In</a>
						</p>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>

<footer>
	<div class="row footer center light-text" id="footer">
		<div class="section">
			<div class="autogrid padded">
				<div class="col">
					<p>footer</p>
				</div>
				<div class="col">
					<p>footer</p>
				</div>
				<div class="col">
					<p>footer</p>
				</div>
			</div>
		</div>
	</div>
</div>

</body>
</html>
