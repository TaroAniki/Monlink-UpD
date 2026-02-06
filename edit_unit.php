<?php
session_start();
include("connections.php");
include("functions.php");

// UPDATE UNIT
if (isset($_POST['update'])) {
    $unit_id = $_POST['unit_id'];

    // Update unit info
    $stmt = $con->prepare("UPDATE unit_info SET unit_name=?, unit_size=?, unit_price=?, unit_status=?, unit_description=? WHERE unit_id=?");
    $stmt->bind_param("ssdssi",
        $_POST['unit_name'],
        $_POST['unit_size'],
        $_POST['unit_price'],
        $_POST['unit_status'],
        $_POST['unit_description'],
        $unit_id
    );
    $stmt->execute();

    // Upload new images (multiple)
    if (isset($_FILES['unit_image'])) {
        foreach ($_FILES['unit_image']['tmp_name'] as $key => $tmp_name) {
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

    // Log action
    $query = "INSERT INTO system_log(log_id, user_id, first_name, last_name, action) 
              VALUES ('".random_num(10)."', '".$_SESSION['user_id']."', '".$_SESSION['first_name']."', '".$_SESSION['last_name']."', 'Edited unit ID $unit_id')";
    mysqli_query($con, $query);

    header("Location: edit_unit.php");
    exit();
}

// SELECT UNIT TO EDIT
$editData = null;
if (isset($_POST['select_edit']) && !empty($_POST['unit_id'])) {
    $res = mysqli_query($con, "SELECT * FROM unit_info WHERE unit_id=" . $_POST['unit_id']);
    if ($res && mysqli_num_rows($res) > 0) {
        $editData = mysqli_fetch_assoc($res);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Unit</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body class="edit-unit-page">

<!-- BACK & REFRESH -->
<form action="unit_management.php" method="get" style="display:inline-block;"><button>Back to Manager</button></form>
<button onclick="location.reload()">Refresh</button>
<hr>

<!-- UNITS TABLE -->
<h2>Units List</h2>
<table border="1" cellpadding="8" width="100%">

<tr>
    <th>Select</th>
    <th>ID</th>
    <th>Name</th>
    <th>Size</th>
    <th>Price</th>
    <th>Status</th>
    <th>Description</th>
</tr>

<?php
$result = mysqli_query($con, "SELECT * FROM unit_info");
while ($row = mysqli_fetch_assoc($result)) {
?>
<tr>
    <td><input type="radio" name="unit_id" value="<?= $row['unit_id'] ?>" form="selectForm" required></td>
    <td><?= $row['unit_id'] ?></td>
    <td><?= $row['unit_name'] ?></td>
    <td><?= $row['unit_size'] ?></td>
    <td><?= $row['unit_price'] ?></td>
    <td><?= $row['unit_status'] ?></td>
    <td><?= $row['unit_description'] ?></td>
</tr>
<tr>
    <td colspan="7" style="padding:5px;">
        <!-- SHOW ALL IMAGES FOR THIS UNIT BELOW THE ROW -->
        <?php
        $imgs = mysqli_query($con, "SELECT * FROM unit_images WHERE unit_id=" . $row['unit_id']);
        if (mysqli_num_rows($imgs) > 0) {
            while ($imgRow = mysqli_fetch_assoc($imgs)) {
                echo '<img src="data:' . $imgRow['image_type'] . ';base64,' . base64_encode($imgRow['unit_image']) . '" width="150" style="margin:5px;">';
            }
        } else {
            echo "No Images Uploaded Yet.";
        }
        ?>
    </td>
</tr>
<?php } ?>
</table>

<!-- FORM TO SELECT UNIT FOR EDIT -->
<form method="POST" id="selectForm">
<br>
<button type="submit" name="select_edit">Edit Selected Unit</button>
</form>

<!-- EDIT FORM -->
<?php if ($editData) { ?>
<hr>
<h3>Edit Unit ID <?= $editData['unit_id'] ?></h3>
<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="unit_id" value="<?= $editData['unit_id'] ?>">

Name:<br>
<input type="text" name="unit_name" value="<?= $editData['unit_name'] ?>" required><br><br>

Size:<br>
<input type="text" name="unit_size" value="<?= $editData['unit_size'] ?>" required><br><br>

Price:<br>
<input type="number" step="0.01" name="unit_price" value="<?= $editData['unit_price'] ?>" required><br><br>

Status:<br>
<input type="text" name="unit_status" value="<?= $editData['unit_status'] ?>" required><br><br>

Description:<br>
<textarea name="unit_description"><?= $editData['unit_description'] ?></textarea><br><br>

<!-- EXISTING IMAGES -->
<h4>Existing Images</h4>
<?php
$imgs = mysqli_query($con, "SELECT * FROM unit_images WHERE unit_id=" . $editData['unit_id']);
if (mysqli_num_rows($imgs) > 0) {
    while ($imgRow = mysqli_fetch_assoc($imgs)) {
        echo '<img src="data:' . $imgRow['image_type'] . ';base64,' . base64_encode($imgRow['unit_image']) . '" width="150" style="margin:5px;">';
    }       
} else {
    echo "No Images Uploaded Yet.";
}
?>
<br><br>

<!-- ADD NEW IMAGES -->
Add More Images:<br>
<input type="file" name="unit_image[]" accept="image/*" multiple><br><br>

<button type="submit" name="update">Update</button>
<button type="button" onclick="window.location='edit_unit.php'">Cancel</button>
</form>
<?php } ?>

</body>
</html>
