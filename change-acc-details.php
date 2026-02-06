<?php
session_start();

include ("connections.php");
include ("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //something was posted
    $newusername = $_POST['New_username'];
    $newpassword = $_POST['New_password'];
    $id = $_SESSION['user_id'];
    $passpattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';
    if (!empty($newusername) && !empty($newpassword)) {
        
      $query = "SELECT username FROM account_details where username = '$newusername'";
      $newusernameresult = mysqli_query($con, $query);
      $query = "SELECT username FROM account_details WHERE user_id = '$id' LIMIT 1";
      $checkdupeusername = mysqli_query($con, $query);

      if (mysqli_num_rows($newusernameresult) > 0){
        echo "<script>alert('Username already exists!');</script>";

      }elseif (!preg_match($passpattern, $newpassword)){
        echo "<script>alert('Password must be at least 8 characters and include uppercase, lowercase, and number');</script>";
      }else{
 //update to database
        $query = "update account_details set password = '$newpassword', username = '$newusername', newaccountstatus = '0' where user_id = '".$_SESSION['user_id']."'";
        $result = mysqli_query($con, $query);
        $query = "insert into system_log(log_id, user_id, first_name, last_name, action) values ('".random_num(10)."', '$id',  '".$_SESSION['first_name']."', '".$_SESSION['last_name']."', 'Updated account details')";
        mysqli_query($con, $query);

        echo "<script>alert('Account details updated successfully!');</script>";
            if ($result['New_password'] == $password) {
                $query = "SELECT user_status FROM account_details WHERE user_id = '$id' LIMIT 1";
                $result = mysqli_query($con, $query);
                $row = mysqli_fetch_assoc($result);
                if ($row['user_status'] == 'tenant'){
                    header("Location: tenant-dashboard.php");
                    die;
                } elseif ($row['user_status'] == 'staff'){
                    header("Location: staff-dashboard.php");
                die;
                }elseif ($result['user_data'] == 'manager'){
                header("Location: manager-dashboard.php"); 
            }else {
        echo "Please enter some valid information!";
        }     
            }
    } 
      }else {
        echo "Please enter some valid information!";
        }     

       
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change details</title>
       <link href="" rel="stylesheet">
    <link href="" rel="stylesheet">
    <link rel="stylesheet" href="style2.css">
</head>

<body class="change-account-page"></body>
    <a href="manager-dashboard.php">GO BACK</a>
    <h2>Change Account Details</h2>
    <form method="post">
        <input type="text" name="New_username" placeholder="New Username" required><br>
        <input type="password" name="New_password" placeholder="New Password" required><br>
        <input type="submit" value="Update Details">
    </form>
</body>
</html>