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
					<canvas id="canvas">
					</canvas>
					<div class="output">
						<img id="photo" alt="The screen capture will appear in this box.">
					</div>
				</div>
				<div class="col-1-2">
					<div class="camera">
						<video id="video">Video stream not available.</video>
						<button id="startbutton">Take photo</button>
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
