<?php
if (!isset($_SESSION))
	session_start();
if (!empty($_SESSION['user']))
{
	$menu = '
	<li><a href="/logout.php?session=' . $_SESSION['token'] . '">Log Out</a></li>
	<li><a href="/account.php">My Account</a></li>
	<li><a href="/capture.php">My Photos</a></li>
	';
}
else
{
	$menu = '
	<li><a href="/create.php">Sign Up</a></li>
	<li><a href="/login.php">Log In</a></li>
	';
}
?>

<div class="row header" id="header">
	<div class="section">
		<nav>
			<a href="/" class="logo"><img src="/icons/camagru-logo.png" alt="Logo"></a>
			<label for="menu-toggle" id="menu-toggle-label"></label>
			<input id="menu-toggle" type="checkbox">
			<div class="icon-menu"></div>

			<ul class="menu">
				<?php echo $menu ?>				
				<li><a href="/index.php">Home</a></li>
			</ul>
		</nav>
	</div>
</div>
