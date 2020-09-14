<?php

session_start();
if (empty($_SESSION['user']))
	header("Location: /index.php");

include ('functions.php');

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<script type="text/javascript">var username='<?php echo $_SESSION['user'];?>';</script>
	<script type="text/javascript" src="scripts/script.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/vital.css"/>
	<link rel="stylesheet" type="text/css" href="styles/style.css"/>
	<link rel="icon" type="image/ico" href="favicon.ico"/>
	<title>Camagru</title>
</head>
<body class="layouts layouts_index">

<?php require 'header.php';?>

<div class="contents" id="contents">
	<div class="row center">
		<div class="section">
			<div class="autogrid">
				<div class="col-3-4">
					<h3>Snap photo</h3>
					<div id="photo_area">
						<div id="dummy"></div>
						<canvas id="canvas" class="hide"></canvas>
						<img id="photo" class="hide" alt="Snapped photo">
						<video id="video">Video stream not available.</video>
						<img id="overlaid">
					</div>
					<p id="warning">Filter has to be selected before using webcam photos</p>
					<button id="snapbutton" class="btn red solid center hide">Take photo</button>
					<p id="uploadtext">Or upload image (will be resized to 4:3 aspepct ratio):</p>
					<input type="file" accept="image/png" id="uploadbutton" name="image" onchange="uploadpicture(event)" class="center">
					<button id="newbutton" class="btn black solid center hide">New photo</button>
					<button id="savebutton" class="btn green solid center hide">Save photo</button>
					<div id="overlays">
						<h3>Filters</h3>
						<div class="overlay"><img src="/overlays/clear.png"></div>
						<div class="overlay"><img src="/overlays/balaclava.png"></div>
						<div class="overlay"><img src="/overlays/bang.png"></div>
						<div class="overlay"><img src="/overlays/border.png"></div>
						<div class="overlay"><img src="/overlays/cat.png"></div>
						<div class="overlay"><img src="/overlays/cells.png"></div>
						<div class="overlay"><img src="/overlays/coffee.png"></div>
						<div class="overlay"><img src="/overlays/colder.png"></div>
						<div class="overlay"><img src="/overlays/colors.png"></div>
						<div class="overlay"><img src="/overlays/cringe.png"></div>
						<div class="overlay"><img src="/overlays/galaxy.png"></div>
						<div class="overlay"><img src="/overlays/horns.png"></div>
						<div class="overlay"><img src="/overlays/mandala.png"></div>
						<div class="overlay"><img src="/overlays/mask.png"></div>
						<div class="overlay"><img src="/overlays/pilots.png"></div>
						<div class="overlay"><img src="/overlays/rainbow.png"></div>
						<div class="overlay"><img src="/overlays/red.png"></div>
						<div class="overlay"><img src="/overlays/shiny.png"></div>
						<div class="overlay"><img src="/overlays/stars.png"></div>
						<div class="overlay"><img src="/overlays/taco.png"></div>
					</div>
				</div>
				<div class="col-1-4 right">
					<h3>My photos</h3>
					<div id="sidebar">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require 'footer.php';?>

</body>
</html>
