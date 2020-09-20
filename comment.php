<?php

session_start();
	if (empty($_POST['token']))
	{
		echo 'Action denied, only allowed to do that from this page';
		return ;
	}
	elseif (hash_equals($_POST['token'], $_SESSION['token']) === false)
	{
		echo 'Action denied, only allowed to do that from this page with valid token';
		return ;
	}

include (__DIR__ . '/functions/db_functions.php');

	$user_id = $_POST['user_id'];
	$img = $_POST['image'];
	$comment = htmlentities($_POST['comment']);
	$owner = $_POST['image_owner'];
	
	if (empty($img))
		echo "no image";
	elseif (empty($user_id))
		echo "no user";
	elseif (empty($owner))
		echo "no image poster";
	elseif (!empty($comment))
	{
		if (add_comment($user_id, $img, $comment) != 0)
			echo "couldn't add comment to database";
		else
		{
			$data = get_user($owner);
			if ($data['status'] == 1)
			{
				if (new_comment_mail($data['login'], $data['email'], $comment) != TRUE)
					echo 'failed to send email to poster of image';
			}
		}
	}
?>
