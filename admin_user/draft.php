<!--
	draft.php
	This webpage lets the admin to view draft images and captions submitted by influencers.
-->
<?php
session_start();
if($_SESSION['adminUserId'] == 1350)
{
	$userId = $_SESSION['adminUserId'];

	include '../backend/db_conn.php';
	include '../backend/model.php';

	$draftId = $_GET['dId'];
	$userId = $_GET['uId'];

	global $mydb;
	$sql = "SELECT name FROM influencer WHERE id = ? ";

	$stmt = $mydb->prepare($sql);
	$stmt->bind_param('i',$userId);

	if($stmt->execute())
	{
		$name = $stmt->get_result()->fetch_assoc()['name'];
	}
	else
	{
		echo 'Error<br>';
	}

	$sql = "SELECT draftPath, caption FROM jobs_campaign_drafts WHERE id = $draftId";
	if($obj = $mydb->query($sql))
	{
		$obj = $obj->fetch_assoc();
		$draftPath = $obj['draftPath'];
		$caption = $obj['caption'];
	}
	else
	{
		die('An error occurred');
	}
?>

<body>
	Influencer:<br><?= convertEmoji($name) ?><br><br>
	<?php 
		$fileType = strtolower(pathinfo($draftPath,PATHINFO_EXTENSION));
		if($fileType == 'mp4'){
	?>
		<video controls>
	  		<source src="<?= $draftPath ?>" type="video/mp4">
		</video><br><br>
	<?php } else { ?>
		<img src = '<?= $draftPath ?>'><br><br>
	<?php } ?>
	Caption: <br>
	<?= urldecode($caption) ?> 
</body>
<?php } ?>