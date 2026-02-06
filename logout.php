<?php

session_start() ;
include ("connections.php");
include ("functions.php");

$query = "insert into system_log(log_id, user_id, first_name, last_name, action) values ('".random_num(10)."', '".$_SESSION['user_id']."',  '".$_SESSION['first_name']."', '".$_SESSION['last_name']."', 'Logged out')";
mysqli_query($con, $query);
if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id']);
    unset($_SESSION['first_name']);
    unset($_SESSION['last_name']);
}
header("Location: home-page.php");
die;