<!--
	active_detail.php
	This webpage shows details of a specific active campaign and lets the user upload draft or report if needed.
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
		$ref = (string)$_GET["ref"]; 
		$thisCamp = getCampByRef($ref);
		$thisBrand = getBrandById($thisCamp['businessId']);
		$status = getStatusNumber(intval($thisCamp['id']), $userId);
		$thisDates = getDeadlineDates($thisCamp['id'], $userId);

		if($status >= 2 && $status < 7){
	?>
	<title><?= $thisCamp['title'] ?></title>
</head>
<body>
	<?php include 'top.php'; ?>
	<div  class = "title">
		<p>	Active Campaigns </p>
	</div>
	<div class = 'containerbod'>
		<div class = 'barholder'>
			<ul>
				<li class = "<?= getBarClassByStatus($status, 2) ?> pending-bar"><span class = 'frdot'>•<span class = 'dottext'>Draft Pending</span></span></li><li class = "<?= getBarClassByStatus($status, 3) ?> pending-bar">
				<span class = 'frdot'>•<span class = 'dottext'>Draft Review</span></span></li><li class = "<?= getBarClassByStatus($status, 4) ?> pending-bar">
				<span class = 'frdot'>•<span class = 'dottext'>Post Pending</span></span></li><li class = "<?= getBarClassByStatus($status, 5) ?> pending-bar">
				<span class = 'frdot'>•<span class = 'dottext'>Post Uploaded</span></span> <span class = "<?= getBarClassByStatus($status, 6) ?> pending-bar fldot">•<span id = 'lasttext' class = 'dottext'>Report Pending</span></span></li>
			</ul>
		</div>
		<div class = 'row'>
			<div class = 'lcont row col-sm-8'>
			<?php if(($status == 2 && draftIsSet($thisCamp['id'], $userId)) || (isset($thisDates['draftDate']) || isset($thisDates['postDate']))){ ?>
				<div class = 'makepanel mgtmore col-sm-12'>
				<?php if($status == 2 && draftIsSet($thisCamp['id'], $userId)){ ?>
					<span class = 'labela' style = 'display: block; font-size: 16px; margin-top: 10px;'>
						Your latest draft was declined by the brand.
					</span>
					<span class = 'labela' style = 'display: block; font-size: 16px;'> Feedback:</span>
					<span style = 'display: block;'><?= getDraftFeedback($thisCamp['id'], $userId) ?></span>
				<?php } ?>
				<?php if(isset($thisDates['draftDate']) || isset($thisDates['postDate'])){ ?>
					<span class = 'labela' style = 'display: block; font-size: 16px; margin-top: 10px;'> Your Dates:</span>
					<?php if(isset($thisDates['draftDate'])) { ?>
						<p class = 'date-det' <?php if(($thisDates['draftDate'] - time()) < 172800){ echo "style = 'color: #f32f2f'"; } ?>>
							Draft By: <?= date('d/m/Y',$thisDates['draftDate']) ?>
						</p> 
					<?php } ?>
					<?php if(isset($thisDates['postDate'])) { ?>
						<p class = 'date-det' <?php if(($thisDates['postDate'] - time()) < 172800){ echo "style = 'color: #f32f2f'"; } ?>>
							Post On: <?= date('d/m/Y',$thisDates['postDate']) ?>
						</p> 
					<?php } ?>
				<?php } ?>
				</div>
			<?php } ?>
				<div class = 'makepanel mgtmore col-sm-12'>
					<p class = 'sizeless makeyellow cmpbrf'><?= $thisCamp['title'] ?></p>
					<div class = 'row hidediv' id = 'pullcmp'>
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
							<p class = 'labela'>Price:</p>
							<p class = 'labelt'>HKD $<?= getActivePrice($thisCamp['id'], $userId) ?></p>
							<?php if($thisCamp['link'] != null){ ?>
								<p class='labela mggtop'>Website:</p>
								<p class='labelt'><?= $thisCamp['link'] ?></p>
							<?php } ?>
						</div>
					</div>
					<i id = 'briefdown' class="cmpbrf fa fa-chevron-down"></i>
				</div>

				<div class = 'makepanel mgtless col-sm-12 hidediv' id='branddt2'>
					<div class = 'sizeless makeyellow brandclick'> Brand Details </div>
					<div class = 'branddet hidediv'>
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
					<i class='branddown fa fa-chevron-down brandclick'></i>
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
						<img src = "<?="https://s3-ap-southeast-1.amazonaws.com/cloudbreakr-campaign/product-photos/" .  $thisCamp['productPhoto'] ?>" class = "campmage">
					<?php } ?>
				</div>

			</div>
			<div class = 'norightpad row col-sm-4'>
				<div class = 'makepanel mgtmore col-sm-12 hidediv' id='branddt'>
					<div class = 'sizeless makeyellow brandclick'> Brand Details </div>
					<div class = 'branddet hidediv'>
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
					<i class='branddown fa fa-chevron-down brandclick'></i>
				</div>

				<?php
					if($status == 2){
				?>
				<form class = 'makepanel mgtless col-sm-12' action="backend/controller.php" method = "post" enctype = "multipart/form-data" onsubmit = "return checkDraft()">
					<p class = 'labela sizeless' id = 'upload'>Upload Draft Post</p>
					<p class='suggestion'><i>Influencers are encouraged to strictly adhere to the content guidelines to ensure that their post is approved</i></p>
					<!--<div class = 'browse-btn mggtop'>Browse</div>-->
					<input type="file" name="draftUpload" id="draftUpload">
					<input type="hidden" name="stamp" value = "<?= $thisCamp['id'] . '_'. time() ?>">
					<input type = "hidden" name = "user" value = "<?= $userId ?>">
					<input type = "hidden" name = "camp" value = "<?= $thisCamp['id'] ?>">
					<p class = 'labela mggtop'>Caption:</p>
					<textarea rows='4' cols='31' name = 'caption' style = 'margin-left: 2px;' id = 'draft-caption'></textarea>
					<br>
					<br>
					<input type = 'submit' class = 'submit-draft-btn' name = 'submitDraft'>
				</form>
				<?php } ?>
				<?php
					if($status == 6){
				?>
					<form class = 'makepanel mgtless col-sm-12' method = 'post' action = "backend/controller.php" enctype = 'multipart/form-data' onsubmit = 'return checkReport()'>
						<p class = 'labela sizeless' id = 'upload'>Upload Report</p>
						<input type = 'file' name = 'reportUpload' id = 'report-upload'>
						<input type="hidden" name="stamp" value = "<?= $thisCamp['id'] . '_'. time() ?>">
						<input type = "hidden" name = "user" value = "<?= $userId ?>">
						<input type = "hidden" name = "camp" value = "<?= $thisCamp['id'] ?>">
						<br>
						<input type='submit' class = 'submit-draft-btn submit-report-btn' name = 'submitReport'>
					</form>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php } ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="navscript.js"></script>
	<script>
		function checkDraft()
		{
			if($("#draftUpload").val() == '')
			{
				alert("Error: You have not chosen a file to submit");
				return false;
     		}
     		else if($("#draft-caption").val() == '')
     		{
				alert("Error: Please enter a caption for your draft post");
				return false;
     		}
		}
		function checkReport()
		{
			if($("#report-upload").val() == '')
			{
				alert("Error: You have not chosen a file to submit");
				return false;
     		}
		}
	</script>
</body>
</html>
<?php 
	}
	else
	{
		header('Location: login.php');
	}
 ?>