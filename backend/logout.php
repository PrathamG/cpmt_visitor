<?php
	//Script to end user or admin sessions for logout.
	session_start();
	if(isset($_SESSION['userId']))
	{
		session_unset(); 
		session_destroy();
	}
	header('Location: ../login.php');
?>