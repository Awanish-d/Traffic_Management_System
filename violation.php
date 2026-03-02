<?php
session_start();
date_default_timezone_set("Asia/Kolkata");

include("../includes/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");

if(isset($_POST['submit'])){

    $vehicle_id = $_POST['vehicle_id'];
    $violation_type = $_POST['violation_type'];
    $violation_date = date("Y-m-d");
    $violation_time = date("H:i:s");
    $due_date = date("Y-m-d", strtotime("+7 days"));

    $recorded_by = $_SESSION['user_id']; // <-- real user

    if($violation_type == "Over Speed"){
        $fine_amount = 1000;
    }
    elseif($violation_type == "Signal Jump"){
        $fine_amount = 1500;
    }
    else{
        $fine_amount = 500;
    }

    mysqli_query($conn,"INSERT INTO violation
(vehicle_id, violation_type, violation_date, violation_time, due_date, recorded_by, fine_amount, payment_status)
VALUES
('$vehicle_id','$violation_type','$violation_date','$violation_time','$due_date','$recorded_by','$fine_amount','pending')");
    echo "<script>alert('Violation Added Successfully');</script>";
}

$vehicles = mysqli_query($conn,"SELECT * FROM vehicle");
?>

<div class="content">
<div class="topbar">
    <strong>Add Violation</strong>
</div>

<form method="post" class="card" style="width:400px;">

<label>Select Vehicle</label>
<select name="vehicle_id" required>
    <option value="">Select Vehicle</option>
    <?php while($row=mysqli_fetch_assoc($vehicles)){ ?>
        <option value="<?php echo $row['vehicle_id']; ?>">
            <?php echo $row['vehicle_number']; ?>
        </option>
    <?php } ?>
</select><br><br>

<label>Violation Type</label>
<select name="violation_type">
    <option>Over Speed</option>
    <option>Signal Jump</option>
    <option>No Helmet</option>
</select><br><br>

<button type="submit" name="submit">Add Violation</button>

</form>

</div>

</body>
</html>