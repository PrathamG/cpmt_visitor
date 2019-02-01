<?php
	header('Content-Type: text/html; charset=utf-8');
	$server = 'icm.cloudbreakr.com';
	$user = 'cloudbreakr';
	$password = 'Vbe4Bvy#vN*WcG';
	$dbname = 'cloudbreakr_new';

	$mydb = new mysqli($server, $user, $password, $dbname);

	if($mydb->connect_error)
	{
		die("Connection failed: " . $mydb->connect_error);
	}

	$mydb->set_charset("utf8mb4");
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$influencers = $_POST['influencers'];
		$influencers = preg_split('/\r\n|\r|\n/', $influencers);
		foreach($influencers as $influencer)
		{
			if(strlen($influencer))
			{
				$sql = "SELECT id FROM influencer WHERE instagram = '$influencer'";
				$result = $mydb->query($sql);
				//print_r($result);
				//echo $result->num_rows;
				if($result->num_rows != 0)
				{
					echo $influencer . "<br>";
				}
				else
				{
					echo "invalid<br>";
				}
			}
		}
	}
	else
	{
?>

<form method = "post" action = "<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
	<textarea name = "influencers" cols= "50" rows = "30" style = "overflow-y: scroll;"></textarea>
	<br>
	<button type="submit" class = 'submit-invite-list'>Submit</button>
</form>

<?php
	}
?>