<?php
session_start();
if($_SESSION['adminUserId'] == 1350) //Verify admin logged in
{
	$userId = $_SESSION['adminUserId'];
?>
<!DOCTYPE html>
<html>
<head>
	<?php
		include 'headfiles.php';
		include '../backend/db_conn.php';
		$pageTitle = 'Admin: Current Campaigns';
		$campaignId = $_GET["ref"];
		$thisCamp = getCampByRef($campaignId );
		$thisBrand = getBrandById($thisCamp['businessId']);
	?>
	<meta name='viewport' content='width=1250, initial-scale=1'>
	<title><?='Invite Influencers | Admin: '?></title>
</head>
<body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="../navscript.js"></script>
	<?php include  'admin_top.php' ?>
	<div class = "title">
		<p id = 'admin-title'>	Admin: Invite Influencers </p>
	</div>
	<div class = 'col-sm-5 makepanel'>
		<?= $thisCamp['title'] . "<br>" ?>
		<?= $thisBrand['name'] ?>
	</div>
	<?php
		if($_SERVER['REQUEST_METHOD'] == "POST")
		{
	?>
		<div class = 'noradius makepanel mgtless'>
		<table class='switch-panel' style="width:100%">
		<tr style = 'border-top: 1px solid black; height: 35px'>
			<th rowspan="2" colspan="1" style="width: 5%; padding-left: 20px;" class="text-center">No.</th>
			<th rowspan="2" colspan="1" style="width: 13%;">Name</th>
			<th rowspan="2" colspan="1" style="width: 13%;">Influencer</th> 
			<th rowspan="2" colspan="1" style="width: 10%;" class="text-center">Tier</th>
			<th rowspan="1" colspan="2" style="width: 15%;" class="text-center">Followers</th>
			<th rowspan="1" colspan="2" style="width: 15%;" class="text-center">Engagement(%)</th>
			<th rowspan="2" colspan="1" style="width: 18%;" class="text-center"></th>
		</tr>
		<tr>
			<th rowspan="1" colspan="1" class="subheader text-center">IG</th>
			<th rowspan="1" colspan="1" class="subheader text-center">FB</th>
			<th rowspan="1" colspan="1" class="subheader text-center">IG</th>
			<th rowspan="1" colspan="1" class="subheader text-center">FB</th>
		</tr>
		<?php
			$influencers = $_POST['influencers'];
			$influencers = preg_split('/\r\n|\r|\n/', $influencers);
			
			$pcount = 0;
			foreach($influencers as $influencer)
			{
				if(strlen($influencer))
				{
					$sql = "SELECT influencer.id, influencer.name, influencer.ig_user_id, fb_user.engagementRate, fb_user.fanCount, 
								   ig_user.followerCount, ig_user.engagementRate as igEngagementRate 
							FROM influencer 
								LEFT JOIN fb_user ON influencer.fb_user_id = fb_user.id 
								LEFT JOIN ig_user ON influencer.ig_user_id = ig_user.id 
							WHERE influencer.instagram = '$influencer'";

					$result = $mydb->query($sql);
					if($result->num_rows > 0)
					{
						$result = $result->fetch_all(MYSQLI_ASSOC)[0];
						$row = $result;
						$pcount += 1;
					
		?>
			<tr>
				<td style = 'padding-left: 20px; text-align: center'><?= $pcount . '.' ?></td>
				<td>
					<?= convertEmoji($row['name']) ?>
				</td>
				<td>
					<?= $influencer ?>
				</td>
				<td style = 'text-align: center'>
					<span class = 'influencer-tier'> <?= getTierById($row['id'])?> </span>
				</td>
				<td style = 'text-align: center'><?= $row['followerCount'] ?></td>
				<td style = 'text-align: center'><?= $row['fanCount'] ?></td>
				<td style = 'text-align: center'><?= round($row['igEngagementRate'], 2) ?></td>
				<td style = 'text-align: center'><?= round($row['engagementRate'], 2) ?></td>
				<td style = 'padding-left: 10px'>
					<span class = 'admin-btn invite' id = '<?= $row['id'] ?>'>Invite</span>
				</td>
			</tr>
		<?php 
					}
				} 
			}
		?>
</table>
<?php
	}
?>
</div>
</body>
<script>
	$(function(){
		$(".invite").click(function(){
			var obj = $(this);
			$.post
			(
				"admin_controller.php",
				{
					function: "inviteInfluencer",
					campaignId: "<?= $thisCamp['id'] ?>",
					infId: this.id,
				},
				function(responseText)
				{
					if(responseText == 1)
					{
						obj.replaceWith("Influencer has been invited.");
					}
					else
					{
						obj.replaceWith("Error. Influencer could not be added.");
					}
				}
			);
		});
	});
</script>
</html>
<?php 
}
else
{
	header('location: index.php');
} 
?>