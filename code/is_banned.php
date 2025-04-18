<?php
require_once "connect.php";

// kontrola přístupu
function isUserBanned($conn, $user) {
    if (!isset($user) || !isset($user['type']) || $user['type'] < 0) {
        header("Location: /burza/banned.html");
        exit;
    }
}
?>