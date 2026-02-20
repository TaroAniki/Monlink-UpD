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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
</head>
<body class="body-user">

<header>
    <nav class="navbar2">
        <h2 class="logo-text">Unit Management</h2>
        <a href="manager-dashboard.php" class="nav-link" id="booknow-btn">⤸ Dashboard</a>
    </nav>
</header>

<div class="form-wrapper">
    <div class="container">
        <div class="unit-buttons-wrapper">

            <button type="button" onclick="window.location='add_unit.php'" class="action-button"> Add Unit</button>
            <button type="button" onclick="window.location='edit_unit.php'" class="action-button"> Edit Unit</button>
            <button type="button" onclick="window.location='delete_unit.php'" class="action-button"> Delete Unit</button>
        </div>
    </div>
</div>


<div class="bg-wrapper">
  <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop" alt="Background">
</div>

</body>
</html>
