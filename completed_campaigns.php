<!--
	completed_campaigns.php
	This webpage lists all of an influencer's active campaigns with brief details and status.
-->
<?php 
session_start();
if(isset($_SESSION['userId']))
{
	$userId = $_SESSION['userId'];
?>
<!DOCTYPE html>
<html>
<head>
	<?php 
		$pageTitle = 'Complete Campaigns';
		include 'headfiles.php'; 
		include 'backend/db_conn.php'; 
		$complete_campaigns = findCompleteCamp($userId);
	?>
	<title>Complete Campaigns</title>
</head>
<body>
<?php include 'top.php'; ?>
	<div  class = "title">
		<p>	Complete Campaigns </p>
	</div>
	<div class = 'containerbod'>
		<div class = "activecamp">
			<?php
				if(empty($complete_campaigns))
				{ 
			?>
				<div class = 'empty-div col-sm-12' style = 'text-align: center; margin-top: 3px; background-color: white;'>
					<p class = 'labelt'> You have no complete campaigns at this time.<br>
				</div>
			<?php 
				} 
				else
				{ 
					foreach($complete_campaigns as $row)
					{
						$campaign = findCampById($row['campaignId']);
			?>
				<div class = "row campaigndet">
					<a href ="completed_detail.php?ref=<?= $campaign['refId'] ?>"><div class = 'mob-link'></div></a>
					<div class = "campleft col-sm-8">
						<p class = "brandcamp"><a href = "completed_detail.php?ref=<?= $campaign['refId'] ?>"><span><?= getBrandById($campaign['businessId'])['name'] ?></span></a>
						<?php if($campaign['facebook'] == 1) { ?> <i class="fb fa fa-facebook-square"></i> <?php } ?>
						<?php if($campaign['instagram'] == 1) { ?><i class="inst fa fa-instagram"></i> <?php } ?></p>
						<p class = "titlecamp"><?= $campaign['title'] ?></p>
						<p class = 'cdates'><?= date('d/m/Y',$campaign['startDate']) ?> - <?= date('d/m/Y',$campaign['endDate']) ?></p>
					</div>
					<div class="no-top-margin campright col-sm-4">
						<div class = 'budget'>HKD $<?= $row['brandPrice'] ?></div>
					</div>
					<a href ="completed_detail.php?ref=<?= $campaign['refId'] ?>"><i class="fa fa-chevron-right" id = 'detrow'></i></a>
				</div>	
			<?php }}	?>		
		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="navscript.js"></script>
</body>
</html>
<?php 
	}
	else
	{
		header('Location: login.php');
	}
 ?>