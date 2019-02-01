<!--
	home.php
	Homepage. Shows todo list and influence power for an influencer.
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
		$pageTitle = 'Home';
		include 'headfiles.php'; 
		include 'backend/db_conn.php';
		$campaignsHome = findHomeCamp($userId);
		$thisInfluencer = getInfluencerById($userId);
		$level = "Rookie";
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
	<title>Home</title>
</head>
<body>
<?php include 'top.php'; ?>
	<div  class = "title">
		<p>	Home </p>
	</div>
	<div class = 'containerbod'>
		<div class = 'makepanel row mgtmore' style = 'border-radius: 0px' id = 'profile-intro-mob'>
			<div class = 'col-xs-4 nopadding'>
				<img src = '<?= $_SESSION['image'] ?>' class = 'profile-dp' onerror = "function(){$('#profile-pic').attr('src', 'brand-logo.png')}">
			</div>
			<div class = 'col-xs-8 nopadding'  style = 'padding-left: 0px'>
				<p class = 'intro-name'>
					<?= convertEmoji(getInfluencerById($userId)['name']) ?>
				</p>
				<p class = 'intro-interest'>
					<?= getInterestById(getInfluencerById($userId)['identityId']) ?>
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
						"
				class='flagprof'>
			</div>
		</div>
		<div class = 'row makepanel mgtless gauge-container' style = 'border-radius: 0px'>
			<div class = 'makeyellow'> Influence Power<sup>TM</sup></div>
			<div class="demo mggtop" style = 'display: inline-block; margin-left: 7px;''>
				<div class = 'valuelabel'> <span><?= $thisInfluencer['infPower'] ?></span>/10 </div>
			</div>
			<div class = 'intro-level'>·&nbsp&nbsp<?= $level ?></div>
		</div>
		<div class = 'row'>
		<div class = "makepanel lcont mgtmore col-sm-8 row">
			<div class = 'makeyellow' style = 'padding-left: 10px;'>
				To Do
			</div>
			<?php
				if(empty($campaignsHome))
				{ 
			?>
			<div class = 'empty-div col-sm-12' style = 'text-align: center; margin-top: 3px; background-color: white;'>
				<p class = 'labelt'>You have no remaining tasks at this time.</p>
			</div>
			<?php 
			}
			else
			{
				foreach($campaignsHome as $row)
				{
					$campaign = findCampById($row['campaignId']);
			?>
			<div class = 'todo col-sm-12'>
				<?php 
					if($row['status'] < 2)
					{
						$link = 'pending_detail.php';
					}
					elseif($row['status'] < 7)
					{
						$link = 'active_detail.php';
					}
					else
					{
						$link = 'completed_detail.php';
					}
				?>
				<a href ="<?= $link . '?ref=' . $campaign['refId'] ?>"><div class = 'mob-link'></div></a>
				<a href = "<?= $link . '?ref=' . $campaign['refId'] ?>">
					<p class = 'todo-brand'><?= getBrandById($campaign['businessId'])['name'] ?></p>
				</a>
				<p class = 'todo-title'><?= $campaign['title'] ?></p>
				<?php if(isset($row['draftDate']) && $row['status'] > 1){ ?>
				<p class = 'date-ntf' <?php if(($row['draftDate'] - time()) < 172800){ echo "style = 'color: #f32f2f'"; } ?>>
					Draft By: <?= date('d/m/y', $row['draftDate'])?>
				</p> 
				<?php } ?>
				<?php if(isset($row['postDate']) && $row['status'] > 1){ ?>
				<p class = 'date-ntf' <?php if(($row['postDate'] - time()) < 172800){ echo "style = 'color: #f32f2f'"; } ?>>
					Post On: <?= date('d/m/y', $row['postDate']) ?>
				</p> 
				<?php } ?>
				<div class = 'statushome'><?= getAdminStatus($row['status']) ?></div>
				<a href = "<?= $link . '?ref=' . $campaign['refId'] ?>">
					<i class="fa fa-chevron-right" id = 'home-arrow'></i>
				</a>
			</div>
			<?php }} ?>
		</div>
		<div class = 'norightpad row col-sm-4'>
			<div class = 'makepanel mgtmore col-sm-12' id = 'hide-infpower'>
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
		</div>
		</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="navscript.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script>
	<script src="gauge_chart/kuma-gauge.jquery.min.js"></script>
	<script>
		$('.demo').kumaGauge(
		{
			radius : 65,
			paddingX : 0,
			paddingY : 0,
			gaugeWidth : 15,
			fill : '#00355F',
			gaugeBackground : '#FFAB00',
			showNeedle : false,
			min : 0,
			max : 10,
			valueLabel : {display : false, },
			value : <?= $thisInfluencer['infPower'] ?>,
			title : {  display : false, },
			label : {  display : false, },
			animationSpeed: 1000,
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
		header('Location: login.php');
	}
 ?>