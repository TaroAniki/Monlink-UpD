<?php
session_start();

include ("connections.php");
include ("functions.php");

$query = "SELECT * FROM unit_info where unit_status = 'available'";
$units = mysqli_query($con, $query);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    //something was posted
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $unit_selected = $_POST['unit_selected'];

    if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($phone_number ) && !empty($unit_selected) && $unit_selected != "Select Unit") {

    $query = "SELECT email FROM account_details where email = '$email'";
      $useremailchecker = mysqli_query($con, $query);

      if (mysqli_num_rows($useremailchecker) > 0){
        echo "<script>alert('choose a different email');</script>";

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
        $query = "INSERT into booking_details (booking_id, first_name, middle_name, last_name, email, phone_number, unit_selected, booking_status) values ('$user_id', '$first_name', '$middle_name', '$last_name', '$email', '$phone_number', '$unit_selected', 'pending')";
        
        mysqli_query($con, $query);
        $query = "UPDATE unit_info SET unit_status = 'pending' WHERE unit_name = '$unit_selected'";
        mysqli_query($con, $query);
       $query = "insert into system_log(log_id, user_id, first_name, last_name, action) values ('".random_num(10)."', '".$_SESSION['user_id']."',  '".$_POST['first_name']."', '".$_POST['last_name']."', 'Booked a unit $unit_selected for $first_name $last_name')";
        mysqli_query($con, $query);
         echo "<script>alert('Booking Successfully created! Please wait for staff approval.'); window.location.href='home-page.php';</script>";

        
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
    <title>Book Unit</title>
        <link href="" rel="stylesheet">
    <link href="" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="home-page.php">⬅ Back to Home Page</a>
    <div>
        <form method="post">
            <div>Book</div>
            <input type="text" name="first_name" placeholder="First Name" required><br>
            <input type="text" name="middle_name" placeholder="Middle Name"><br>
            <input type="text" name="last_name" placeholder="Last Name" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="number" name="phone_number" placeholder="Phone Number" required><br>
            <select name="unit_selected" id="unit_selected" required>
            <option value="Select Unit"></option>
            <?php if ($units->num_rows > 0) { 
                while ($row = $units->fetch_assoc()) {
                   echo '<option value="' . htmlspecialchars($row['unit_name']) . '">' . htmlspecialchars($row['unit_name']) . '</option>';
                    }
                    } else {
                    echo '<option value="">No units available</option>';
                }
                ?>
            </select><br>
            <select name="payment_method" id="payment_method" required>
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="GCash">GCash</option>
            </select><br>
            <p>Cash is only available in on-site booking</p>
            <input type="submit" value="Book Unit"><br>
        </form>
    </div>
</body>
</html>