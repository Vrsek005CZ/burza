<?php


session_start();
//include("connect.php");

$clientID = '938699442792-h42ebsvio387jmb3i0bo7oqiubqaunvl.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/burza/login.php';

$tokenRevocationUrl = "https://oauth2.googleapis.com/revoke";

if (!isset($_GET['code'])) {
    $authorizationUrl = 'https://accounts.google.com/o/oauth2/v2/auth';
  
    $params = array(
      'response_type' => 'code',
      'client_id' => $clientID,
      'redirect_uri' => $redirectUri,
      'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
    );
  
    header('Location: ' . $authorizationUrl . '?' . http_build_query($params));
    exit;
  }

if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
}

if (isset($_GET['logout'])){
  $accessToken = $_SESSION['access_token'];

  if($accessToken){
    $revokeParams = array(
      'token' => $tokenRevocationUrl
    );

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$accessTokenUrl);
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($params));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $response = curl_exec($ch);
    curl_close($ch);
  }

  session_destroy();

  header("Location: http://localhost");
  exit;
}

$accessTokenUrl ="https://oauth2.googleapis.com/token";

$params = array(
  'code' => $_GET['code'],
  'client_id' => $clientID,
  'client_secret' => $clientSecret,
  'redirect_uri' => $redirectUri,
  'grant_type' => 'authorization_code',
);

$ch = curl_init();

curl_setopt($ch,CURLOPT_URL,$accessTokenUrl);
curl_setopt($ch,CURLOPT_POST,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($params));
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
$response = curl_exec($ch);
curl_close($ch);

$accessTokenData = json_decode($response,true);

if (isset($accessTokenData['access_token'])){
  $_SESSION['access_token'] = $accessTokenData['access_token'];
  
  $userInfoUrl = "https://www.googleapis.com/oauth2/v1/userinfo?access_token=" . $_SESSION['access_token'];

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $userInfoUrl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $userInfo = curl_exec($ch);
  curl_close($ch);
  
  $userInfoData = json_decode($userInfo, true);

  $_SESSION['userinfodata'] = $userInfoData;

  setcookie('user_info', json_encode($userInfo), time() + (86400 * 30), "/");

  echo '<h1>Welcome,' . $userInfoData['name'] . '!</h1>';
  echo '<p>Email: '. $userInfoData['email'] . '</p>';
  echo '<p>Profile picture: </p>';
  echo '<img src="' . $userInfoData['picture'] . '"alt="Profile Picture">';
  echo '<a href="?logout">Odhlásit se</a>';
}else {
  echo "Chyba v získání přístupového tokenu";
}  
?>