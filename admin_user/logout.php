<?php
	//Script to end user or admin sessions for logout.
	session_start();
	unset($_SESSION['adminUserId']);
	header('Location: index.php');
?>