<?php
session_start();
include("connections.php");
include("functions.php");

$editData = null;

// UPDATE UNIT
if (isset($_POST['update'])) {
    $unit_id = $_POST['unit_id'];
    $stmt = $con->prepare("UPDATE unit_info SET unit_name=?, unit_size=?, unit_price=?, unit_status=?, unit_description=? WHERE unit_id=?");
    $stmt->bind_param("ssdssi", $_POST['unit_name'], $_POST['unit_size'], $_POST['unit_price'], $_POST['unit_status'], $_POST['unit_description'], $unit_id );
    $stmt->execute();

    // Upload new images
    if (isset($_FILES['unit_image'])) {
        foreach ($_FILES['unit_image']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['unit_image']['error'][$key] == 0) {
                $img_data = file_get_contents($tmp_name);
                $img_type = $_FILES['unit_image']['type'][$key];
                $img_stmt = $con->prepare("INSERT INTO unit_images (unit_id, unit_image, image_type) VALUES (?, ?, ?)");
                $null = NULL;
                $img_stmt->bind_param("ibs", $unit_id, $null, $img_type);
                $img_stmt->send_long_data(1, $img_data);
                $img_stmt->execute();
            }
        }
    }

    $query = "INSERT INTO system_log(log_id, user_id, first_name, last_name, action) VALUES ('".random_num(10)."', '".$_SESSION['user_id']."', '".$_SESSION['first_name']."', '".$_SESSION['last_name']."', 'Edited unit ID $unit_id')";
    mysqli_query($con, $query);
    header("Location: edit_unit.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Units</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
<style>
.units-table {
    opacity: 0.7;
    width: 100%;
    margin-bottom: 10px;
    border-collapse: collapse;
}
.units-table th, .units-table td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
}
.units-images img {
    width: 120px;
    margin: 5px;
}
.edit-unit-container {
    display: none;
    opacity: 0.9;
    background: white;
    padding: 15px;
    border-radius: 15px;
    margin-top: 20px;
}
.units-button {
    padding: 8px 16px;
    border-radius: 8px;
    background-color: #007BFF;
    color: white;
    border: none;
    cursor: pointer;
    margin-top: 10px;
}
.units-button:hover {
    background-color: #0056b3;
}
</style>
</head>
<body class="body-user">

<header>
<nav class="navbar2">
    <h2 class="logo-text">Edit Units</h2>
    <a href="unit_management.php" class="nav-link" id="booknow-btn">⤸ Manage Units</a>
</nav>
</header>

<div class="units-container">
<h2>Units List</h2>
<form method="POST" id="selectForm">
<?php
$result = mysqli_query($con, "SELECT * FROM unit_info");
while ($row = mysqli_fetch_assoc($result)) {
?>
<table class="units-table">
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
    <td><input type="radio" name="unit_id" value="<?= $row['unit_id'] ?>" required></td>
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
<button type="submit" name="select_edit" class="units-button">Edit Selected Unit</button>
</form>
</div>

<div class="edit-unit-container">
<h3>Edit Unit</h3>
<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="unit_id" value="">

<label>Name</label>
<input type="text" name="unit_name" value="" required>

<label>Size</label>
<select name="unit_size" required>
<option value="small">Small</option>
<option value="medium">Medium</option>
<option value="large">Large</option>
</select>

<label>Price</label>
<input type="number" step="0.01" name="unit_price" value="" required>

<label>Status</label>
<select name="unit_status" required>
<option value="available">Available</option>
<option value="pending">Pending</option>
<option value="booked">Sold</option>
</select>

<label>Description</label>
<textarea name="unit_description"></textarea>

<label>Add More Images</label>
<input type="file" name="unit_image[]" accept="image/*" multiple>

<button type="submit" name="update" class="units-button">Update</button>
<button type="button" onclick="window.location='edit_unit.php'" class="units-button">Cancel</button>
</form>
</div>

<div class="bg-wrapper">
  <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070&auto=format&fit=crop" alt="Background">
</div>

<script>
const selectForm = document.getElementById('selectForm');
const editUnitContainer = document.querySelector('.edit-unit-container');

selectForm.addEventListener('submit', function(e){
    const selectedRadio = document.querySelector('input[name="unit_id"]:checked');
    if(!selectedRadio){
        alert("Please select a unit first.");
        e.preventDefault();
        return;
    }

    const row = selectedRadio.closest('tr');
    editUnitContainer.style.display = 'block';
    editUnitContainer.querySelector('input[name="unit_id"]').value = selectedRadio.value;
    editUnitContainer.querySelector('input[name="unit_name"]').value = row.children[2].textContent;
    editUnitContainer.querySelector('select[name="unit_size"]').value = row.children[3].textContent.toLowerCase();
    editUnitContainer.querySelector('input[name="unit_price"]').value = row.children[4].textContent;
    editUnitContainer.querySelector('select[name="unit_status"]').value = row.children[5].textContent.toLowerCase();
    editUnitContainer.querySelector('textarea[name="unit_description"]').value = row.children[6].textContent;

    editUnitContainer.scrollIntoView({ behavior: 'smooth' });

    e.preventDefault(); 
});
</script>

</body>
</html>
