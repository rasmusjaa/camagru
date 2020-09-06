<?php

include (__DIR__ . '/functions/db_functions.php');

    $img = $_POST['image'];
    $username = $_POST['username'];
	
	if (empty($img))
        echo "no image";
    elseif (empty($username))
        echo "no user";
	else {
        if (!file_exists('user_images/')) {
            mkdir('user_images/', 0777, true);
        }
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $filename = uniqid($username . '_');
        $file = 'user_images/' . $filename . '.png';
        $success = file_put_contents($file, $data);
        if (!$success)
            echo 'Unable to save the file.';
        elseif (add_image($username, $filename) != 0)
            echo 'Could not save photo to user account';
	}

?>
