<!--
	pending_campaigns.php
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
		$pageTitle = 'Pending Campaigns';
		include 'headfiles.php'; 
		include 'backend/db_conn.php'; 
		$pending_campaigns = findPendingCamp($userId);
	?>
	<title>Pending Campaigns</title>
</head>
<body>
<?php include 'top.php'; ?>
	<div  class = "title">
		<p>	Pending Campaigns </p>
	</div>
	<div class = 'containerbod'>
		<div class = "activecamp">
			<?php
				if(empty($pending_campaigns))
				{ 
			?>
				<div class = 'empty-div col-sm-12' style = 'text-align: center; margin-top: 3px; background-color: white;'>
					<p class = 'labelt'> You have no pending campaigns at this time.<br>
						You can apply to public campaigns at the <a href = 'marketplace.php'>marketplace</a>.</p>
				</div>
			<?php 
				} 
				else
				{
					foreach($pending_campaigns as $row)
					{
						$campaign = findCampById($row['campaignId']);
			?>
				<div class = "row campaigndet">
					<a href = "pending_detail.php?ref=<?= $campaign['refId'] ?>"><div class = 'mob-link'></div></a>
					<div class = "campleft col-sm-8">
						<p class = "brandcamp"><a href = "pending_detail.php?ref=<?= $campaign['refId'] ?>"><span><?= getBrandById($campaign['businessId'])['name'] ?></span></a>
						<?php if($campaign['facebook'] == 1) { ?> <i class="fb fa fa-facebook-square"></i> <?php } ?>
						<?php if($campaign['instagram'] == 1) { ?><i class="inst fa fa-instagram"></i> <?php } ?></p>
						<p class = "titlecamp"><?= $campaign['title'] ?></p>
						<p class = 'cdates'><?= date('d/m/Y',$campaign['startDate']) ?> - <?= date('d/m/Y',$campaign['endDate']) ?></p>
					</div>
					<div class="campright col-sm-4">
						<div class = 'budget'>HKD $<?= getActivePrice($campaign['id'], $userId) ?></div>
					</div>
					<a href = "pending_detail.php?ref=<?= $campaign['refId'] ?>"><i class="fa fa-chevron-right" id = 'detrow'></i></a>
					<div class = 'statusdisp'>
						<?php if(isNotify($userId, $row['updatedAt'])){ ?> 
							<i class="notification fa fa-circle" style = 'font-size: 9px'></i>
						<?php } ?>
						<?php getPendingStatus($row['status']); ?></div>
				</div>
			<?php }} setLastOnline($userId); ?>			
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