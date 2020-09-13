<?php

session_start();

if (!empty($_GET['page']))
	$_SESSION['page'] = $_GET['page'];
else
	$_SESSION['page'] = 1;

include ('functions.php');

if (!empty($_SESSION['user']))
	$msg = '<h2>Hi ' . $_SESSION['user'] . '</h2>';

// Pages
if ($image_count > 0)
{
	$previous = '';
	if ($current_page > 1)
		$previous = '<a href="/index.php?page=' . ($current_page - 1) . '"><img src="/icons/left.png"></a>';
	$next = '';
	if ($current_page < $pages)
		$next = '<a href="/index.php?page=' . ($current_page + 1) . '"><img src="/icons/right.png"></a>';
	else
		$end = $image_count;
	$page_element = 'Page ' . $current_page . ' / ' . $pages . ',<br>
		photos ' . ($start + 1) . '-' . ($end) . ' / ' . $image_count . '<br>
		' . $previous . $next;
	$no_images = NULL;
}
else
{
	$no_images = 'No photos yet, go on and create some!';
	$page_element = NULL;
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<script type="text/javascript">var username='<?php echo $_SESSION['user'];?>'; var user_id='<?php echo $_SESSION['id'];?>';</script>
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
				<div id="photo_area" class="col">
					<?php echo $msg ?>
					<div class="pages">
						<?php echo $page_element . $no_images ?>
					</div>
					<div id="img_feed">
					</div>
					<div class="pages">
						<?php echo $page_element ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require 'footer.php';?>

</body>
</html>
