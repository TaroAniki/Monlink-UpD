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
    <title>Notice</title>
    <link rel="stylesheet" href="notice.css">
        <link href="" rel="stylesheet">
    <link href="" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Unit added successfully!</h1>
<p>The new unit has been recorded in the database.</p>

<form action="add_unit.php" method="get" style="display:inline-block;">
    <button type="submit">Add Unit Again</button>
</form>

<form action="manager-dashboard.php" method="get" style="display:inline-block;">
    <button type="submit">Back to Manager Page</button>
</form>

</body>
</html>
