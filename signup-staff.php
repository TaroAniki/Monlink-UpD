<?php
session_start();

include ("connections.php");
include ("functions.php");



if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //something was posted
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
    
    // Matches: 09171234567, +639171234567, 639171234567
    $phoneregex = '/^((\+63)|63|0)9[0-9]{9}$/';
    if (!preg_match($phoneregex, $phone_number)){
        echo "<script>alert('Phone Number format error!');</script>";
    }else{
//save to database
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
    <title>Create Account</title>
        <link href="" rel="stylesheet">
    <link href="" rel="stylesheet">
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <body class="signup-staff-page"></body>
    <a href="manager-dashboard.php">GO BACK</a>
    <div>
        <form method="post">
            <div>Staff Create Account</div>
            <input type="text" name="first_name" placeholder="First Name" required><br>
            <input type="text" name="middle_name" placeholder="Middle Name"><br>
            <input type="text" name="last_name" placeholder="Last Name" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="number" name="phone_number" placeholder="Phone Number" required><br>
            <input type="submit" value="Create Account"><br>
        </form>
    </div>
</body>
</html>