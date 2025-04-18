<?php
require_once "connect.php";
require_once "userinfo.php";

// Pouze administrátoři mají přístup
if (!isset($user) || !isset($user['type']) || $user['type'] == 0) {
    header("Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");
    exit;
} else {
    $admintype = $user['type'];}
?>