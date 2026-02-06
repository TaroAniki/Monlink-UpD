<?php
session_start() ;
include ("connections.php");
include ("functions.php");

if (isset($_POST['delete_selected'])) {
    if (!empty($_POST['unit_ids'])) {
        foreach ($_POST['unit_ids'] as $id) {
            mysqli_query($con, "DELETE FROM unit_info WHERE unit_id=$id");
            mysqli_query($con, "DELETE FROM unit_images WHERE unit_id=$id"); // delete images
            $query = "INSERT INTO system_log(log_id, user_id, first_name, last_name, action) 
                      VALUES ('".random_num(10)."', '".$_SESSION['user_id']."',  '".$_SESSION['first_name']."', '".$_SESSION['last_name']."', 'Deleted unit ID $id')";
            mysqli_query($con, $query);
        }
    }
    header("Location: delete_unit.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Units</title>


    <link rel="stylesheet" href="style2.css">
</head>
<body class="delete-unit-page">

<form action="unit_management.php" method="get" style="display:inline-block;">
    <button>Back to Manager</button>
</form>
<button onclick="location.reload()">Refresh</button>
<hr>

<h2>Select Units to Delete</h2>
<form method="POST">
<?php
$result = mysqli_query($con, "SELECT * FROM unit_info");
while ($row = mysqli_fetch_assoc($result)) {
?>
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
<tr>
    <td><input type="checkbox" name="unit_ids[]" value="<?= $row['unit_id'] ?>" ></td>
    <td><?= $row['unit_id'] ?></td>
    <td><?= $row['unit_name'] ?></td>
    <td><?= $row['unit_size'] ?></td>
    <td><?= $row['unit_price'] ?></td>
    <td><?= $row['unit_status'] ?></td>
    <td><?= $row['unit_description'] ?></td>
</tr>
</table>

<!-- Display Images Below Table -->
<?php
$imgs = mysqli_query($con, "SELECT * FROM unit_images WHERE unit_id=" . $row['unit_id']);
if(mysqli_num_rows($imgs) > 0){
    while ($imgRow = mysqli_fetch_assoc($imgs)) {
        echo '<img src="data:' . $imgRow['image_type'] . ';base64,' . base64_encode($imgRow['unit_image']) . '" width="100" style="margin:5px;">';
    }
} else {
    echo "No Images Uploaded Yet.";
}
echo "<hr>";
?>
<?php } ?>

<button type="submit" name="delete_selected" 
onclick="return confirm('Are you sure you want to delete the selected unit(s)?')">Delete</button>
</form>

</body>
</html>
