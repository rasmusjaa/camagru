<?php

session_start();
if (!empty($_SESSION['user']))
	$msg = '<p>Logged in as ' . $_SESSION['user'] . ', edit your <a href="/account.php">account</a></p>';
else
	$msg = '<p><a href="/login.php">Log in</a> or <a href="/create.php">create account</a></p>';

?>

<footer>
	<div class="row footer center light-text" id="footer">
		<div class="section">
			<div class="autogrid padded">
				<div class="col">
					<p> </p>
				</div>
				<div class="col">
					<h2>Camagru</h2>
					<h4>Share, comment and like photos</h4>
					<?php echo $msg ?>
				</div>
				<div class="col">
					<p> </p>
				</div>
			</div>
		</div>
	</div>
</div>
