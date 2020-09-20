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
	
	if (empty($img))
		echo "no image";
	elseif (empty($user_id))
		echo "no user";
   elseif (add_like($user_id, $img) != 0)
	   echo "couldn't add like to database";
?>
