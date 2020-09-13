<?php

session_start();

include ('functions/db_functions.php');

// variables for index.php
$images_per_page = 5;

$start = 0;
$pages = 1;
$image_count = get_row_count('images');
if ($image_count)
	$pages = ceil($image_count / $images_per_page);
if ($pages == 0)
	$pages = 1;
$current_page = 1;
if (!empty($_SESSION['page']))
{
	$current_page = $_SESSION['page'];
	$start = ($current_page - 1) * $images_per_page;
}
$end = $start + $images_per_page;

if ($_POST['function'] == 'reload_user_images')
	reload_user_images($_POST['value1']);

if ($_POST['function'] == 'reload_all_images')
	reload_all_images();

function reload_user_images($user)
{
	$data = get_user_images($user);
	$images = '<div id="my_photos">';

	if ($data)
	{
		foreach ($data as $image)
		{
			$images = $images . '<div id="' . $image["id"] . '" class="single_photo">';
			$src = '/user_images/' . $image["filename"] . '.png';
			$images = $images . '<img src="' . $src . '">'  . '<br />' . 
			'<div class="delete_image">X</div></div>';
		}
	}
	$images . '</div>';
	echo ($images);
}

function reload_all_images()
{
	global $start;
	global $images_per_page;

	$data = get_all_images($start, $images_per_page);

	$images = '';

	if ($data)
	{
		foreach ($data as $image)
		{
			$like_count = "<p>" . get_row_count_of_image_id('likes', $image['id']) . "</p>";
			$src = '/user_images/' . $image['filename'] . '.png';
			
			$images = $images . '<div id="' . $image['id'] . '" class="feed_img">' .
			'<p class="image_user">@' . $image['login'] . '</p>
			<sup class="image_date">' . $image['date'] . '</sup>' .
			'<img src="' . $src . '">';
			$images = $images . '<div class="comments">';
			$images = $images . '<div class="comment_form"><textarea name="comment_area" class="comment_field" placeholder="Add a comment..."></textarea>' .
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
	echo ($images);
}

?>
