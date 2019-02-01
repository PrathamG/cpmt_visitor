<!--
	active_campaigns.php
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
		$pageTitle = 'Active Campaigns';
		include 'headfiles.php'; 
		include 'backend/db_conn.php'; 
		$active_campaigns = findActiveCamp($userId);
	?>
	<title>Active Campaigns</title>
</head>
<body>
<?php include 'top.php'; ?>
	<div  class = "title">
		<p>	Active Campaigns </p>
	</div>
	<div class = 'containerbod'>
		<div class = "activecamp">
			<?php
				if(empty($active_campaigns))
				{ 
			?>
				<div class = 'empty-div col-sm-12' style = 'text-align: center; margin-top: 3px; background-color: white;'>
					<p class = 'labelt'> You have no active campaigns at this time.<br>
						You can apply to public campaigns at the <a href = 'marketplace.php'>marketplace</a>.</p>
				</div>
			<?php 
				} 
				else
				{
					foreach($active_campaigns as $row)
					{
						$campaign = findCampById($row['campaignId']);
			?>
				<div class = "row campaigndet">
					<a href ="active_detail.php?ref=<?= $campaign['refId'] ?>"><div class = 'mob-link'></div></a>
					<div class = "campleft col-sm-8">
						<p class = "brandcamp">
							<a href = "active_detail.php?ref=<?= $campaign['refId'] ?>">
								<span><?= getBrandById($campaign['businessId'])['name'] ?></span>
							</a>
						<?php if($campaign['facebook'] == 1) { ?> <i class="fb fa fa-facebook-square"></i> <?php } ?>
						<?php if($campaign['instagram'] == 1) { ?><i class="inst fa fa-instagram"></i> <?php } ?></p>
						<p class = "titlecamp"><?= $campaign['title'] ?></p>
						<?php if(isset($row['draftDate'])) { ?>
						<p class = 'date-ntf' <?php if(($row['draftDate'] - time()) < 172800){ echo "style = 'color: #f32f2f'"; } ?>>		Draft By: <?= date('d/m/y',$row['draftDate']) ?>
						</p>
						<?php } ?>
						<?php if(isset($row['postDate'])) { ?>
						<p class = 'date-ntf' <?php if(($row['postDate'] - time()) < 172800){ echo "style = 'color: #f32f2f'"; } ?>>
							Post On: <?= date('d/m/y',$row['postDate']) ?>
						</p>
						<?php } ?>
						<?php if(!isset($row['postDate']) && !isset($row['draftDate'])) { ?>
							<p class = 'cdates'><?= date('d/m/Y',$campaign['startDate']) ?> - <?= date('d/m/Y',$campaign['endDate']) ?></p>
						<?php } ?>
					</div>
					<div class="campright col-sm-4">
						<div class = 'budget'>HKD $<?= $row['brandPrice'] ?></div>
					</div>
					<a href ="active_detail.php?ref=<?= $campaign['refId'] ?>"><i class="fa fa-chevron-right" id = 'detrow'></i></a>
					<div class = 'statusdisp'>
						<?php getActiveStatus($row['status']); ?>
						<?php
						if(isNotify($userId, $row['updatedAt'])){ ?> 
							<i class="notification fa fa-circle" style = 'font-size: 9px'></i>
						<?php } ?>
					</div>
				</div>	
			<?php }} setLastOnline($userId);?>		
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
