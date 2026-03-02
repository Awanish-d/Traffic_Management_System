<?php
session_start();
date_default_timezone_set("Asia/Kolkata");

include("../includes/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");

if(isset($_POST['submit'])){

    $vehicle_number = $_POST['vehicle_number'];
    $vehicle_type = $_POST['vehicle_type'];
    $lane_no = $_POST['lane_no'];
    $entry_time = date("H:i:s");

    $added_by = $_SESSION['user_id']; // real logged in user

    mysqli_query($conn,"INSERT INTO vehicle
    (vehicle_number, vehicle_type, lane_no, entry_time, added_by)
    VALUES
    ('$vehicle_number','$vehicle_type','$lane_no','$entry_time','$added_by')");

    echo "<script>alert('Vehicle Added Successfully');</script>";
}
?>

<div class="content">

<div class="topbar">
    <strong>Add Vehicle</strong>
</div>

<form method="post" class="card" style="width:400px;">

<label>Vehicle Number</label>
<input type="text" name="vehicle_number" required><br><br>

<label>Vehicle Type</label>
<select name="vehicle_type">
    <option>Car</option>
    <option>Bike</option>
    <option>Truck</option>
</select><br><br>

<label>Lane Number (1-4)</label>
<input type="number" name="lane_no" min="1" max="4" required><br><br>

<button type="submit" name="submit">Add Vehicle</button>

</form>

</div>

</body>
</html>