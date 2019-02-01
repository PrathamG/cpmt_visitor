<!--
	index.php
	Admin Login Page.
-->
<?php
session_start();
if(isset($_SESSION['adminUserId']))
{
	if($_SESSION['adminUserId'] == 1350) //If already logged in, redirect to admin.php
	{
		header('location: admin.php');
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include 'headfiles.php' ?>
	<?php include '../backend/db_conn.php' ?>
	<title></title>
</head>
<?php 
	if($_SERVER['REQUEST_METHOD'] == 'POST') //If page called through post request, verify login details. 
	{
		$username = $_POST['username'];
		$pass = do_hash($_POST['password']);

		global $mydb;
		$sql = "SELECT id, email FROM user WHERE email = ? AND password = ?";
		$stmt = $mydb->prepare($sql);
		$stmt->bind_param("ss", $username, $pass);
		$stmt->execute();
		$user = $stmt->get_result();
		if($user->num_rows > 0)
		{
			$user = $user->fetch_assoc();
			if($user['email'] == 'brand@cloudbreakr.com' && $user['id'] == 1350)
			{	
				$_SESSION['adminUserId'] = $user['id'];
				header('location: admin.php');
			}
		}
	}
?>

<body style = 'background-color:white; padding-bottom: 0px;'>
	<div class = 'top-container'>
		<img src = '../brand-logo-text.png' class = 'login-logo'>
		<div class = 'welcome-text'>Welcome to Cloudbreakr Admin</div>
	</div>
	<form class = 'btm-container' action = "<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = 'post'>
		<div class = 'login-label'>
			<?php 
			if($_SERVER['REQUEST_METHOD'] == 'POST'){ //If page called through POST request and no redirect yet, login details incorrect ?>
				<div class = 'suggestion'>Invalid username or password</div>
			<?php } ?>
			Username:
		</div>
		<div><input type = 'text' class = 'login-input' name = 'username'></div>
		<div class = 'login-label'>Password:</div>
		<div><input type = 'password' class = 'login-input' name = 'password'></div>
		<button class = 'login-btn' type = 'submit'>Log In</button>
	</form>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="navscript.js"></script>
</body>
</html>
