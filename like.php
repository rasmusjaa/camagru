<?php

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
