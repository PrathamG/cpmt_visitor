<!--
	profile.php
	This webpage shows an influencer's details and insights.
-->
<?php
session_start();
if($_SESSION['adminUserId'] == 1350) //Verify admin logged in
{
	$userId = $_GET['id'];
?>

<!DOCTYPE html>
<html>
<head>
	<?php 
		$pageTitle = 'Admin: Influencer Profile';
		include 'headfiles.php';
		include '../backend/db_conn.php';
		$thisInfluencer = getInfluencerById($userId);
		$thisInfluencerIg = getInfluencerIgById($userId);
		$thisInfluencerFb = getInfluencerFbById($userId);
		if($thisInfluencer['infPower'] <=3){
			$level = "Rookie";
		} else if($thisInfluencer['infPower'] <=5){
			$level = "Amateur";
		} else if($thisInfluencer['infPower'] <=7){
			$level = "Hotshot";
		} else if($thisInfluencer['infPower'] <=9){
			$level = "Expert";
		} else {
			$level = "Super Star";
		}
	?>
	<title>Admin: Influencer Profile</title>
</head>
<body>
	<?php include 'admin_top.php'; ?>
	<div  class = "title">
		<p id = 'admin-title'> Influencer Profile </p>
	</div>
	<div class = 'containerbod'>
		<div class = 'makepanel row mgtmore' style = 'border-radius: 0px' id = 'profile-intro-mob'>
			<div class = 'col-xs-4 nopadding'>
				<img src = "<?= getInfluencerPicByIgId($thisInfluencer['ig_user_id']) ?>" class = 'profile-dp'> 
			</div>
			<div class = 'col-xs-4 nopadding'  style = 'padding-left: 0px'>
				<p class = 'intro-name'> 
					<?= convertEmoji($thisInfluencer['name']) ?> 
				</p>
				<p class = 'intro-interest'>
					<?= getInterestById($thisInfluencer['identityId']) ?>
				</p>
				<img src = 'https://lipis.github.io/flag-icon-css/flags/4x3/hk.svg' class='flagprof'>
			</div>
			<div class = 'col-xs-4 nopadding gauge-container' style = 'text-align: center'> 
				<div class = 'infpower'> Influence Power<sup>TM</sup></div>
				<div class="demo" style = 'margin-top: 7px;'></div>
				<div class = 'valuelabel'> <span><?= $thisInfluencer['infPower'] ?></span>/10 </div>
				<div class = 'intro-level'><?= $level ?></div>
			</div>
		</div>
		<div class = 'lcont row col-sm-8'>
			<div class = 'row mgtless col-sm-12 makepanel'>
				<div class = 'col-sm-12 nopadding' id = 'admin-profile-web'>
					<div class = 'col-xs-3 nopadding'>
						<img src = "<?= getInfluencerPicByIgId($thisInfluencer['ig_user_id']) ?>" class = 'profile-dp'> 
					</div>
					<div class = 'col-xs-4 nopadding'  style = 'padding-left: 0px'>
						<p class = 'labela' style = 'font-size: 21px; margin-bottom: 5px; margin-top: 7px;'> 
							<?= convertEmoji($thisInfluencer['name']) ?> 
						</p>
						<p class = 'labela' style = 'font-size: 18px; margin-bottom: 8px;'>
							<?= getInterestById($thisInfluencer['identityId']) ?>
						</p>
						<img src = "
								<?php 
									$location = $thisInfluencer['locationId'];
									if($location == 1)
									{
										echo 'https://lipis.github.io/flag-icon-css/flags/4x3/hk.svg';
									}
									elseif($location == 2)
									{
										echo 'https://lipis.github.io/flag-icon-css/flags/4x3/tw.svg';
									}
									elseif($location == 3)
									{
										echo 'https://lipis.github.io/flag-icon-css/flags/4x3/my.svg';
									}
									elseif($location == 4)
									{
										echo 'https://lipis.github.io/flag-icon-css/flags/4x3/sg.svg';
									}
								?>
						" class='flagprof'>
					</div>
				</div>
				<div class = 'makeyellow'>
					About
				</div>
				<div class = 'labelt'>
					<?= convertEmoji(nl2br($thisInfluencerIg['bio'])) ?>
				</div>
				<div class = 'labela mggtop'>
					Social Media
				</div>
				<div class = 'labelt'>
					<?php if(!empty($thisInfluencer['facebook'])){ ?>
					<i class='fa fa-facebook-official' style = 'font-size: 20px; color: #3B5998'></i><?php } ?>
					<?php if(!empty($thisInfluencer['instagram'])){ ?>&nbsp
					<i class='fa fa-instagram' style = 'font-size: 20px; color: #fb3958'></i><?php } ?>
				</div>
			</div>
			<?php if(!empty($thisInfluencer['facebook']) && $thisInfluencer['fb_user_id'] !== 0){ ?>
			<div class = 'row mgtless col-sm-12 makepanel'>
				<div class = 'makeyellow' style = 'color: #3B5998'>
					Facebook
				</div>
				<div class = 'col-sm-4 nopadding'>
					<div class = 'labelt'>Total Followers:</div>
					<div class = 'labela'>
						<?= thousandsFormat(round($thisInfluencerFb['fanCount']), 2) ?> 
						<span class = 'mini-labela'>
							<?php if($thisInfluencerFb['followerPercentage'] >= 0){ ?>
								<i class = 'fa fa-caret-up' style = 'color: #00ff00'></i>
							<?php } else { ?>
								<i class = 'fa fa-caret-down' style = 'color: red'></i>
							<?php } ?>
							<?= round($thisInfluencerFb['followerPercentage'], 2) ?>%
						</span>
					</div>
				</div>
				<div class = 'col-sm-4 nopadding'>
					<div class = 'labelt'>Interactions Per Post:</div>
					<div class = 'labela'>
						<?= thousandsFormat(round($thisInfluencerFb['interaction']), 2) ?>
						<span class = 'mini-labela'>
							<?php if($thisInfluencerFb['interactionPercentage'] >= 0){ ?>
								<i class = 'fa fa-caret-up' style = 'color: #00ff00'></i>
							<?php } else { ?>
								<i class = 'fa fa-caret-down' style = 'color: red'></i>
							<?php } ?>
							<?= round($thisInfluencerFb['interactionPercentage'], 2) ?>%
						</span>
					</div>
				</div>
				<div class = 'col-sm-4 nopadding'>
					<div class = 'labelt'>Engagement Rate:</div>
					<div class = 'labela'><?= round($thisInfluencerFb['engagementRate'], 2) ?>%</div>
				</div>
			</div>
			<?php } ?>
			<?php if(!empty($thisInfluencer['instagram'])){ ?>
			<div class = 'row mgtless col-sm-12 makepanel'>
				<div class = 'makeyellow' style = 'color: #fb3958'>
					Instagram
				</div>
				<div class = 'col-sm-4 nopadding'>
					<div class = 'labelt'>Total Followers:</div>
					<div class = 'labela'>
						<?= thousandsFormat(round($thisInfluencerIg['followerCount']), 2) ?>
						<span class = 'mini-labela'>
							<?php if($thisInfluencerIg['followerPercentage'] >= 0){ ?>
								<i class = 'fa fa-caret-up' style = 'color: #00ff00'></i>
							<?php } else { ?>
								<i class = 'fa fa-caret-down' style = 'color: red'></i>
							<?php } ?>
							<?= round($thisInfluencerIg['followerPercentage'], 2) ?>%
						</span>
					</div>
				</div>
				<div class = 'col-sm-4 nopadding'>
					<div class = 'labelt'>Interactions Per Post:</div>
					<div class = 'labela'>
						<?= thousandsFormat(round($thisInfluencerIg['interaction']), 2) ?>
						<span class = 'mini-labela'>
							<?php if($thisInfluencerIg['interactionPercentage'] >= 0){ ?>
								<i class = 'fa fa-caret-up' style = 'color: #00ff00'></i>
							<?php } else { ?>
								<i class = 'fa fa-caret-down' style = 'color: red'></i>
							<?php } ?>
							<?= round($thisInfluencerIg['interactionPercentage'], 2) ?>%
						</span>
					</div>
				</div>
				<div class = 'col-sm-4 nopadding'>
					<div class = 'labelt'>Engagement Rate:</div>
					<div class = 'labela'><?= round($thisInfluencerIg['engagementRate'], 2) ?>%</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<div class = 'lcont row col-sm-4'>
			<div class = 'row mgtless col-sm-12 makepanel' id = 'hide-infpower'>
				<div class = 'makeyellow'>
					Influence Power<sup>TM</sup>
				</div>
				<div class = 'labelt' style="font-size: 17px;">
					<h2 style='font-weight: normal; margin-top: 0px; display: inline'>
						<?= $thisInfluencer['infPower'] ?>
					</h2>/10
					&nbsp&nbsp·&nbsp&nbsp<?= $level ?>
				</div>
			</div>
			<div class = 'row mgtless col-sm-12 makepanel'>
				<div class = 'makeyellow'>
					Followers
				</div>
				<div class = 'labelt' style = 'font-size: 17px'>
					<?= thousandsFormat(round($thisInfluencerIg['followerCount'] + $thisInfluencerFb['fanCount'], 1)) ?> Total Followers
				</div>
			</div>
			<div class = 'row mgtless col-sm-12 makepanel' style = 'border-radius: 0px; padding-left: 15px; word-wrap: break-word;'>
				<div class = 'makeyellow'>
					Top Post
				</div>
				<?php if(!empty($thisInfluencer['instagram'])){ ?>
					<div class = 'col-sm-12 nopadding'>
						<img src = <?= getTopPostPicByIg($thisInfluencerIg['id']) ?> class = 'mggtop topPost'>				
					</div>
					<div class = 'col-sm-12 nopadding toppost-caption' style = 'margin-top: 8px'>
						<?php $topPostIg = getTopPostByIg($thisInfluencerIg['id']); ?>
						<?= convertEmoji($topPostIg['content']) ?><br>
						<i class = 'fa fa-heart' style = 'margin-top: 5px'></i>&nbsp<?= thousandsFormat($topPostIg['likeCount']) ?>&nbsp&nbsp&nbsp
						<i class = 'fa fa-commenting' style = 'margin-top: 5px'></i>&nbsp<?= thousandsFormat($topPostIg['commentCount']) ?>	
						<i class='fa fa-instagram' style = 'font-size: 20px; color: #fb3958; float: right; margin-right: 3px;'></i>	
					</div>
				<?php } ?>
				<?php if(!empty($thisInfluencer['facebook']) && $thisInfluencer['fb_user_id'] !== 0){ ?>
					<div class = 'col-sm-12 nopadding'>
						<img src = <?php echo getTopPostPicByFb($thisInfluencerFb['id']) ?> class = 'mggtop topPost'>				
					</div>
					<div class = 'col-sm-12 nopadding toppost-caption' style = 'margin-top: 8px'>
						<?php $topPostFb = getTopPostByFb($thisInfluencerFb['id']);  ?>
						<?= convertEmoji($topPostFb['content']) ?><br>
						<i class = 'fa fa-heart' style = 'margin-top: 5px'></i>&nbsp<?= thousandsFormat($topPostFb['likeCount']) ?>&nbsp&nbsp&nbsp
						<i class = 'fa fa-commenting' style = 'margin-top: 5px'></i>&nbsp<?= thousandsFormat($topPostFb['commentCount']) ?>
						<i class='fa fa-facebook-official' style = 'font-size: 20px; color: #3B5998; float: right; margin-right: 3px;'></i>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="../navscript.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script>
	<script src="../gauge_chart/kuma-gauge.jquery.min.js"></script>
	<script>
		$('.demo').kumaGauge(
		{
			radius : 50,
			paddingX : 0,
			paddingY : 0,
			gaugeWidth : 15,
			fill : '#00355F',
			gaugeBackground : '#FFAB00' ,
			showNeedle : false,
			min : 0,
			max : 10,
			valueLabel : { display : false, },
			value : <?= $thisInfluencer['infPower'] ?>,
			title : {  display : false, },
			label : {  display : false, },
			animationSpeed: 700,
		});
		$(function()
		{
			$('.gauge__value').css("text-anchor", "end");
			$("tspan").append("/10");
		});
	</script>
</body>
</html>
<?php 
	}
	else
	{
		header('Location: ../login.php');
	}
 ?>