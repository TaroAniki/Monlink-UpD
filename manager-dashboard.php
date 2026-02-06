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

    <link rel="stylesheet" href="style2.css">

</head>

<body class="manager-dashboard-page">
  <button onclick="window.location='logout.php'">Logout</button>
    <h2>Manager Dashboard</h2><br>
    <h3>Hello, <?php echo $user_data['first_name']; ?>!</h3><br>

<button onclick="window.location='change-acc-details.php'">Change Account Details</button><br>
<button onclick="window.location='signup-tenant.php'">Create Tenant Account</button><br>
<button onclick="window.location='signup-staff.php'">Create Staff Account</button><br>
<button onclick="window.location='unit_management.php'">Unit Management</button><br>

    
</body>
</html>