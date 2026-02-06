<?php
session_start();

include("connections.php");
include("functions.php");

$error = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    // Security: Prevent SQL Injection
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {

        $query = "select * from account_details where username = '$username' limit 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            
            if ($user_data['password'] == $password) {
                
                $_SESSION['user_id'] = $user_data['user_id'];

                $log_id = random_num(10); 
                $uid = $_SESSION['user_id'];
                $fname = $user_data['first_name'];
                $lname = $user_data['last_name'];
                
                $log_query = "insert into system_log(log_id, user_id, first_name, last_name, action) values ('$log_id', '$uid', '$fname', '$lname', 'Logged in')";
                mysqli_query($con, $log_query);

                if ($user_data['newaccountstatus'] == '1') {
                    header("Location: change-acc-details.php");
                    die;
                } else {
                    if ($user_data['user_status'] == 'staff') {
                        header("Location: staff-dashboard.php");
                        die;
                    } elseif ($user_data['user_status'] == 'tenant') {
                        header("Location: tenant-dashboard.php");
                        die;
                    } elseif ($user_data['user_status'] == 'manager') {
                        header("Location: manager-dashboard.php");
                        die;
                    } else {
                        $error = "Unknown user role.";
                    }
                }
            } else {
                $error = "Invalid credentials.";
            }
        } else {
            $error = "Invalid credentials.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="" rel="stylesheet">
    <link href="" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="body-login">

    <div class="login-container">
       
        <div class="login-content">
             <div class ="home-page-btn">
                <i class="ri-arrow-left-line"></i>
            <a href="home-page.php">Back to Home</a>
        </div>
            <form method="post" autocomplete="off" class="login-form">
                
                <h1 class="login-title">Welcome Back</h1>
                <p class="login-description">Please enter your details.</p>

                <?php if($error != ""): ?>
                    <div class="error-msg"><?php echo $error; ?></div>
                <?php endif; ?>

                <div class="login-box">
                    <i class="ri-user-3-line login-icon"></i>
                    <div class="login-box-input">
                        <input type="text" name="username" required class="login-input" id="login-email" placeholder=" ">
                        <label for="login-email" class="login-label">Username</label>
                    </div>
                </div>

                <div class="login-box">
                    <i class="ri-lock-2-line login-icon"></i>
                    <div class="login-box-input">
                        <input type="password" name="password" required class="login-input" id="login-pass" placeholder=" ">
                        <label for="login-pass" class="login-label">Password</label>
                    </div>
                </div>

                <div class="login-forgot">
                    
                    <a href="#">Forgot Password?</a>
                </div>

                <button type="submit" class="login-button">Login</button>

                <p class="login-register">
                    Don't have an account? <a href="booking.php">Book now!</a>
                </p>
            </form>
        </div>

        <div class="login-img">
            <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop" alt="Login Image">
        </div>
        
    </div>

</body>
</html>