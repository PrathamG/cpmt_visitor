<!--
	admin_detail.php
	This webpage displays details about a specific campaign and influencers associated with it.
	It also allows the admin to take certain actions regarding influencers(negotiate applications, accept/reject drafts, view reports, etc)
-->
<?php
session_start();
if($_SESSION['adminUserId'] == 1350) //Verify admin logged in
{
	$userId = $_SESSION['adminUserId'];
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="datepick.css">
	<?php

		$pageTitle = 'Admin: Current Campaigns';
		include 'headfiles.php';
		include '../backend/db_conn.php';

		$ref = (string)$_GET["ref"];
		$thisCamp = getCampByRef($ref);
		$thisBrand = getBrandById($thisCamp['businessId']);

		$pendingInfluencers = getPendingInfluencers($thisCamp['id']);
		$approvedInfluencers = getApprovedInfluencers($thisCamp['id']);
		$completeInfluencers = getCompleteInfluencers($thisCamp['id']);

		$count = 0;
	?>
	<meta name='viewport' content='width=1250, initial-scale=1'>
	<title><?='Admin: '. $thisCamp['title'] ?></title>
</head>
<body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="../navscript.js"></script>
	<?php include  'admin_top.php' ?>
	<div  class = "title">
		<p id = 'admin-title'> Current Campaign </p>
	</div>
	<div class = 'containerbod'>
		<div class = 'row'>
			<div class = 'lcont col-sm-8'>
				<div class = 'makepanel mgtless col-sm-12'>
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
							<p class = 'labela'>Budget:</p>
							<p class = 'labelt'>HKD $<?= $thisCamp['totalBudget'] ?></p>
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

				<div class = 'reqclick col-sm-12 mgtless makepanel' >
					<p class = 'sizeless makeyellow'>Campaign Requirements</p>
					<div class = 'reqpnl row hidediv'>
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
						<?php if(isset($thisCamp['contentGuide'])){ ?>
						<div class = "nopadding col-sm-6">
							<p class = "labela mggtop">Content Guideline:</p>
							<a href = "<?= '../' .  $thisCamp['contentGuide'] ?>" target = '_blank'>
								<p class = "labelt" id = 'download-brief'>View File</p>
							</a>
						</div>
						<?php } ?>
						<?php if(isset($thisCamp['productPhoto'])){ ?>
						<br>
						<div class = "nopadding col-sm-12">
							<p class = "labela">Product Photo:</p>
							<img src = "<?=$thisCamp['productPhoto'] ?>" class = "campmage">
						</div>
						<?php } ?>
					</div>
					<i id = 'reqdown' class="reqdown fa fa-chevron-down"></i>
				</div>

				<div class = 'col-sm-9 mgtless makepanel'>
					<p class = 'sizeless makeyellow'>Invite Influencers</p>
					<div class = 'invpnl row hidediv'>
					<form method = "post" action = "invite.php?ref=<?= $thisCamp['refId'] ?>" style = 'padding-top: 10px'>
						<textarea name = "influencers" cols= "50" rows = "5" style = "overflow-y: scroll;"></textarea>
						<br>
						<button type="submit" class = 'submit-invite-list'>Submit</button>
					</form>
					</div>
					<i class='fa fa-chevron-down invclick'></i>
				</div>
			</div>
			<div class = 'norightpad row col-sm-4'>
				<div class = 'makepanel mgtless col-sm-12 hidediv' id='branddt'>
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
				<form class = 'makepanel mgtless col-sm-12' action="admin_controller.php" method = "post" enctype = "multipart/form-data" onsubmit = 'return checkGuide()'>
					<div class = 'makeyellow'>Upload Content Guide</div>
					<input type="file" name="uploadGuide" id="uploadGuide">
					<input type = "hidden" id="cId" name="cId" value = "<?= $thisCamp['id'] ?>">
					<input type = 'submit' class = 'mggtop submit-draft-btn' name = 'submitGuide'>
				</form>
			</div>
		</div>
	</div>
	<div class = 'noradius makepanel mgtless'>
		<div class = 'select-inftype'>
			Type:
			<select style = 'margin-left: 15px' class = 'influencer-type-select'>
				<option value="pending" selected>Pending</option>
				<option value="approved">Approved</option>
				<option value="complete">Complete</option>
			</select>
		</div>
		<div class = 'influencer-title'>
			Influencers
		</div>

		<div class = 'switch-panel pending-panel mggtop'>
			<table style="width:100%">
				<tr style = 'border-top: 1px solid black; height: 35px'>
					<th rowspan="2" colspan="1" style="width: 5%; padding-left: 20px;" class="text-center">No.</th>
					<th rowspan="2" colspan="1" style="width: 14%;">Influencer</th> 
					<th rowspan="2" colspan="1" style="width: 5%;" class="text-center">Status</th>
					<th rowspan="2" colspan="1" style="width: 5%;" class="text-center">Tier</th>
					<th rowspan="1" colspan="2" style="width: 10%;" class="text-center">Followers</th>
					<th rowspan="1" colspan="2" style="width: 10%;" class="text-center">Engagement(%)</th>
					<th rowspan="2" colspan="1" style="width: 8%; padding-left: 10px;">Brand Price</th>
					<th rowspan="2" colspan="1" style="width: 9%;">Influencer Price</th>
					<th rowspan="2" colspan="1" style="width: 4%;">Margin</th>
					<th rowspan="2" colspan="1" style="width: 30%; font-weight: normal; padding-left: 10px;">
						<form method = "get" action = "<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
							<input type="hidden" name="ref" value="<?= $thisCamp['refId'] ?>">
							<input type="hidden" name="type" value="pending">
							Sort By:
							<select style = 'margin-left: 3px; margin-right: 5px' name = 'sort' id="pen-sort-select">
								<option value="date">Date</option>
								<option value="status">Status</option>
								<option value="ig_follower">IG Followers</option>
								<option value="fb_follower">FB Followers</option>
								<option value="ig_engagement">IG Engagement</option>
								<option value="fb_engagement">FB Engagement</option>
								<option value="price">Brand Price</option>
								<option value="inf_price">Influencer Price</option>
								<option value="margin">Margin</option>
							</select>
							Order:
							<select style = 'margin-left: 3px;' name='dir' id="pen-order-select">
								<option value="asc">Ascending</option>
								<option value="desc">Descending</option>
							</select>
							<button type="submit">Go</button>
						</form>
					</th>
				</tr>
				<tr>
					<th rowspan="1" colspan="1" class="subheader text-center">IG</th>
					<th rowspan="1" colspan="1" class="subheader text-center">FB</th>
					<th rowspan="1" colspan="1" class="subheader text-center">IG</th>
					<th rowspan="1" colspan="1" class="subheader text-center">FB</th>
				</tr>
			<?php
				$pcount = 0;
				foreach($pendingInfluencers as $row)
				{
					$pcount += 1;
					$count += 1;
			?>
				<tr>
					<td style = 'padding-left: 20px' class="text-center"><?= $pcount . '.' ?></td>
					<td>
						<img src = "<?= getDbInfluencerPicByIgId($row['ig_user_id']) ?>" class = "influencer-panel-pic" id = 'img-<?= $count-1 ?>' onerror = "pullInstaDp(<?= $row['ig_user_id'] ?>, <?= $count - 1 ?>)">
						<?= convertEmoji($row['name']) ?>
					</td>
					<td class="text-center"> <?= getAdminStatus($row['status']) ?> </td>
					<td class="text-center">
						<span class = 'influencer-tier'> <?= getTierById($row['influencerId'])?> </span>
					</td>
					<td class="text-center"><?= $row['followerCount'] ?></td>
					<td class="text-center"><?= $row['fanCount'] ?></td>
					<td class="text-center"><?= round($row['igEngagementRate'], 2) ?></td>
					<td class="text-center"><?= round($row['engagementRate'], 2) ?></td>
					<td style="padding-left: 10px;"> <?= $row['brandPrice']?> </td>
					<td> <?= $row['influencerPrice'] ?> </td>
					<td> <?= $row['brandPrice'] - $row['influencerPrice'] ?> </td>
					<td>
						<?php if($row['status'] === 0 && !empty($row['message'])){ ?>
						<i class = "fa fa-envelope-o show-message" id = '<?= $pcount - 1 ?>'></i>
						<div class = 'com-tooltip' id = "tooltip-<?= $pcount - 1 ?>">
							<?= $row['message'] ?>
						<?php } ?>
						</div>
						<?php if($row['status'] === 0){ ?>
						<div <?php if($row['status'] !== 0 || empty($row['message'])){echo "style='margin-left:18px'";} ?> class = 'admin-btns-pending' id = 'admin-btns-pending-<?= $pcount - 1 ?>'>
							<span class ='admin-btn admin-accept' id = '<?= $pcount - 1 ?>'>Accept</span>
							<span class ='admin-btn admin-neg' id = '<?= $pcount - 1 ?>'>Negotiate</span>
							<span class ='admin-btn admin-reject' id = '<?= $pcount - 1 ?>'>Decline</span>
						</div>
						<div class = 'message-panel' id = 'msg-<?= $pcount - 1 ?>'>
							<?= $row['message'] ?>
						</div>
						<div class = 'neg-panel-admin hidediv' id = 'neg-panel-admin-<?= $pcount - 1 ?>'>
							Price:
							<span class = 'suggestion hidediv' id = 'validate-<?= $pcount-1 ?>' style = 'margin-left: 8px'>
								Please enter a valid price
							</span><br>
							<input type = 'text' id = 'neg-input-<?= $pcount-1 ?>'><br>
							Comments:<br>
							<textarea cols = "20" style = 'vertical-align: text-top' id = 'neg-comment-<?= $pcount-1 ?>'></textarea>
							<span class ='admin-btn neg-submit-admin' id = '<?= $pcount-1 ?>'>Submit</span>
							<i class = 'crsneg-pending-admin fa fa-close' id = <?= $pcount - 1 ?>></i>
						</div>
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
			</table>
		</div>

		<div class = 'switch-panel approved-panel mggtop'>
			<table style = 'width: 100%'>
				<tr style="height: 50px; border-top: 1px solid black; border-bottom: 1px solid black;"">
					<th colspan="1" style="width: 5%; padding-left: 20px" class="text-center">No.</th>
					<th colspan="1" style="width: 15%;">Influencer</th> 
					<th colspan="1" style="width: 8%;" class="text-center">Status</th>
					<th colspan="1" style="width: 5%;" class="text-center">Tier</th>
					<th colspan="1" style="width: 6%;"> Price </th>
					<th colspan="1" style="width: 13%;">Draft Date</th>
					<th colspan="1" style="width: 13%;">Post Date</th>
					<th colspan="1" style="width: 35%; font-weight: normal">
						<form method = "get" action = "<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
							<input type="hidden" name="ref" value="<?= $thisCamp['refId'] ?>">
							<input type="hidden" name="type" value="approved">
							Sort By:
							<select style = 'margin-left: 3px; margin-right: 5px' name = 'sort' id="app-sort-select">
								<option value="date">Date</option>
								<option value="status">Status</option>
								<option value="price">Price</option>
								<option value="draft">Draft Date</option>
								<option value="post">Post Date</option>
							</select>
							Order:
							<select style = 'margin-left: 3px;' name='dir' id="app-order-select">
								<option value="asc">Ascending</option>
								<option value="desc">Descending</option>
							</select>
							<button type="submit">Go</button>
						</form>
					</th>
				</tr>
				<?php
				$acount = 0;
				foreach($approvedInfluencers as $row)
				{
					$acount += 1;
					$count += 1;
				?>
				<tr>
					<td style = 'padding-left: 20px' class="text-center"><?= $acount . '.' ?></td>
					<td>
						<img src = "<?= getDbInfluencerPicByIgId($row['ig_user_id']) ?>" class = "influencer-panel-pic" id = 'img-<?= $count-1 ?>' onerror = "pullInstaDp(<?= $row['ig_user_id'] ?>, <?= $count - 1 ?>)">
						<?= convertEmoji($row['name']) ?>
					</td>
					<td class="text-center"> <?= getAdminStatus($row['status']) ?> </td>
					<td class="text-center">
						<span class = 'influencer-tier'> <?= getTierById($row['influencerId'])?> </span>
					</td>
					<td> <?= $row['brandPrice'] ?> </td>
					<td> 
						<input type = 'text' class = 'datepicker' style = "width: 90px" id = 'draft-<?=$acount - 1?>' <?php if($row['draftDate'] != 0){ echo "value = ".date('d/m/Y',$row['draftDate']); }?>>
						<button value = 'Set' class = 'set-draft' id = '<?= $acount-1 ?>'> Set </button>
					</td>
					<td> 
						<input type = 'text' class = 'datepicker' style = "width: 90px" id = 'post-<?=$acount - 1?>' <?php if($row['postDate'] != 0){ echo "value = ".date('d/m/Y',$row['postDate']); }?>>
						<button value = 'Set' class = 'set-post' id = '<?= $acount-1 ?>'> Set </button>
					</td>
					<td>
						<?php if($row['status'] < 6 && $row['status'] > 2){ ?>
						<a href = 'draft.php?dId=<?= $row['draftId'] ?>&uId=<?= $row['influencerId'] ?>' target="_blank" >
							View Draft
						</a>
						<?php } ?>
						<?php if($row['status'] == 3){ ?>
						<div class = 'hidediv reject-draft-panel' id = 'reject-draft-panel-<?= $acount-1 ?>' style = 'padding: 10px 0'>
							Feedback:
							<textarea cols = "15" style = 'vertical-align: text-top' id = 'feedback-input-<?= $acount-1 ?>'>
							</textarea>								
							<br><span class ='admin-btn reject-draft-submit' id = '<?= $acount-1 ?>'>Submit</span>
							<i class = 'crsneg-approved-admin fa fa-close' id = '<?= $acount - 1 ?>'></i>
						</div>
						<div class = 'admin-btns-approved' id = 'admin-btns-approved-<?= $acount-1 ?>'>
							<span class ='admin-btn accept-draft' id = '<?=$acount-1?>'>Accept</span>
							<span class ='admin-btn reject-draft' id = '<?=$acount-1?>'>Decline</span>
						</div>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>
			</table>
		</div>

		<div class = 'switch-panel complete-panel mggtop'>
			<table style="width:100%">
				<tr style = 'border-top: 1px solid black; border-bottom: 1px solid black; height: 50px;'>
					<th colspan="1" style="width: 7%; padding-left: 20px;" class="text-center">No.</th>
					<th colspan="1" style="width: 20%;">Influencer</th> 
					<th colspan="1" style="width: 8%;" class="text-center">Tier</th>
					<th colspan="1" style="width: 8%;">Price</th>
					<th colspan="1" style="width: 57%; font-weight: normal; padding-left: 10px;">
						<form method = "get" action = "<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" style = 'margin-left: 90px'>
							<input type="hidden" name="ref" value="<?= $thisCamp['refId'] ?>">
							<input type="hidden" name="type" value="complete">
							Sort By:
							<select style = 'margin-left: 3px; margin-right: 5px' name = 'sort' id="com-sort-select">
								<option value="date">Date</option>
								<option value="price">Price</option>
							</select>
							Order:
							<select style = 'margin-left: 3px;' name='dir' id="com-order-select">
								<option value="asc">Ascending</option>
								<option value="desc">Descending</option>
							</select>
							<button type="submit">Go</button>
						</form>
					</th>
				</tr>
			<?php
				$ccount = 0;
				foreach($completeInfluencers as $row)
				{
					$ccount += 1;
					$count += 1;
			?>
				<tr>
					<td style = 'padding-left: 20px' class="text-center"><?= $ccount . '.' ?></td>
					<td>
						<img src = "<?= getDbInfluencerPicByIgId($row['ig_user_id']) ?>" class = "influencer-panel-pic" id = 'img-<?= $count-1 ?>' onerror = "pullInstaDp(<?= $row['ig_user_id'] ?>, <?= $count - 1 ?>)">
						<?= convertEmoji($row['name']) ?>
					</td>
					<td class="text-center">
						<span class = 'influencer-tier'> <?= getTierById($row['influencerId'])?> </span>
					</td>
					<td><?= $row['brandPrice'] ?></td>
					<td>
						<a href = "<?= $row['reportPath'] ?>" target="_blank" >
							View Report
						</a>
					</td>
				</tr>
			<?php } ?>
			</table>
		</div>

	</div>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
		integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
		crossorigin="anonymous">
	</script>
	<script>

  		$(function(){
   			$( ".datepicker" ).datepicker(
   			{
  				dateFormat: "dd/mm/yy",
  				duration: 'fast',
  				prevText: "◄",
  				nextText: "►",
  				ignoreReadonly: true,
			});

			<?php
				if(isset($_GET['type']))
				{
					if($_GET['type'] == 'approved')
					{
			?>
						$('#app-sort-select').val('<?= $_GET['sort'] ?>');
						$('#app-order-select').val('<?= $_GET['dir'] ?>');
						$('.influencer-type-select').val('approved');
			<?php 	}
					elseif($_GET['type'] == 'complete')
					{
			?>			
						console.log('Good news');
						$('#com-sort-select').val('<?= $_GET['sort'] ?>');
						$('#com-order-select').val('<?= $_GET['dir'] ?>');
						$('.influencer-type-select').val('complete');
			<?php	}
					else
					{
			?>			
						$('#pen-sort-select').val('<?= $_GET['sort'] ?>');
						$('#pen-order-select').val('<?= $_GET['dir'] ?>');
						$('.influencer-type-select').val('pending');
			<?php	}
				}
				else
				{
					echo "$('.influencer-type-select').val('pending');";
				} 
			?>
			adminPanelControl();

			$(".influencer-type-select").change(function(){
				
				adminPanelControl();
			});

			$('.admin-accept').click(function(){
				var myid = this.id;
				$.post
				(
					"admin_controller.php",
					{
						function: 'adminAcceptApplication',
						index: myid,
						campId: '<?= $thisCamp['id'] ?>',
						influencers: <?php echo json_encode($pendingInfluencers); ?>
					},
					function(responseText){
						if(responseText == 1)
						{
							$('#admin-btns-pending-' + myid).html('This influencer was accepted to the campaign.');
						}
						else
						{
							$('#admin-btns-pending-' + myid).html('An error occurred');
						}
					}
				);
			});

			$('.admin-reject').click(function(){
				var index = this.id;
				$.post
				(
					"admin_controller.php",
					{
						function: 'adminRejectApplication',
						index: index,
						campId: '<?= $thisCamp['id'] ?>',
						influencers: <?php echo json_encode($pendingInfluencers); ?>
					},
					function(responseText){
						if(responseText == 1)
						{
							$('#admin-btns-pending-' + index).html('This influencer was removed from the campaign.');
						}
						else
						{
							$('#admin-btns-pending-' + index).html('An error occurred');
						}
					}
				);
			});
			$('.neg-submit-admin').click(function(){
				var index = this.id;
				$.post
				(
					"admin_controller.php",
					{
						function: 'adminCounterOffer',
						index: index,
						price: $('#neg-input-'+index).val(),
						comment: $('#neg-comment-'+index).val(),
						campId: '<?= $thisCamp['id'] ?>',
						influencers: <?php echo json_encode($pendingInfluencers); ?>
					},
					function(responseText){
						if(responseText == 1)
						{
							$('#neg-panel-admin-' + index).html('You offer was sent to the influencer.');
						}
						else if(responseText == 2)
						{
							$('#validate-' + index).show();
						}
						else
						{
							$('#neg-panel-admin-' + index).html('An error occurred');
						}
					}
				);
			});
			$('.set-draft').click(function(){
				var myid = this.id;
				var date;
				var myArr = <?=json_encode($approvedInfluencers)?>;
				//Regex for dd/mm/yyyy validation
				var pattern = /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/;
				if(pattern.test($("#draft-"+myid).val()) && (date = Date.parse($("#draft-"+myid).datepicker( "getDate" ))/1000))
				{
					$.post(
						"admin_controller.php",
						{
							function: "setDraftDate",
							index: myid,
							date: date,
							infId: myArr[myid]["influencerId"],
							campaignId: <?= $thisCamp['id']?>,
						},
						function(responseText)
						{
							if(responseText == 1)
							{
								console.log('done');
								$("#" + myid + ".set-draft").attr("disabled", "disabled");
								$("#" + myid + ".set-draft").css("background-color", "#12cc12");
							}
							else
							{
								alert("Invalid action");
							}
						}
					);
				}
				else
				{
					alert("Please enter a valid date");
				}
			});
			$('.set-post').click(function(){
				var myid = this.id;
				var date;
				var myArr = <?=json_encode($approvedInfluencers)?>;
				//Regex for dd/mm/yyyy validation
				var pattern = /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/; 

				if(pattern.test($("#post-"+myid).val()) && (date = Date.parse($("#post-"+myid).datepicker( "getDate" ))/1000))
				{
					$.post(
						"admin_controller.php",
						{
							function: "setPostDate",
							index: myid,
							date: date,
							infId: myArr[myid]["influencerId"],
							campaignId: <?= $thisCamp['id']?>,
						},
						function(responseText)
						{
							if(responseText == 1)
							{
								console.log('done');
								$("#" + myid+".set-post").attr("disabled", "disabled");
								$("#" + myid+".set-post").css("background-color", "#12cc12");
							}
							else
							{
								alert("Invalid action");
							}
						}
					);
				}
				else
				{
					alert("Please enter a valid date");
				}
			});
			$('.accept-draft').click(function(){
				var index = this.id;
				$.post
				(
					"admin_controller.php",
					{
						function: 'acceptDraft',
						index: index,
						campId: '<?= $thisCamp['id'] ?>',
						influencers: <?php echo json_encode($approvedInfluencers); ?>
					},
					function(responseText){
						if(responseText == 1)
						{
							$('#admin-btns-approved-' + index).html("This influencer's draft was accepted.");
						}
						else
						{
							$('#admin-btns-approved-' + index).html('An error occurred');
						}
					}
				);
			});
			$('.reject-draft-submit').click(function(){
				var index = this.id;
				$.post
				(
					"admin_controller.php",
					{
						function: 'rejectDraft',
						index: index,
						campId: '<?= $thisCamp['id'] ?>',
						feedback: $('#feedback-input-' + index).val(),
						influencers: <?php echo json_encode($approvedInfluencers); ?>
					},
					function(responseText){
						if(responseText == 1)
						{
							$('#reject-draft-panel-' + index).html("This influencer's draft was rejected.");
						}
						else
						{
							$('#reject-draft-panel-' + index).html('An error occurred');
						}
					}
				);
			});
		});
	</script>
</body>
</html>
<?php 
	}
	else
	{
		header('location: index.php');
	} 
?>
