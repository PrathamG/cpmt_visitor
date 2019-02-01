<?php
session_start();
if(isset($_SESSION['userId']))
{
	header('location: home.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php 
		include 'headfiles.php';
		include 'backend/db_conn.php';
		require_once 'vendor/autoload.php';
	?>
	<title>Welcome to Cloudbreakr</title>
</head>
<?php 
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$username = $_POST['username'];
		$pass = do_hash($_POST['password']);

		global $mydb;
		$sql = "SELECT id FROM user WHERE email = ? AND password = ?";
		$stmt = $mydb->prepare($sql);
		$stmt->bind_param("ss", $username, $pass);
		$stmt->execute();
		$user = $stmt->get_result();
		if($user->num_rows > 0)
		{
			$user = $user->fetch_assoc();
			$userId = $user['id'];
			$sql = "SELECT id, ig_user_id, locationId FROM influencer WHERE user_id = ?";
			$stmt = $mydb->prepare($sql);
			$stmt->bind_param("s", $userId);
			$stmt->execute();
			$user = $stmt->get_result();
			if($user->num_rows > 0)
			{
				$user = $user->fetch_assoc();

				$userIgId = $user['ig_user_id'];
				$userId = $user['id'];
				$locationId = $user['locationId'];
				
				session_start();
				$_SESSION['userId'] = $userId;
				$_SESSION['image'] = getInfluencerPicByIgId($userIgId);
				$_SESSION['location'] = $locationId;
				
				header('location: home.php');
			}
			else
			{
			}
		}
	}
?>

<body style = 'background-color:white; padding-bottom: 0px;'>
	<div class = 'top-container'>
		<img src = 'brand-logo-text.png' class = 'login-logo' id = 'login-logo'>
		<div class = 'welcome-text'>Welcome to Cloudbreakr for Influencers.</div>
		<div class = 'welcome-desc'>Effortlessly manage all your social media campaigns. Focus more on doing what you love.</div>
	</div>
	<form class = 'btm-container' action = "<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = 'post'>
		<div class = 'login-label'>
			<?php 
			if($_SERVER['REQUEST_METHOD'] == 'POST'){ ?>
				<div class = 'suggestion'>Invalid username or password</div>
			<?php } ?>
			Username:
		</div>
		<div><input type = 'text' class = 'login-input' name = 'username'></div>
		<div class = 'login-label'>Password:</div>
		<div><input type = 'password' class = 'login-input' name = 'password'></div>
		<button class = 'login-btn' type = 'submit'>Log In</button>
		<div class = 'or-divider'>OR</div>
		<?php
			$fb = new Facebook\Facebook
				([
				  'app_id' => '319248615135933', 
				  'app_secret' => '73e0dd1002d45148e73ddd965590d2d4',
				  'default_graph_version' => 'v2.2',
				]);

			$helper = $fb->getRedirectLoginHelper();

			$permissions = []; // Optional permissions
			$loginUrl = $helper->getLoginUrl('https://icm.cloudbreakr.com/backend/fb_callback.php', $permissions);
		?>
		<a href="<?= htmlspecialchars($loginUrl) ?>" class = "btn btn-default fb-login login-btn">
			<i class = 'fa fa-facebook-f fb-login-icon'></i>Log In with Facebook
		</a>
	</form>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="navscript.js"></script>
</body>
</html>