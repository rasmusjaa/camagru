<?php

include (__DIR__ . '/functions/db_functions.php');

	$user_id = $_POST['user_id'];
	$img = $_POST['image'];
	$comment = $_POST['comment'];
	
	if (empty($img))
		echo "no image";
	elseif (empty($user_id))
		echo "no user";
	elseif (!empty($comment))
	{
		if (add_comment($user_id, $img, $comment) != 0)
			echo "couldn't add comment to database";
	}
?>
