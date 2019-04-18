<!--
	pending_detail.php
	This webpage shows details of a specific pending campaign and lets the user respond to offers.
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
		$ref = (string)$_GET["ref"];
		$thisCamp = getCampByRef($ref);
		$thisBrand = getBrandById($thisCamp['businessId']);
		$status = getStatusNumber(intval($thisCamp['id']), $userId);
		if($status == 1 || $status === 0){
	?>
	<title><?= $thisCamp['title'] ?></title>
</head>
<body>
	<?php include 'top.php'; ?>
	<div  class = "title">
		<p>	Pending Campaigns </p>
	</div>
	<div class = 'containerbod'>
		<div class = 'row'>
			<div class = 'lcont row col-sm-8'>
				<?php if($status == 1 && $feedback = brandFeedbackIsSet($thisCamp['id'], $userId)){ ?>
					<div class = 'makepanel mgtmore col-sm-12'>
						<span class = 'labela' style = 'display: block; font-size: 16px; margin-top: 10px;'>
							The brand has sent you another offer.
						</span>
						<span class = 'labela' style = 'display: block; font-size: 16px;'> Message:</span>
						<span style = 'display: block;'><?= $feedback ?></span>
					</div>
				<?php } ?>
				<div class = 'makepanel mgtmore col-sm-12'>
					<div class = 'statusbox pcmpbrf'><?php getPendingStatus($status); ?></div>
					<p class = 'lesswidth sizeless makeyellow pcmpbrf'><?= $thisCamp['title'] ?></p>
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
					<i id = 'briefdown' class="pcmpbrf fa fa-chevron-up" style = 'top: 22px'></i>
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
					<i class='branddown fa fa-chevron-up brandpnl'></i>
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
						<img src = "<?=$thisCamp['productPhoto'] ?>" class = "campmage">
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
					<i class='branddown fa fa-chevron-up brandpnl'></i>
				</div>
				<?php if($status == 1){ ?>
					<div id ='respond-buttons' class = 'nobgpanel col-sm-12'>
						<a><div class = 'pending-btn accept-btn' id = 'accept-invite'>Accept</div></a>
						<a><div class = 'pending-btn counter-btn' id = 'counter-btn'>Negotiate</div></a>
						<a><div class = 'pending-btn reject-btn' id = 'reject-invite'>Decline</div></a>
					</div>
					<div id = 'negotiate-form' class = 'makepanel mgtless col-sm-12 hidediv'>
						<p class = 'labela bigmargin'>Brand's Offer:</p>
						<p class = 'labelt'>HKD $<?= getActivePrice($thisCamp['id'], $userId) ?></p>
						<p class = 'labela mggtop'>Your Offer*: <span class = 'suggestion hidediv' id = 'validate' style = 'margin-left: 5px'>Please enter a valid price</span></p>
						<input type = 'text' id = 'offer-input' name = 'counter' style ='border: 1px solid #A9A9A9'>
						<p class = 'labela mggtop'>Comments: </p> 
						<textarea cols = "20" name = 'comment' class = 'neg-comment'></textarea>
						<a><div name = 'submit-counter' class = 'offer-btn' id = 'neg-invite'>Submit</div></a>
						<i id = 'close-neg' class = 'crsneg fa fa-close'></i>
					</div>
					<div class = 'col-sm-12 mgtless makepanel hidediv' id = 'applied-message'>
						<p class = 'labelt mggtop'>Your application has been sent to the brand. You can keep track of it in the <a href = 'pending_campaigns.php'>Pending Campaigns</a> section.</p>
					</div>
					<div class = 'col-sm-12 mgtless makepanel hidediv' id = 'accept-message'>
						<p class = 'labelt mggtop'>Congrats, you have joined a new campaign! You can keep track of it in the <a href = 'active_campaigns.php'>Active Campaigns</a> section.</p>
					</div>
					<div class = 'col-sm-12 mgtless makepanel hidediv' id = 'reject-message'>
						<p class = 'labelt mggtop'>This campaign has been removed.</p>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php } ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="navscript.js"></script>
	<script type="text/javascript">
		$(function(){
			$('#accept-invite').click(function(){
				$.post("backend/controller.php", 
					{
						function: 'acceptPendingInvitation',
						campId: <?= $thisCamp['id'] ?>, 
						userId: <?= $userId ?>
					},
					function(responseTxt){
					if(responseTxt == 1)
					{
						$('#respond-buttons').hide();
						$('#accept-message').show();
					}			
				});
			});
			$('#reject-invite').click(function(){
				$.post("backend/controller.php", 
					{
						function: 'rejectPendingInvitation',
						campId: <?= $thisCamp['id'] ?>, 
						userId: <?= $userId ?>, 
					},
					function(responseTxt){
					if(responseTxt == 1)
					{
						$('#respond-buttons').hide();
						$('#reject-message').show();
					}
				});
			});
			$('#neg-invite').click(function(){
				$.post("backend/controller.php", 
					{
						function: 'negotiatePendingInvitation',
						campId: <?= $thisCamp['id'] ?>, 
						userId: <?= $userId ?>,
						comment: $('.neg-comment').val(),
						influencerPrice: $('#offer-input').val(),
					},
					function(responseTxt){
					if(responseTxt == 1)
					{
						$('#negotiate-form').hide();
						$('#applied-message').show();
					}
					else if(responseTxt == 2)
					{
						$('#validate').show();
					}
				});
			});
		});
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