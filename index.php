<?php

include ('functions/db_functions.php');

session_start();
if (!empty($_SESSION['user']))
	$msg = '<h2>Hi ' . $_SESSION['user'] . '</h2>';

// Variables to change from here
$images_per_page = 6;

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

// Images
$data = get_all_images($start, $images_per_page);

$images = '<div id="img_feed">';
if ($data)
{
//	print_r ($data);
	foreach ($data as $image)
	{
		$like_count = "<p>" . get_row_count_of_image_id('likes', $image['id']) . "</p>";
//		$comment_count = get_row_count_of_image_id('comments', $image['id']);
		$src = '/user_images/' . $image['filename'] . '.png';
		
		$images = $images . '<div id="' . $image['id'] . '" class="feed_img">' .
		'<p class="image_user">@' . $image['login'] . '</p>
		<sup class="image_date">' . $image['date'] . '</sup>' .
		'<img src="' . $src . '">';
		$images = $images . '<div class="comments">'; // . 'comments (' . $comment_count . ')';
		$images = $images . '<div class="comment_form"><textarea name="comment_area" class="comment_field"></textarea>' .
			'<input type="submit" value="Post" class="comment_button"></div></div>';
		if (!empty($_SESSION['user']) && has_liked($_SESSION['id'], $image['id']) == 0)
			$images = $images . '<div class="likes">'. '<div class="heart button redheart"></div>' . $like_count . '</div>';
		else
			$images = $images . '<div class="likes">'. '<div class="heart button"></div>' . $like_count . '</div>' . 
			'<div class="comment_feed">';
		$comments = get_all_comments($image['id']);
		foreach ($comments as $comment)
		{
			$images = $images . '<p class="comment">
			<span class="comment_user">' . $comment['login'] . '</span>
			<sup class="comment_date">' . $comment['date'] . '</sup>
			<span class="comment_text">' . $comment['content'] . '</span>
			</p>';
		}
		$images = $images . '</div></div>';
	}
}
$images = $images . '</div>';

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
					<?php echo $images ?>
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
