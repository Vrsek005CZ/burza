<?php
session_start();
include("connect.php");
include("userinfo.php");

if (!isset($user) || !isset($user['type']) || $user['type'] == 0) {
    header("Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Admin</title>
</head>

<body class="bg-gray-100 h-screen flex items-start justify-center pt-10">

    <div class="w-full max-w-5xl">
        <!-- Záhlaví -->
        <div class="flex items-center justify-between bg-white shadow-md p-5 rounded-md">
            <!-- Nadpis -->
            <h1 class="text-3xl font-bold text-center flex-1 text-yellow-600">
                <a href="index.php" class="">Online Burza Učebnic</a>
            </h1>
        </div>
        <br> 

    </div>

</body>
</html>