<?php
session_start();
include("../includes/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$id = $_GET['id'];

if(isset($_POST['update'])){

    $violation_type = $_POST['violation_type'];
    $fine_amount = $_POST['fine_amount'];
    $payment_status = $_POST['payment_status'];

    mysqli_query($conn,"UPDATE violation SET
        violation_type='$violation_type',
        fine_amount='$fine_amount',
        payment_status='$payment_status'
        WHERE violation_id='$id'");

    header("Location: violations_list.php");
}

$result = mysqli_query($conn,"SELECT * FROM violation WHERE violation_id='$id'");
$row = mysqli_fetch_assoc($result);
?>

<div class="content">
<div class="topbar">
    <strong>Edit Violation</strong>
</div>

<form method="post" class="card" style="width:400px;">

<label>Violation Type</label>
<select name="violation_type">
    <option <?php if($row['violation_type']=="Over Speed") echo "selected"; ?>>Over Speed</option>
    <option <?php if($row['violation_type']=="Signal Jump") echo "selected"; ?>>Signal Jump</option>
    <option <?php if($row['violation_type']=="No Helmet") echo "selected"; ?>>No Helmet</option>
</select><br><br>

<label>Fine Amount</label>
<input type="number" name="fine_amount"
value="<?php echo $row['fine_amount']; ?>" required><br><br>

<label>Status</label>
<select name="payment_status">
    <option <?php if($row['payment_status']=="pending") echo "selected"; ?>>pending</option>
    <option <?php if($row['payment_status']=="paid") echo "selected"; ?>>paid</option>
</select><br><br>

<button type="submit" name="update">Update Violation</button>

</form>

</div>

</body>
</html>