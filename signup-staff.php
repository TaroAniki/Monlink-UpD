<?php
session_start();

include ("connections.php");
include ("functions.php");



if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($phone_number)) {

    $query = "SELECT email FROM account_details where email = '$email'";
      $useremailchecker = mysqli_query($con, $query);

      if (mysqli_num_rows($useremailchecker) > 0){
        echo "<script>alert('email already exists!');</script>";

      }
    
  
    $phoneregex = '/^((\+63)|63|0)9[0-9]{9}$/';
    if (!preg_match($phoneregex, $phone_number)){
        echo "<script>alert('Phone Number format error!');</script>";
    }else{

        if ($middle_name == null){
            $middle_name = "";
        }
        $user_id = random_num(10);
        $username = random_pass(8);
        $password = random_pass(12);
        $query = "insert into account_details (user_id, first_name, middle_name, last_name, email, phone_number, username, password, newaccountstatus, user_status) values ('$user_id', '$first_name', '$middle_name', '$last_name', '$email', '$phone_number', '$username', '$password', '1', 'staff')";

        mysqli_query($con, $query);
         echo "<script>alert('Account Successfully created');</script>";

        header("Location: login.php");
        die;
    } 
        
    }else {
        echo "<script>alert('Please enter some valid information!');</script>";


        
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Staff Account</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="signup-staff-page">

<header>
    <nav class="navbar2">
        <h2 class="logo-text">Create Staff Account</h2>
        <a href="manager-dashboard.php" class="nav-link" id="booknow-btn">⤸ Back</a>
    </nav>
</header>

<div class="signup-wrapper">
    <form method="post" class="signup-form">
        <h3>Staff Information</h3>

        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="middle_name" placeholder="Middle Name">
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="number" name="phone_number" placeholder="Phone Number" required>

        <button type="submit" class="signup-btn">Create Account</button>
    </form>
</div>

<div class="bg-wrapper">
    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop" alt="Background">
</div>

</body>
</html>
