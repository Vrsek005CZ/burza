<?php
session_start();
//include("connect.php");


if (isset($_COOKIE['user_info'])) {
    $userInfoData = json_decode($_COOKIE['user_info'], true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burza</title>
</head>
<body>
    <h1>Vítejte</h1>

    <?php
if (isset($_COOKIE)) {
    echo 'Cookie: <pre>' . print_r($_COOKIE, true) . '</pre>';
} else {
    echo 'cookie neexistuje.';
}

if (isset($_SESSION)) {
    echo 'Session: <pre>' . print_r($_SESSION, true) . '</pre>';
} else {
    echo 'session neexistuje.';
}

if (isset($userInfo)) {
    echo 'User Info: <pre>' . print_r($userInfo, true) . '</pre>';
} else {
    echo 'userinfo neexistuje.<br>';
}

if (isset($userInfoData)) {
    echo 'User Info Data: <pre>' . print_r($userInfoData, true) . '</pre>';
} else {
    echo 'userinfodata neexistuje.';
}

echo '<pre>';
print_r($userInfoData);
echo '</pre>';

if (isset($userInfoData['email'])) {
    echo 'Email: ' . htmlspecialchars($userInfoData['email']);
} else {
    echo 'Email nenalezen.';
}
?>
test
</body>
</html>
