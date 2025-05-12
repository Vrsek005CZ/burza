<?php
require_once "connect.php";
require_once "is_banned.php";

if (isset($_COOKIE['user_info'])) {
    $userInfoData = json_decode($_COOKIE['user_info'], true);
    $userInfoDataArray = json_decode($userInfoData, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo("Chyba: Neplatný formát JSON v cookie.");
        header("Location: " . $BASE_URL . "code/login.php");
        exit;
    }

    if (isset($userInfoDataArray['email'])) {
        $email = $conn->real_escape_string($userInfoDataArray['email']);

        $query_user = "SELECT * FROM user WHERE email = ?";
        $stmt = $conn->prepare($query_user);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result_user = $stmt->get_result();

            if ($result_user->num_rows > 0) {
                $user = $result_user->fetch_assoc();
                $userId = $user['id'];

                if (!isset($user['trida_id']) || empty($user['trida_id'])) {
                    if (basename($_SERVER['PHP_SELF']) !== 'profil.php') {
                        header("Location: " . $BASE_URL . "pages/profil.php");
                        exit;
                    }
                }
            } else {
                echo("Chyba: User nenalezen.");
                exit;
            }
            $stmt->close();
        } else {
            echo("Chyba při přípravě dotazu: " . $conn->error);
            exit;
        }
    } else {
        echo("Chyba: Email není v cookie.");
        header("Location: " . $BASE_URL . "code/login.php");
        exit;
    }
} else {
    echo("Chyba: Nejste přihlášen.");
    header("Location: " . $BASE_URL . "code/login.php");
    exit;
}

isUserBanned($conn, $user);
?>