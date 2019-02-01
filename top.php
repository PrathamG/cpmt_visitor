<!--
	top.php
	Web Masthead for user webpages. Includes file for mobile navigations.
-->
<!DOCTYPE html>
<html>
<head>
</head>
<body>
	<?php
		$influencerTop = getInfluencerById($userId);
		include 'mob_nav.php'; 
	?>

	<div class = 'holder'>
		<div>
			<img src="brand-logo.png" class='logo'>
			<ul class='nav-bar'>
				<a href = "home.php"><li>Home</li></a>
				<span style = 'position: relative'><li id = 'campaign-drop'>Campaigns
					<ul class = 'campaign-extension' id = 'campaign-extension'>
						<a href = 'active_campaigns.php'><li>Active</li></a>
						<a href = 'pending_campaigns.php'><li>Pending</li></a>
						<a href = 'completed_campaigns.php'><li>Complete</li></a>
					</ul>
				</li></span>
				<a href = "marketplace.php"><li>Marketplace</li></a>
				<!--<a href = ""><li>Contact Us</li></a>-->
				<span style = 'position: relative'><li id = 'profile-drop' >Profile
					<ul class = 'campaign-extension' id = 'profile-extension'>
						<a href = 'profile.php'><li>My Profile</li></a>
						<a href = 'backend/logout.php'><li>Logout</li></a>
					</ul>
				</li></span>
			</ul>
		</div>
		<div class='nrow'>
			<div class="dp">
				<img src = "<?= $_SESSION['image'] ?>" id = 'profile-pic' onerror = "function(){$('#profile-pic').attr('src', 'brand-logo.png')}">
			</div>
			<div class = 'intro'>
				<ul>
					<li id="name"> <?= convertEmoji($influencerTop['name']) ?> </li>
					<li> <?= getInterestById($influencerTop['identityId']) ?> &nbsp <span id="dot">&middot</span> &nbsp <img src="
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
						"
					class='flag'></li>
				</ul>
			</div>
		</div>
	</div>
</body>
</html>