<!--
	admin_top.php
	Responsive navigation bar for admin webpages.
-->
<div class = 'holder'>
	<div>
		<img src="../brand-logo.png" class='logo' style = 'margin-bottom: 15px'>
		<ul class='nav-bar'>
			<a href = "admin.php"><li>Current Campaigns</li></a>
			<a href = 'completed_detail.php'><li>Completed Campaigns</li></a>
			<a href = "logout.php"><li>Logout</li></a>
		</ul>
	</div>
</div>
<div class = 'mobnav'>
	<img src = '../brand-logo.png' class = 'moblogo' style = 'margin-left: 10px'>
	<div class='mobpgtitle'><?= $pageTitle ?></div>
</div>
<div class = 'btmnav'  style = 'padding-top: 3px;'>
	<ul>
		<a href = 'logout.php'><li><i class = 'bnavcon fa fa-sign-out' style = 'font-size: 20px'></i> <br> Logout</li></a><a href = 'admin.php'><li><i class = 'bnavcon fa fa-calendar' style = 'font-size: 20px'></i><br>Current</li></a><a href = 'completed_detail.php'><li><i class = 'bnavcon fa fa-calendar-check-o' style = 'font-size: 20px'></i><br>Complete</li></a>
	</ul>
</div>