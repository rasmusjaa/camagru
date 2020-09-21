<?php
if (!isset($_SESSION))
	session_start();
if(!isset($_GET['session']) || !isset($_SESSION['token'])){
	echo 'Logging out only allowed for logged in users from menu bar.';
}
elseif(hash_equals($_GET['session'], $_SESSION['token']) === false){
	echo 'Logging out only allowed from menu bar.';
}
else
{
	session_destroy();
	header("Location: /index.php");
}
	
?>
