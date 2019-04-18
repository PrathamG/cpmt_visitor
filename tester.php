<?php
	include 'headfiles.php';
	include 'backend/db_conn.php';

	$sql = "SELECT * FROM influencer";
	$influencer = $mydb->query($sql);
	for($i = 0; $i < 60; $i++)
	{
		$thisInfluencer = $influencer->fetch_assoc();
		if($i > 49)
		{
			echo $thisInfluencer["instagram"] . "<br>";	
		}
		
	}
?>