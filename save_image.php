<?php

include (__DIR__ . '/functions/db_functions.php');

	$img = $_POST['image'];
	$username = $_POST['username'];
	$overlay = $_POST['overlay'];
	if (empty($img))
		echo "no image to save";
	elseif (empty($username))
		echo "no user";
	elseif (empty($overlay))
		echo "no overlay chosen";
	else {
		if (!file_exists('user_images/')) {
			mkdir('user_images/', 0777, true);
		}
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$filename = uniqid($username . '_');
		$file = 'user_images/' . $filename . '.png';
		if (file_put_contents($file, $data))
		{
			// Add overlay
			$dest = imagecreatefrompng($file);
			$sub = 'overlays/' . substr($overlay, strrpos($overlay, '/') + 1);
			$src = imagecreatefrompng($sub);
			imagecopyresampled($dest, $src, 0, 0, 0, 0, 1080, 810, 1080, 810);
			header('Content-Type: image/png');
			imagepng($dest, $file);
			imagedestroy($dest);
			imagedestroy($src);
			if (add_image($username, $filename) != 0)
				echo 'Could not save photo to user account';
		}
		else
			echo 'Unable to save the file.';
	}

?>
