<?php
session_start();

// Odmítnutí tokenu
function revokeAccessToken($accessTokenUrl, $accessToken) {
    if ($accessToken) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $accessTokenUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['token' => $accessToken]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }
}

// URL pro odmítnutí tokenu
$tokenRevocationUrl = "https://oauth2.googleapis.com/revoke";

// Kontrola existence tokenu
if (isset($_SESSION['access_token'])) {
    revokeAccessToken($tokenRevocationUrl, $_SESSION['access_token']);
}

// Smazat session
session_destroy();

// Kontrola existence a mazání cookie
if (isset($_COOKIE['user_info'])) {
    setcookie('user_info', '', time() - 3600, '/');
}

// Odkázat zpět na homepage
header("Location: ../index.php");
exit;
?>
