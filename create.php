<?php

?>

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
