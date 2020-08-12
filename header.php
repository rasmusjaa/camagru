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
				<li><a href="/camagru/login.php">Log In</a></li> <!-- toggle et nakyy vaan jos ei sisal -->
				<li><a href="/camagru/logout.php">Log Out</a></li> <!-- toggle -->
				<li><a href="/camagru/modify.php">My Account</a></li>
				<li><a href="/camagru/index.php">Home</a></li>
			</ul>
		</nav>
	</div>
</div>
