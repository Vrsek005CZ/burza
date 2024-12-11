<?php
include("connect.php");

if (isset($_COOKIE['user_info'])) {
    $userInfoData = json_decode($_COOKIE['user_info'], true);
    $userInfoDataArray = json_decode($userInfoData, true);
    $email = $conn->real_escape_string($userInfoDataArray['email']);
    $query_user = "SELECT * FROM user WHERE email='$email'";
    $result_user = $conn->query($query_user);

    if ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
        $userId = $user['id'];
    }
    else {
        echo("Chyba: User nenalezen.");
        exit;
    }
}
else {
    echo("Chyba: Nejste přihlášen.");
    header("Location: login.php");
    exit;
}
?>