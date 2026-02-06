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
<body class="staff-dashboard-page">
    <a href="logout.php">Logout</a>
    <h1>Staff Dashboard</h1>
      Hello, <?php echo $user_data['first_name']; ?>!<br>
    <a href="change-acc-details2.php">Change Account Details</a><br>
  
    <a href="signup-tenant-staff.php">Create Tenant Account</a><br>
    <a href="staff-booking-details.php">View Booking List</a><br>
</body>
</html>