<!--
	marketplace.php
	This webpage lists all public campaigns not associated with the influencer.
-->
<?php 
session_start();
if(isset($_SESSION['userId']))
{
	$pageTitle = 'Marketplace';
	$userId = $_SESSION['userId'];
	include 'headfiles.php'; 
	include 'backend/db_conn.php'; 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Marketplace</title>
</head>
<body>
<?php include 'top.php'; ?>
	<div  class = "title">
		<p>	Marketplace </p>
	</div>
	<div class = 'containerbod'>
		<div class = "activecamp">
			<?php
				$public_campaigns = getMarketplace($_SESSION['location']);
				if(empty($public_campaigns))
				{ 
			?>
				<div class = 'empty-div'>
					<p class = 'labelt'> There are no public campaigns available at this time. </p>
				</div>
			<?php 
				} 
				else
				{
					foreach($public_campaigns as $row){ 
					if(!isRelated($row['id'], $userId)){
			?>
					
				<div class = 'row campaigndet'>
				<a href = "<?php echo 'marketcamp.php?ref=' . $row['refId']; ?>"><div class = 'mob-link'></div></a>
					<div class = 'campleft col-sm-8'>
						<p class = 'brandcamp'><a href = "<?php echo 'marketcamp.php?ref=' . $row['refId']; ?>"><span><?= getBrandById($row['businessId'])['name'] ?></span></a>
						<?php if($row['facebook'] == 1){echo "<i class='fb fa fa-facebook-square'></i>";} 
						if($row['instagram'] == 1){echo "<i class='inst fa fa-instagram'></i>";} ?></p>
						<p class = 'titlecamp'><?= $row['title']; ?></p>
						<p class = 'cdates'><?= date('d/m/Y', $row['startDate']); ?> - <?= date('d/m/Y', $row['endDate']); ?></p>
					</div>
					<div class='campright campright-market col-sm-4'>
						<div class = 'budget'>HKD $<?= getTierPrice($userId, $row) ?></div>
					</div>
					<a href = "<?php echo 'marketcamp.php?ref=' . $row['refId']; ?>"><i class='fa fa-chevron-right' id = 'detrow'></i></a>
				</div>

			<?php }}} ?>	
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