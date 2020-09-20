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

    $img = $_POST['image'];
    
    if (empty($img))
        echo "no image";
    else
        delete_user_image($img);
?>
