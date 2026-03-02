<?php
session_start();
include("../includes/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$id = $_GET['id'];

if(isset($_POST['update'])){

    $vehicle_number = $_POST['vehicle_number'];
    $vehicle_type = $_POST['vehicle_type'];
    $lane_no = $_POST['lane_no'];

    mysqli_query($conn,"UPDATE vehicle SET 
        vehicle_number='$vehicle_number',
        vehicle_type='$vehicle_type',
        lane_no='$lane_no'
        WHERE vehicle_id='$id'");

    header("Location: vehicles_list.php");
}

$result = mysqli_query($conn,"SELECT * FROM vehicle WHERE vehicle_id='$id'");
$row = mysqli_fetch_assoc($result);
?>

<div class="content">
<div class="topbar">
    <strong>Edit Vehicle</strong>
</div>

<form method="post" class="card" style="width:400px;">

<label>Vehicle Number</label>
<input type="text" name="vehicle_number" 
value="<?php echo $row['vehicle_number']; ?>" required><br><br>

<label>Vehicle Type</label>
<select name="vehicle_type">
    <option <?php if($row['vehicle_type']=="Car") echo "selected"; ?>>Car</option>
    <option <?php if($row['vehicle_type']=="Bike") echo "selected"; ?>>Bike</option>
    <option <?php if($row['vehicle_type']=="Truck") echo "selected"; ?>>Truck</option>
</select><br><br>

<label>Lane Number</label>
<input type="number" name="lane_no" min="1" max="4"
value="<?php echo $row['lane_no']; ?>" required><br><br>

<button type="submit" name="update">Update Vehicle</button>

</form>
</div>

</body>
</html>