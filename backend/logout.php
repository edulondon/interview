<?php
	
//session_start();
  	//	session_destroy();
	//	header("Location:login.html");
 	 // 1. find the session
	session_start();

//2. Unset all the session variables
	$_SESSION = array();

//3. Destroy the session cookie
	if(isset($_COOKIE[session_name()])){
		setcookie(session_name(), '', time()-42000, '/');
	}
// 4. Destroy the session
	session_destroy();
		echo json_encode(array(
    		"status"=> 200,
    		"message"=> 'success'
    	), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
	//header("Location:../index.php");
?>