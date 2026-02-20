<?php
session_start();
include("connections.php");
include("functions.php");

$user_data = check_login($con);

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $unit_name        = $_POST['unit_name'];
    $unit_size        = $_POST['unit_size'];
    $unit_price       = $_POST['unit_price'];
    $unit_status      = $_POST['unit_status'];
    $unit_description = $_POST['unit_description'];

 
    $stmt = $con->prepare("
        INSERT INTO unit_info 
        (unit_name, unit_size, unit_price, unit_status, unit_description)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssdss",
        $unit_name,
        $unit_size,
        $unit_price,
        $unit_status,
        $unit_description
    );

    if ($stmt->execute()) {

        $unit_id = $stmt->insert_id;


        if (!empty($_FILES['unit_image']['name'][0])) {
            foreach ($_FILES['unit_image']['tmp_name'] as $key => $tmp_name) {

                if ($_FILES['unit_image']['error'][$key] === 0) {

                    $img_data = file_get_contents($tmp_name);
                    $img_type = $_FILES['unit_image']['type'][$key];

                    $img_stmt = $con->prepare("
                        INSERT INTO unit_images (unit_id, unit_image, image_type)
                        VALUES (?, ?, ?)
                    ");

                    $null = NULL;
                    $img_stmt->bind_param("ibs", $unit_id, $null, $img_type);
                    $img_stmt->send_long_data(1, $img_data);
                    $img_stmt->execute();
                }
            }
        }

        $log_stmt = $con->prepare("
            INSERT INTO system_log (log_id, user_id, first_name, last_name, action)
            VALUES (?, ?, ?, ?, ?)
        ");

        $log_id = random_num(10);
        $action = "Added a new unit: $unit_name";

        $log_stmt->bind_param(
            "sisss",
            $log_id,
            $_SESSION['user_id'],
            $_SESSION['first_name'],
            $_SESSION['last_name'],
            $action
        );

        $log_stmt->execute();

        echo "<script>
            alert('Unit successfully added!');
            window.location.href='unit_management.php';
        </script>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body class="body-user">

<header>
    <nav class="navbar2">
        <h2 class="logo-text">Add Unit</h2>
        <a href="unit_management.php" class="nav-link" id="booknow-btn">⤸ Manage Units</a>
    </nav>
</header>

<div class="form-wrapper">
    <form method="POST" enctype="multipart/form-data" class="add-unit-form">
        <div class="container">

            <div class="user-box">
                <div class="user-box-input">
                    <input type="text" name="unit_name" required class="user-input" id="user-n" placeholder=" ">
                    <label for="user-n" class="user-label">Unit Name</label>
                </div>
            </div>
            <div class="user-box">
                <div class="user-box-input">
                    <select name="unit_size" id="user-s" required class="user-input">
                        <option value="" disabled selected hidden></option>
                        <option value="small   ">Small</option>
                        <option value="medium">Medium</option>
                        <option value="large">Large</option>
                    </select>
                    <label for="user-s" class="user-label">Unit size</label>
                </div>
            </div>
            
            <div class="user-box">
                <div class="user-box-input">
                    <input type="number" name="unit_price" step="0.01" required class="user-input" id="user-p" placeholder=" ">
                    <label for="user-p" class="user-label">Unit Price</label>
                </div>
            </div>

            <div class="user-box">
                <div class="user-box-input">
                    <select name="unit_status" id="user-st" required class="user-input">
                        <option value="" disabled selected hidden></option>
                        <option value="available">Available</option>
                        <option value="pending">Pending</option>
                        <option value="booked">Sold</option>
                    </select>
                    <label for="user-st" class="user-label">Unit Status</label>
                </div>
            </div>

            <div class="user-box">
                <div class="user-box-input">
                    <textarea name="unit_description" required class="user-input" id="user-d" placeholder=" "></textarea>
                    <label for="user-d" class="user-label">Unit Description</label>
                </div>
            </div>

            <label>Unit Image/s</label>
            <input type="file" name="unit_image[]" accept="image/*" multiple>

            <button type="submit" class="add-button">ADD UNIT</button>

        </div> 
    </form>
</div>


<div class="bg-wrapper">
  <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop" alt="Background">
</div>

</body>

</html>

