<?php

	session_start();
	if(!isset($_GET['session'])){
		echo 'Logging out only allowed for logged in users from menu bar.';
	}
	//It exists, so compare the token we received against the 
	//token that we have stored as a session variable.
	elseif(hash_equals($_GET['session'], $_SESSION['token']) === false){
		echo 'Logging out only allowed from menu bar.';
	}
	else
	{
		session_destroy();
		header("Location: /index.php");
	}
	
?>
