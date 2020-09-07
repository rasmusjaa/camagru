<?php

include ('functions/db_functions.php');

session_start();
if (empty($_SESSION['user']))
	header("Location: /index.php");

$data = get_user_images($_SESSION['user']);

$images = '<div>';

if ($data)
{
//	print_r ($data);
	foreach ($data as $key => $value)
	{
		$src = '/user_images/' . $value . '.png';
		$images = $images . '<img src="' . $src . '">'  . "<br />";
	}
}

$images . '</div>';

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

<div class="contents light-text" id="contents">
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
					</div>
					<button id="snapbutton" class="btn red solid center">Take photo</button>
					<button id="newbutton" class="btn white solid center hide">New photo</button>
					<button id="savebutton" class="btn green solid center hide">Save photo</button>
				</div>
				<div class="col-1-4 right">
					<h3>My photos</h3>
					<?php echo $images ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require 'footer.php';?>

</body>
</html>
