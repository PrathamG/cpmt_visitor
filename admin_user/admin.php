<!--
	Admin.php
	Author: Pratham Goradia
	Description: Webpage to display currently ongoing campaigns and brief details to the admin.
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
	<?php
		$pageTitle = 'Admin: Current Campaigns';
		include 'headfiles.php';
		include '../backend/db_conn.php'; 
	?>
	<title>Current Campaigns</title>
</head>
<body>
	<?php include  'admin_top.php' ?>
	<div class = "title">
		<p id = 'admin-title'>	Admin: Current Campaigns </p>
	</div>
	<div class = 'containerbod'>
		<div class = "activecamp">
			<div class = 'searchbox'>
				<input type = 'text' id = 'searchinput' placeholder = 'Search by Brand...'>
				<i style = 'font-size: 25px;' class = "fa fa-search searchlabel"></i>
			</div>
			<?php
				$active_campaigns = getAdminCamp();
				foreach($active_campaigns as $row){ 
			?>	
				<div class = 'row campaigndet'>
				<a href = "<?php echo 'admin_detail.php?ref=' . $row['refId']; ?>"><div class = 'mob-link'></div></a>
					<div class = 'campleft col-sm-8'>
						<p class = 'brandcamp'><a href = "<?php echo 'admin_detail.php?ref=' . $row['refId']; ?>"><span><?= getBrandById($row['businessId'])['name'] ?></span></a>
						<?php if($row['facebook'] == 1){echo "<i class='fb fa fa-facebook-square'></i>";} 
						if($row['instagram'] == 1){echo "<i class='inst fa fa-instagram'></i>";} ?></p>
						<p class = 'titlecamp'><?= $row['title']; ?></p>
						<p class = 'cdates'><?= date('d/m/Y', $row['startDate']); ?> - <?= date('d/m/Y', $row['endDate']); ?></p>
					</div>
					<a href = "<?php echo 'admin_detail.php?ref=' . $row['refId']; ?>"><i class='fa fa-chevron-right' id = 'detrow'></i></a>
				</div>
			<?php } ?>
		</div>
	</div>
	<!-- Minified Jquery CDN -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<!-- Script for ajax requests responsible for live campaign search -->
	<script>
		$('#searchinput').keyup(function(){
			console.log('run');
			$.post("admin_controller.php",
				{
					function: 'brandLiveSearch',
					search: $('#searchinput').val(),
				},
				function(responseTxt)
				{
					if(responseTxt != 0)
					{
						var campArr = JSON.parse(responseTxt);
						var children = [];
						$(".activecamp" ).find(".campaigndet").each(function() 
						{
							children.push(this);
						});
						var campNum = children.length
						var count = 0;
						campArr.forEach(function(thisCamp, index)
						{
							count += 1;
							var ref = thisCamp['refId'];
							var startDate = convertDate(new Date(thisCamp['startDate']*1000));
							var endDate = convertDate(new Date(thisCamp['endDate']*1000));
							var child_str = 
							"<a href = 'admin_detail.php?ref=" + ref + "'><div class = 'mob-link'></div></a>"+
							"<div class = 'campleft col-sm-8'>"+
							"<p class = 'brandcamp'><a href = 'admin_detail.php?ref=" + ref + "'><span style = 'diaplay: block; line-height: normal'>" + thisCamp['name'] + "</span></a></p>"+
							"<p class = 'titlecamp'>" + thisCamp['title'] + "</p>"+
							"<p class = 'cdates'>" + startDate + " - " + endDate + "</p></div>"+
							"<a href = 'admin_detail.php?ref=" + ref + "'><i class='fa fa-chevron-right' id = 'detrow'></i></a>";
							$(children[index]).html(child_str);
						})
						for(i = count; i < campNum; i++)
						{
							$(children[i]).html("");
						}
						for(i = 0; i < campNum; i++)
						{
							if($(children[i]).html() == "")
							{
								$(children[i]).css('background', 'transparent');
							}
							else
							{
								$(children[i]).css('background', 'white');
							}
						}
					}
					else
					{
						console.log('Not Found');
					}
				}
			);
		});
		function convertDate(inputFormat) 
		{
			function pad(s) { return (s < 10) ? '0' + s : s; }
			var d = new Date(inputFormat);
			return [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('/');
		}
	</script>
</body>
</html>
<?php 
	}
	else
	{
		header('location: index.php'); //If logged out then redirect to admin login page.
	} 
?>
