<?php
session_start();
include("connections.php");
include("functions.php");
$user_data = check_login($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unit Management</title>
    <link rel="stylesheet" href="unit_management.css">
</head>
<body class="manager-page">

<button onclick="window.location='manager-dashboard.php'">Return to the dashboard</button>
<button onclick="location.reload()">Refresh</button>
<h1>Unit Management</h1>



<div class="unit-buttons">
    <button onclick="window.location='add_unit.php'">Add New Unit</button><br>
    <button onclick="window.location='edit_unit.php'">Edit Unit</button><br>
    <button onclick="window.location='delete_unit.php'">Delete Unit</button><br>
</div>


</body>
</html>
