<?php

session_start();
	if (empty($_POST['token']) || !isset($_SESSION['token']))
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
	$username = $_POST['username'];
	$overlay = $_POST['overlay'];
	if (empty($img))
		echo "no image to save";
	elseif (empty($username))
		echo "no user";
	else {
		if (!file_exists('user_images/')) {
			mkdir('user_images/', 0777, true);
		}
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$filename = uniqid($username . '_');
		$file = 'user_images/' . $filename . '.png';
		if (file_put_contents($file, base64_decode($img)))
		{
			// Add overlay
			if (!empty($overlay))
			{
				$overlay = str_replace('data:image/png;base64,', '', $overlay);
				$overlay = str_replace(' ', '+', $overlay);
				$temp_file = 'temp_overlay.png';
				if (file_put_contents($temp_file, base64_decode($overlay)))
				{
					$dest = imagecreatefrompng($file);
					$src = imagecreatefrompng($temp_file);
					imagecopyresampled($dest, $src, 0, 0, 0, 0, 1080, 810, 1080, 810);
					header('Content-Type: image/png');
					imagepng($dest, $file);
					imagedestroy($dest);
					imagedestroy($src);
					unlink($temp_file);
				}
				else
					echo 'Could not add overlay';
			}
			if (add_image($username, $filename) != 0)
				echo 'Could not save photo to user account';
		}
		else
			echo 'Unable to save the file.';
	}

?>
