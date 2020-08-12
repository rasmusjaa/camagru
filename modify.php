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
				<div class="col-1-4 right">
					<p>sidebar</p>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require 'footer.php';?>
