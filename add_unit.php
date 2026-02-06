<?php
session_start();
include ("connections.php");
include ("functions.php");
$user_data = check_login($con);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $unit_name        = $_POST['unit_name'];
    $unit_size        = $_POST['unit_size'];
    $unit_price       = $_POST['unit_price'];
    $unit_status      = $_POST['unit_status'];
    $unit_description = $_POST['unit_description'];

    // Insert unit without image into unit_info
    $stmt = $con->prepare("INSERT INTO unit_info (unit_name, unit_size, unit_price, unit_status, unit_description) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $unit_name, $unit_size, $unit_price, $unit_status, $unit_description);

    if ($stmt->execute()) {
        $unit_id = $stmt->insert_id; // get last inserted unit_id

        // Insert multiple images if uploaded
        if(isset($_FILES['unit_image'])){
            foreach ($_FILES['unit_image']['tmp_name'] as $key => $tmp_name){
                if ($_FILES['unit_image']['error'][$key] == 0) {
                    $img_data = file_get_contents($tmp_name);
                    $img_type = $_FILES['unit_image']['type'][$key];
                    $img_stmt = $con->prepare("INSERT INTO unit_images (unit_id, unit_image, image_type) VALUES (?, ?, ?)");
                    $img_stmt->bind_param("ibs", $unit_id, $img_data, $img_type);
                    $img_stmt->send_long_data(1, $img_data);
                    $img_stmt->execute();
                }
            }
        }

        $query = "INSERT INTO system_log(log_id, user_id, first_name, last_name, action) 
                  VALUES ('".random_num(10)."', '".$_SESSION['user_id']."',  '".$_SESSION['first_name']."', '".$_SESSION['last_name']."', 'Added a new unit: $unit_name')";
        mysqli_query($con, $query);

        echo "<script>alert('Unit Successfully added!'); window.location.href='unit_management.php';</script>";
        exit();
    } else {
        die("Error adding unit: " . $stmt->error);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
        <title>Add Unit</title>


    <link rel="stylesheet" href="style2.css">
</head>
<body class="add-unit-page">


<form action="unit_management.php" method="get" style="display:inline-block;">
    <button type="submit">Back to Manager</button>
</form>

<button onclick="location.reload()">Refresh</button>
<hr>

<h1>ADD UNIT</h1>

<form method="POST" enctype="multipart/form-data"> 
    <label>Unit Name</label><br>
    <input type="text" name="unit_name" required><br><br>

    <label>Unit Size</label><br>
    <input type="text" name="unit_size" required><br><br>

    <label>Unit Price</label><br>
    <input type="number" step="0.01" name="unit_price" required><br><br>

    <label>Unit Status</label><br>
    <select name="unit_status" required>
        <option value="available">Available</option>
        <option value="pending">Pending</option>
        <option value="booked">Sold</option>
    </select><br><br>

    <label>Unit Description </label><br>
    <textarea name="unit_description"></textarea><br><br>

    <label>Unit Images (multiple allowed)</label><br>
    <input type="file" name="unit_image[]" accept="image/*" multiple><br><br>

    <button type="submit">ADD UNIT</button>
</form>

</body>
</html>
