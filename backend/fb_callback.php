<?php
  if (!session_id()) 
  {
      session_start();
  }
  require_once '../vendor/autoload.php';
  require_once 'db_conn.php';
  require_once 'model.php';

  $fb = new Facebook\Facebook([
    'app_id' => '319248615135933', //Also on line 51
    'app_secret' => '73e0dd1002d45148e73ddd965590d2d4',
    'default_graph_version' => 'v2.2',
    ]);

  $helper = $fb->getRedirectLoginHelper();

  try {
    $accessToken = $helper->getAccessToken();
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }

  if (! isset($accessToken)) {
    if ($helper->getError()) {
      header('HTTP/1.0 401 Unauthorized');
      echo "Error: " . $helper->getError() . "\n";
      echo "Error Code: " . $helper->getErrorCode() . "\n";
      echo "Error Reason: " . $helper->getErrorReason() . "\n";
      echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
      header('HTTP/1.0 400 Bad Request');
      echo 'Bad request';
    }
    exit;
  }

  // Logged in
  // The OAuth 2.0 client handler helps us manage access tokens
  $oAuth2Client = $fb->getOAuth2Client();

  // Get the access token metadata from /debug_token
  $tokenMetadata = $oAuth2Client->debugToken($accessToken);
  // Validation (these will throw FacebookSDKException's when they fail)
  $tokenMetadata->validateAppId('319248615135933');
  // If you know the user ID this access token belongs to, you can validate it here
  //$tokenMetadata->validateUserId('123');
  $tokenMetadata->validateExpiration();

  if (! $accessToken->isLongLived()) {
    // Exchanges a short-lived access token for a long-lived one
    try {
      $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
      echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
      exit;
    }
  }

  try {
    // Returns a `Facebook\FacebookResponse` object
    $response = $fb->get('/me', (string) $accessToken);
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }

  $user = $response->getGraphUser();
  $fbId = $user->getId();

  global $mydb;
  $sql = "SELECT id FROM user WHERE type = 'INFLUENCER' AND fb_login_id = $fbId";
  if($result = $mydb->query($sql))
  {
    if($result->num_rows > 0)
    {
      $uid = $result->fetch_assoc()['id'];
      $sql = "SELECT id, ig_user_id, locationId FROM influencer WHERE user_id = $uid";
      $result = $mydb->query($sql)->fetch_assoc();
      $_SESSION['userId'] = $result['id'];
      $_SESSION['image'] = getInfluencerPicByIgId($result['ig_user_id']);
      $_SESSION['location'] = $result['locationId'];
      $_SESSION['fb_token'] = (string) $accessToken;
      header('location: ../home.php');
    }
    else
    {
    	header('location: https://cloudbreakr.com/signup-influencer');
    }
  }

?>