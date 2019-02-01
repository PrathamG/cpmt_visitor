<!--
	completed_detail.php
	This webpage shows details of a specific completed campaign.
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
		$ref = (string)$_GET["ref"]; 
		$thisCamp = getCampByRef($ref);
		$thisBrand = getBrandById($thisCamp['businessId']);		
		$status = getStatusNumber(intval($thisCamp['id']), $userId);

		if($status==7){
	?>
	<title><?= $thisCamp['title'] ?></title>
</head>
<body>
	<?php include 'top.php'; ?>
	<div  class = "title">
		<p>	Complete Campaigns </p>
	</div>
	<div class = 'containerbod'>
		<div class = 'row'>
			<div class = 'lcont row col-sm-8'>

				<div class = 'makepanel mgtmore col-sm-12' >
					<p class = 'sizeless makeyellow pcmpbrf'><?= $thisCamp['title'] ?></p>
					<div class = 'row' id = 'pullcmp'>
						<p class='mggtop labela'>Description:</p>
						<p class='labelt'>
							<?= nl2br($thisCamp['about']); ?>
						</p>
						<div class = 'leftc mggtop col-sm-6'>
							<p class = 'labela'>Location:</p>
							<p class = 'labelt'><?=  getLocationById($thisCamp['locationId']) ?></p>
							<p class='labela mggtop'>Campaign Dates:</p>
							<p class='labelt'><?= date('dS F Y',$thisCamp['startDate']) ?> - <?= date('dS F Y',$thisCamp['endDate']) ?></p>
							<p class="labela mggtop">Social Media Channels:</p>
							<?php if($thisCamp['facebook'] == 1) { ?>
								<p class="labelt"><i class='fa fa-facebook-official'></i> Facebook</p>
							<?php } ?>
							<?php if($thisCamp['instagram'] == 1) { ?>
								<p class="labelt"><i class='fa fa-instagram'></i> Instagram</p>
							<?php } ?>
						</div>
						<div class = 'pad leftc mggtop col-sm-6'>
							<p class = 'labela'>Price Offered:</p>
							<p class = 'labelt'>HKD $<?= getActivePrice($thisCamp['id'], $userId) ?></p>
							<?php if($thisCamp['link'] != null){ ?>
								<p class='labela mggtop'>Website:</p>
								<p class='labelt'><?= $thisCamp['link'] ?></p>
							<?php } ?>
						</div>
					</div>
					<i id = 'briefdown' class="pcmpbrf fa fa-chevron-up"></i>
				</div>

				<div class = 'makepanel mgtless col-sm-12 hidediv' id='branddt2'>
					<div class = 'sizeless makeyellow brandpnl'> Brand Details </div>
					<div class = 'branddet'>
						<div class ='row'>
							<div class = 'col-sm-8 nopadding'>
								<p class = 'labela' id='sabout'><?= $thisBrand['name'] ?> </p>
								<p class = 'labelt'><?= getCampType($thisBrand['businessTypeId']) ?></p>
							</div>
							<div class = 'col-sm-4 nopadding'>
								<img src ="<?= $thisBrand['profilePic'] ?>" class ='brand-logo'>
							</div>
						</div>
						<p class = 'mggtop labela'>About </p>
						<p class = 'labelt'> 
						<?= nl2br($thisBrand['about']) ?>
						</p>
						<?php if($thisBrand['cpFirstName'] != null){ ?>
							<p class = 'labela mggtop'>Recruiter </p>
							<p class = 'labelt'><?= $thisBrand['cpFirstName'] . " " . $thisBrand['cpLastName']  ?></p>
						<?php } ?>
					</div>
					<i class='brandpnl branddown fa fa-chevron-up'></i>
				</div>

				<div class = 'col-sm-12 mgtless makepanel' >
					<p class = 'sizeless makeyellow'>Campaign Requirements</p>
					<div class = 'row'>
						<?php 
							$hashtagArr = getCampaignHashtag($thisCamp);
							if(!empty($hashtagArr)){
						?>
							<div class = "nopadding col-sm-6">
								<p class = "labela mggtop">Campaign Hashtags:</p>
								<p class = "labelt fullwidth">
									<?php
										$hashtagCount = 0;
										foreach($hashtagArr as $row)
										{	
											$hashtagCount += 1;
											echo $row['hashtag'] . '&nbsp';
											if($hashtagCount == 4)
											{
												echo '<br>';
												$hashtagCount = 0;
											}
										}
									?>
								</p>
							</div>
						<?php } ?>
						<div class = "nopadding col-sm-6">
							<?php if(isset($thisCamp['contentGuide'])){ ?>
							<p class = "labela mggtop">Content Guideline:</p>
							<a href = '<?= $thisCamp['contentGuide'] ?>' target = '_blank'>
								<p class = "labelt" id = 'download-brief'>View File</p>
							</a>
							<?php } ?>
						</div>
					</div>
					<?php if(isset($thisCamp['productPhoto'])){ ?>
						<p class = "labela mggtop">Product Photo:</p>
						<img src = "<?="https://s3-ap-southeast-1.amazonaws.com/cloudbreakr-campaign/product-photos/" . $thisCamp['productPhoto'] ?>" class = "campmage">
					<?php } ?>
				</div>
			</div>

			<div class = 'norightpad row col-sm-4'>
				
				<div class = 'makepanel mgtmore col-sm-12 hidediv' id='branddt'>
					<div class = 'sizeless makeyellow brandpnl'> Brand Details </div>
					<div class = 'branddet'>
						<div class = 'row'>
							<div class = 'col-sm-8 nopadding'>
								<p class = 'labela' id='sabout'><?= $thisBrand['name'] ?></p>
								<p class = 'labelt'><?= getCampType($thisBrand['businessTypeId']) ?></p>
							</div>
							<div class = 'col-sm-4 nopadding'>
								<img src ="<?= $thisBrand['profilePic'] ?>" class ='brand-logo'>
							</div>
						</div>
						<p class = 'mggtop labela'>About </p>
						<p class = 'labelt'> 
						<?= nl2br($thisBrand['about']) ?>
						</p>
						<?php if($thisBrand['cpFirstName'] != null){ ?>
							<p class = 'labela mggtop'>Recruiter </p>
							<p class = 'labelt'><?= $thisBrand['cpFirstName'] . " " . $thisBrand['cpLastName']  ?></p>
						<?php } ?>
					</div>
					<i class='brandpnl branddown fa fa-chevron-up'></i>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
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