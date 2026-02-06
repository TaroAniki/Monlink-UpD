
<?php
session_start();

include ("connections.php");
include ("functions.php");
$booking_data = [
    'first_name' => '',
    'middle_name' => '',
    'last_name' => '',
    'email' => '',
    'phone_number' => ''
];


     if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['submit_id'])){
                    $booking_id = $_POST['booking_id'];

                    $query = "SELECT * FROM booking_details where booking_id = '$booking_id' and booking_status = 'accepted' limit 1";
                    $bookingidretrieve = mysqli_query($con, $query);

                    if (mysqli_num_rows($bookingidretrieve) > 0){
                         $booking_data = mysqli_fetch_assoc($bookingidretrieve);
                        
                    }else{
                        echo "ID not found or not accepted yet!";
                    }
        
     }

        elseif (isset($_POST['submit_acc'])){
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
        $query = "insert into account_details (user_id, first_name, middle_name, last_name, email, phone_number, username, password, newaccountstatus, user_status) values ('$user_id', '$first_name', '$middle_name', '$last_name', '$email', '$phone_number', '$username', '$password', '1', 'tenant')";
        mysqli_query($con, $query);
        $query = "SELECT unit_selected from booking_details WHERE booking_id = '$booking_id' limit 1 ";
        mysqli_query($con, $query);
        $query = "insert into tenant_details (tenant_id, first_name, middle_name, last_name, phone_number, email, unit, amount_due) values ('$user_id, '$first_name', '$middle_name', '$last_name', '$phone_number', '$email', '$unit', '0')";
        mysqli_query($con, $query);
        $query = "insert into system_log(log_id, user_id, first_name, last_name, action) values ('".random_num(10)."', '".$_SESSION['user_id']."',  '".$_SESSION['first_name']."', '".$_SESSION['last_name']."', 'Created a tenant account for $first_name $last_name')";
        mysqli_query($con, $query);
         $query = "DELETE FROM booking_details where email = '$email'";
            mysqli_query($con, $query);
            $query = "UPDATE unit_info SET unit_status = 'booked' WHERE unit_id = (SELECT unit_selected FROM booking_details WHERE email = '$email' LIMIT 1)";
            mysqli_query($con, $query);
            $query = "SELECT user_status FROM account_details where user_id = '$id' LIMIT 1";
            $result = mysqli_query($con, $query);
            
         
        echo "<script>alert('Account created successfully!'); window.location.href='staff-dashboard.php';</script>";
        die;
    } 
        
    }else {
        echo "<script>alert('Please enter some valid information!');</script>";


        
    }
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
    <body class="create-account-page"></body>
   
    <div>
        <a href="manager-dashboard.php">GO BACK</a>  </div>
        <h2>BOOKING</h2>
  
    <div2>
        <form method="post">
            <input type="text" name="booking_id" placeholder="Input Booking ID">
            <input type="submit" name = "submit_id" value="Input Details">
        </form>
    </div2>
    <div3>
        <form method="post">
            <input type="text" name="first_name" placeholder="First Name" required value="<?php echo htmlspecialchars($booking_data['first_name']); ?>"><br>
            <input type="text" name="middle_name" placeholder="Middle Name" value="<?php echo htmlspecialchars($booking_data['middle_name']); ?>"><br>
            <input type="text" name="last_name" placeholder="Last Name" required value="<?php echo htmlspecialchars($booking_data['last_name']); ?>"><br>
            <input type="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($booking_data['email']); ?>"><br>
            <input type="number" name="phone_number" placeholder="Phone Number" required value="<?php echo htmlspecialchars($booking_data['phone_number']); ?>"><br>
            <input type="submit" name="submit_acc" value="Create Account"><br>
        </form>
  </div3>
</body>
</html>