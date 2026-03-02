<?php
session_start();
include("../includes/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");
if($_SESSION['role'] != 'admin'){
    header("Location: dashboard.php");
    exit();
}

if(isset($_POST['update'])) {

    $red_time = $_POST['red_time'];
    $yellow_time = $_POST['yellow_time'];
    $green_time = $_POST['green_time'];
    $mode = $_POST['mode'];

    mysqli_query($conn, "UPDATE traffic_signal 
    SET red_time='$red_time', 
        yellow_time='$yellow_time', 
        green_time='$green_time',
        mode='$mode'
    WHERE signal_id=1");

    echo "<script>alert('Signal Updated Successfully');</script>";
}

$result = mysqli_query($conn, "SELECT * FROM traffic_signal WHERE signal_id=1");
$data = mysqli_fetch_assoc($result);
?>

<div class="content">

<div class="topbar">
    Welcome, <?php echo $_SESSION['username']; ?>
</div>

<h2>Signal Management</h2>

<div style="display:flex; gap:40px;">

    <!-- Current Status Card -->
    <div style="background:white; padding:20px; width:300px;">
        <h3>Current Signal Status</h3>
        <p><strong>Red Time:</strong> <?php echo $data['red_time']; ?> sec</p>
        <p><strong>Yellow Time:</strong> <?php echo $data['yellow_time']; ?> sec</p>
        <p><strong>Green Time:</strong> <?php echo $data['green_time']; ?> sec</p>
        <p><strong>Mode:</strong> <?php echo strtoupper($data['mode']); ?></p>
    </div>

    <!-- Update Form -->
    <div style="background:white; padding:20px; width:350px;">
        <h3>Update Signal Timing</h3>

        <form method="post">

            <label>Red Time (sec)</label><br>
            <input type="number" name="red_time" value="<?php echo $data['red_time']; ?>" required><br><br>

            <label>Yellow Time (sec)</label><br>
            <input type="number" name="yellow_time" value="<?php echo $data['yellow_time']; ?>" required><br><br>

            <label>Green Time (sec)</label><br>
            <input type="number" name="green_time" value="<?php echo $data['green_time']; ?>" required><br><br>

            <label>Mode</label><br>
            <select name="mode">
                <option value="auto" <?php if($data['mode']=="auto") echo "selected"; ?>>Auto</option>
                <option value="manual" <?php if($data['mode']=="manual") echo "selected"; ?>>Manual</option>
            </select><br><br>

            <button type="submit" name="update">Update Signal</button>

        </form>

    </div>

</div>

</div>

</body>
</html>