<!--
	mob_nav.php
	Mobile/Tablet navigation bar and masthead for user webpages
-->
<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<div class = 'mobnav'>
		<img src = 'brand-logo.png' class = 'moblogo'>
		<div class='mobpgtitle'><?= $pageTitle ?></div>
		<div id='altercrs'>
			<img src="<?= $_SESSION['image'] ?>" id = "mobdp" class = 'mob-nav-image'> 
			<i id = 'mobdp' class = 'crs fa fa-close mob-nav-crs' style = 'display: none'></i>
		</div>
	</div>
	<div id = "profpage">
		<div id = 'mobprofdet'>
			<div id = 'imgcont'>
				<img src="<?= $_SESSION['image'] ?>" id = "bmobdp" onerror = "function(){$('#profile-pic').attr('src', 'brand-logo.png')}">
			</div>
			<div class = "profmob">
				<p class='lower'><?= $influencerTop['name'] ?></p>
				<p style = 'font-size: 16px'><?= getInterestById($influencerTop['identityId']) ?>&nbsp</p>
				<p><img src="
								<?php 
									$location = $influencerTop['locationId'];
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
							" class='flag'></p>
			</div>
			<a href = 'profile.php'><div class = 'profbtn'>
				My Profile
			</div></a>
			<a href = 'backend/logout.php'><div class = 'profbtn'>
				Logout
			</div></a>
		</div>
	</div>

	<div id = 'whitey'>
	</div>
	<div class = 'mktnav'>
		<ul>
			<a href = 'active_campaigns.php'><li>Active</li><a href = 'pending_campaigns.php'><li>
				Pending</li></a><a href = 'completed_campaigns.php'><li>
				Complete</li></a>
		</ul>
	</div>

	<div class = 'btmnav'>
		<ul>
			<a href = 'home.php'><li <?php if($pageTitle == 'Home'){echo "id = 'active'"; }?>><i class = 'bnavcon icon-home'></i>Home</li></a><a href = 'marketplace.php'><li <?php if($pageTitle == 'Marketplace'){echo "id = 'active'"; }?>>
				<i class = 'bnavcon icon-market'></i>Marketplace</li></a><li class='cmpnav' <?php if($pageTitle == 'Active Campaigns' || $pageTitle == 'Pending Campaigns' || $pageTitle == 'Complete Campaigns'){echo "id = 'active'"; }?>>
				<i class = 'bnavcon icon-campaign'></i><i class = 'campdown fa fa-chevron-up'></i>My Campaigns</li>
		</ul>
	</div>
</body>
</html>