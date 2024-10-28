<?php


session_start();
include("connect.php");

//OAUTH 2.0 hodnoty
$clientID = '938699442792-h42ebsvio387jmb3i0bo7oqiubqaunvl.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/burza/login.php';
$tokenRevocationUrl = "https://oauth2.googleapis.com/revoke";


// Kontrola, zda byl vrácen kód pro autorizaci
if (!isset($_GET['code'])) {
    $authorizationUrl = 'https://accounts.google.com/o/oauth2/v2/auth';

    // Parametry pro autorizaci
    $params = array(
      'response_type' => 'code',
      'client_id' => $clientID,
      'redirect_uri' => $redirectUri,
      'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
    );

    // Přesměrování na stránku pro autorizaci
    header('Location: ' . $authorizationUrl . '?' . http_build_query($params));
    exit;
  }


// Kontrola, zda je uživatel přihlášen
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
}


// URL pro získání přístupového tokenu
$accessTokenUrl ="https://oauth2.googleapis.com/token";


// Parametry pro získání přístupového tokenu
$params = array(
  'code' => $_GET['code'],
  'client_id' => $clientID,
  'client_secret' => $clientSecret,
  'redirect_uri' => $redirectUri,
  'grant_type' => 'authorization_code',
);


// Inicializace cURL
$ch = curl_init();


// Nastavení cURL pro požadavek na získání tokenu
curl_setopt($ch,CURLOPT_URL,$accessTokenUrl);
curl_setopt($ch,CURLOPT_POST,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($params));
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

// Provést cURL požadavek
$response = curl_exec($ch);
curl_close($ch);


$accessTokenData = json_decode($response,true);


// Kontrola, zda byl získán přístupový token
if (isset($accessTokenData['access_token'])){
  $_SESSION['access_token'] = $accessTokenData['access_token'];
  
  // URL pro získání informací o uživateli
  $userInfoUrl = "https://www.googleapis.com/oauth2/v1/userinfo?access_token=" . $_SESSION['access_token'];

  // Další inicializace cURL pro získání uživatelských informací
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $userInfoUrl);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  
  // Provést cURL požadavek na získání informací o uživateli
  $userInfo = curl_exec($ch);
  curl_close($ch);
  
  // Dekódování JSON odpovědi pro uživatelské informace
  $userInfoData = json_decode($userInfo, true);

  // Uložení informací o uživateli do session
  $_SESSION['userinfodata'] = $userInfoData;
 
  // Uložení informací o uživateli do cookie
  setcookie('user_info', json_encode($userInfo), time() + (86400 * 30), "/");
}




// Příprava na kontrolu existence uživatele v databázi
$email = $conn->real_escape_string($userInfoData['email']);
$query = "SELECT * FROM user WHERE email='$email'";
$result = $conn->query($query);


// Kontrola, zda uživatel již existuje v databázi
if ($result->num_rows === 0) {
    // Uživatel neexistuje, vložení nového uživatele
    $username = $conn->real_escape_string(explode('@',htmlspecialchars($userInfoData['email']))[0]);
    $firstName = $conn->real_escape_string($userInfoData['given_name']);
    $lastName = $conn->real_escape_string($userInfoData['family_name']);
    
    // SQL dotaz pro vložení nového uživatele do databáze
    $insertQuery = "INSERT INTO user (user, email, jmeno, prijmeni) VALUES ('$username', '$email', '$firstName', '$lastName')";
    if ($conn->query($insertQuery) === TRUE) {
        // Uživatel úspěšně vložen
    } else {
        echo "Error: " . $insertQuery . "<br>" . $conn->error;
    }
}
$conn->close();

?>