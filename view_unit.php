<?php
session_start();
include ("connections.php");
include ("functions.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Units</title>
        <link href="" rel="stylesheet">
    <link href="" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<button onclick="window.location='home-page.php'">⬅ Back to Home Page</button>
<button onclick="location.reload()">Refresh</button>
<hr>

<h2>View All Units</h2>

<?php
$result = mysqli_query($con, "SELECT * FROM unit_info");
while ($row = mysqli_fetch_assoc($result)) {
?>
<table border="1" cellpadding="8" style="margin-bottom:10px; width: 80%;">
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

</body>
</html>
