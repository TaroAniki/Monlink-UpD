<?php
session_start();
include("connections.php");
include("functions.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Units</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body class="body-user">

<header>
    <nav class="navbar2">
        <h2 class="logo-text">View Units</h2>
        <a href="unit_management.php" class="nav-link" id="booknow-btn">⤸ Manage Units</a>
    </nav>
</header>

<div class="units-container">
<h2>All Units</h2>

<?php
$result = mysqli_query($con, "SELECT * FROM unit_info");
while ($row = mysqli_fetch_assoc($result)) {
?>
<table class="units-table">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Size</th>
    <th>Price</th>
    <th>Status</th>
    <th>Description</th>
</tr>
<tr>
    <td><?= $row['unit_id'] ?></td>
    <td><?= $row['unit_name'] ?></td>
    <td><?= $row['unit_size'] ?></td>
    <td><?= $row['unit_price'] ?></td>
    <td><?= $row['unit_status'] ?></td>
    <td><?= $row['unit_description'] ?></td>
</tr>
</table>

<div class="units-images">
<?php
$imgs = mysqli_query($con, "SELECT * FROM unit_images WHERE unit_id=" . $row['unit_id']);
if(mysqli_num_rows($imgs) > 0){
    while ($imgRow = mysqli_fetch_assoc($imgs)) {
        echo '<img src="data:' . $imgRow['image_type'] . ';base64,' . base64_encode($imgRow['unit_image']) . '">';
    }
} else {
    echo "No Images Uploaded Yet.";
}
?>
</div>
<hr>
<?php } ?>
</div>

<div class="bg-wrapper">
  <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop" alt="Background">
</div>

</body>
</html>
