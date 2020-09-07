<?php

include ('functions/db_functions.php');

session_start();
if (!empty($_SESSION['user']))
	$msg = '<h2>Hi ' . $_SESSION['user'] . '</h2>';

// Variables to change from here
$images_per_page = 2;

// Other variables
$start = 0;
$pages = 1;
$image_count = get_row_count('images');
if ($image_count)
$pages = ceil($image_count / $images_per_page);
if ($pages == 0)
	$pages = 1;
$current_page = 1;
if (!empty($_GET['page']))
{
	$current_page = $_GET['page'];
	$start = ($current_page - 1) * $images_per_page;
}
$end = $start + $images_per_page;

// Pages
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

// Images
$data = get_all_images($start, $images_per_page);

$images = '<div id="img_feed">';
if ($data)
{
//	print_r ($data);
	foreach ($data as $key => $value)
	{
		$src = '/user_images/' . $value . '.png';

		$images = $images . '<div id="' . $key . '" class="feed_img">' .
		'<img src="' . $src . '">'  . '</div>' .
		'<div class="comments">' . 'comments' . '</div>' .
		'<div class="likes">'. '<div class="heart button"></div>' . '</div>';
	}
}
$images = $images . '</div>';

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
				<div id="photo_area" class="col">
					<?php echo $msg ?>
					<div class="pages">
						<?php echo $page_element ?>
					</div>
					<?php echo $images ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require 'footer.php';?>

</body>
</html>
