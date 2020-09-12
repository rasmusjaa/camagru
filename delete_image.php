<?php

include (__DIR__ . '/functions/db_functions.php');

    $img = $_POST['image'];
    
    if (empty($img))
        echo "no image";
    else
        delete_user_image($img);
?>
