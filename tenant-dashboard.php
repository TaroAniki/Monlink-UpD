<?php
session_start();
include ("connections.php");
include ("functions.php");
$user_data = check_login($con);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
        <link href="" rel="stylesheet">
    <link href="" rel="stylesheet">
    <link rel="stylesheet" href="style2.css">
</head>
<body class="tenant-dashboard-page">
    <a href="logout.php">Logout</a>
    <h2>Tenant Dashboard</h2>
     Hello, <?php echo $user_data['first_name']; ?>!<br>

   <button type="button" onclick="window.location='CADtenant.php';">Change Account Detail</button>
</body>
</html>