<!--
	marketcamp.php
	This webpage shows details of a specific public campaign and lets the user apply to it.
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
		$pageTitle = 'Marketplace';
		include 'headfiles.php';
		include 'backend/db_conn.php'; 
		$ref = (string)$_GET["ref"]; 
		$thisCamp = getCampByRef($ref);
		$thisBrand = getBrandById($thisCamp['businessId']);
		
		if((!isRelated($thisCamp['id'], $userId)) && campIsPublic($thisCamp['id'])) 
		{
	?>
	<title><?= $thisCamp['title'] ?></title>
</head>
<body>
	<?php include 'top.php'; ?>
	<div  class = "title">
		<p>	Marketplace </p>
	</div>
	<div class = 'containerbod'>
		<div class ='row'>
			<div class = 'lcont row col-sm-8'>
				<div class = 'makepanel mgtmore col-sm-12'>
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
							<p class = 'labelt'>HKD $<?= getTierPrice($userId, $thisCamp) ?></p>
							<?php if($thisCamp['link'] != null){ ?>
								<p class='labela mggtop'>Website:</p>
								<p class='labelt'><?= $thisCamp['link'] ?></p>
							<?php } ?>
						</div>
					</div>
					<i id = 'briefdown' class="pcmpbrf fa fa-chevron-up"></i>
				</div>

				<div class = 'makepanel mgtless col-sm-12 hidediv branddt2' id='branddt2'>
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
						<img src = "<?= $thisCamp['productPhoto']?>" class = "campmage">
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

				<div class = 'nobgpanel col-sm-12 apply-panel' style = 'text-align: center'>
					<a><div class = 'pending-btn accept-btn' id = 'marketplace-apply-direct'>Apply</div></a>
					<a><p id = 'contact-brand'>Contact Brand</p></a>
				</div>
				<div class = 'col-sm-12 mgtless makepanel hidediv' id = 'contact-panel'>
					<p class = 'labela bigmargin'>Comments:</p>
					<textarea id="comments" style ='border: 1px solid #A9A9A9; margin-left: 2px; margin-top: 2px;'></textarea>
					<p class = 'labela mggtop'>Your Offer*: <span class = 'suggestion hidediv' id = 'validate' style = 'margin-left: 5px'>Please enter a valid price</span></p>
					<input type = 'text' id = 'offer-input' name = 'counter' style ='border: 1px solid #A9A9A9;'>
					<a><div class = 'offer-btn' id = 'marketplace-apply-counter'>Apply</div></a>
					<i id = 'close-contact' class = 'crsneg fa fa-close'></i>
				</div>
				<div class = 'col-sm-12 mgtless makepanel hidediv' id = 'applied-message'>
					<p class = 'labelt mggtop'>Your application has been sent to the brand. You can keep track of it in the <a href = 'pending_campaigns.php'>Pending Campaigns</a> section.</p>
				</div>
			</div>
		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="navscript.js"></script>
	<script type="text/javascript">
		$(function(){
			$('#marketplace-apply-direct').click(function(){
				$.post("backend/controller.php", 
					{
						function: 'applyToPublicCampDirect',
						campId: <?= $thisCamp['id'] ?>, 
						userId: <?= $userId ?>
					}, 
					function(responseTxt){
					if(responseTxt == 1)
					{
						console.log('success');
						$('.apply-panel').addClass('hidediv');
						$('#applied-message').fadeIn(150);
					}
					else
					{
						console.log(responseTxt);
					}
				});
			});

			$('#marketplace-apply-counter').click(function(){
				console.log('clicked');
				$.post("backend/controller.php", 
					{
						function: 'applyToPublicCampCounter',
						campId: <?= $thisCamp['id'] ?>, 
						userId: <?= $userId ?>, 
						counter: $('#offer-input').val(),
						message: $('#comments').val()
					},
					function(responseTxt){
					if(responseTxt == 1)
					{
						$('#contact-panel').hide();
						$('#apply-panel').hide();
						$("#applied-message").fadeIn(150);
					}
					else if(responseTxt == 2)
					{
						console.log(responseTxt);
						$('#validate').show();
					}
				});
			});	
		});
	</script>
<?php } ?>
</body>
</html>
<?php 
	}
	else
	{
		header('Location: login.php');
	}
 ?>