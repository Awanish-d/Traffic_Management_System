<?php
if(!isset($_SESSION)) session_start();
$current = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <h2>TrafficMS</h2>

    <a href="dashboard.php" class="<?php if($current=='dashboard.php') echo 'active'; ?>">Dashboard</a>
    <a href="vehicle.php" class="<?php if($current=='vehicle.php') echo 'active'; ?>">Add Vehicle</a>
    <a href="vehicles_list.php" class="<?php if($current=='vehicles_list.php') echo 'active'; ?>">View Vehicles</a>
    <a href="violation.php" class="<?php if($current=='violation.php') echo 'active'; ?>">Add Violation</a>
    <a href="violations_list.php" class="<?php if($current=='violations_list.php') echo 'active'; ?>">View Violations</a>

    <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>
        <a href="signal.php" class="<?php if($current=='signal.php') echo 'active'; ?>">Signal Management</a>
        <a href="report.php" class="<?php if($current=='report.php') echo 'active'; ?>">Reports</a>
    <?php } ?>

    <a href="../logout.php" onclick="return confirmLogout();">Logout</a>
</div>