<?php
session_start();
include ("connections.php");
include ("functions.php");
$user_data = check_login($con);
$search_id = "";

 // 1. Handle POST Action (Accepting Booking)
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['reject_booking'])) {
        $booking_id = $_POST['booking_id'];
        if (!empty($booking_id)) {
            $query = "UPDATE unit_info SET unit_status = 'available' WHERE unit_name = (SELECT unit_selected FROM booking_details WHERE booking_id = '$booking_id' LIMIT 1)";
            mysqli_query($con, $query);
            $query = "insert into system_log(log_id, user_id, first_name, last_name, action) values ('".random_num(10)."', '".$_SESSION['user_id']."',  '".$_SESSION['first_name']."', '".$_SESSION['last_name']."', 'Rejected a booking of ID $booking_id')";
            mysqli_query($con, $query);
            $query = "DELETE FROM booking_details WHERE booking_id = '$booking_id'";
            mysqli_query($con, $query);
           
            
            echo "<script>alert('Booking ID $booking_id has been rejected and removed.'); window.location.href='staff-booking-details.php';</script>";
            die;
        }
    }elseif (isset($_POST['accept_booking'])) {
    $booking_id = $_POST['booking_id'];
    if (!empty($booking_id)) {
        $query = "insert into system_log(log_id, user_id, first_name, last_name, action) values ('".random_num(10)."', '".$_SESSION['user_id']."',  '".$_SESSION['first_name']."', '".$_SESSION['last_name']."', 'Accepted a booking of ID $booking_id')";
        mysqli_query($con, $query);
        $query = "UPDATE booking_details SET booking_status = 'accepted' WHERE booking_id = '$booking_id'";
        mysqli_query($con, $query);
        $query = "UPDATE unit_info SET unit_status = 'booked' WHERE unit_name = (SELECT unit_selected FROM booking_details WHERE booking_id = '$booking_id' LIMIT 1)";
        mysqli_query($con, $query);
        echo "<script>alert('Booking ID $booking_id has been accepted.'); window.location.href='staff-booking-details.php';</script>";
        die;
    }
}
}
// 2. Handle Data Retrieval (The "View" Logic)
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_id = mysqli_real_escape_string($con, $_GET['search']);
    $query = "SELECT * FROM booking_details WHERE booking_id = '$search_id' AND booking_status = 'pending'";
} else {
    // DEFAULT: Show all pending bookings when the page first loads
    $query = "SELECT * FROM booking_details WHERE booking_status = 'pending'";
}

$results = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Units</title>
        <link href="" rel="stylesheet">
    <link href="" rel="stylesheet">
    <link rel="stylesheet" href="style2.css">
</head>
<body class="staff-dashboard-page">

<hr>
<a href="staff-dashboard.php">Back to Dashboard</a>
<h2>View Booking Details</h2>
<form method="get" action="staff-booking-details.php">
    <input type="text" name="search" placeholder="Search by Booking ID">
    <input type="submit" value="Search">
    <input type="button" value="Reset" onclick="window.location.href='staff-booking-details.php'">
</form>
<form method="post" action="staff-booking-details.php">
<table border="1" cellpadding="8">
    <tr>
        <th>Select</th>
        <th>Booking ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Unit Selected</th>
        <th>Mode of Payment</th>
    </tr>
<tbody>
    
<?php 

if (mysqli_num_rows($results) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($results)): ?>
                    <tr>
                        <td><input type="radio" name="booking_id" value="<?php echo $row['booking_id']; ?>" required></td>
                        <td><?php echo $row['booking_id']; ?></td>
                        <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['unit_selected']; ?></td>
                        <td><?php echo $row['booking_status']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center;">No records found.</td>
                </tr>
            <?php endif; ?>
</tbody>
</table>
<br>
<button type="submit" name="accept_booking">Accept</button>
<button type="submit" name="reject_booking">Reject</button>
</form>
</body>
</html>
