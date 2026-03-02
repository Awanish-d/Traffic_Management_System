<?php
session_start();
include("../includes/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");

/* ===============================
   DELETE (Admin Only)
=================================*/
if(isset($_GET['delete']) && $_SESSION['role'] == 'admin') {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM vehicle WHERE vehicle_id=$id");
    header("Location: vehicles_list.php");
    exit();
}

/* ===============================
   SEARCH & FILTER
=================================*/
$search = $_GET['search'] ?? '';
$typeFilter = $_GET['type'] ?? 'all';
$laneFilter = $_GET['lane'] ?? 'all';

$where = " WHERE 1=1 ";

if(!empty($search)){
    $where .= " AND v.vehicle_number LIKE '%$search%' ";
}

if($typeFilter != 'all'){
    $where .= " AND v.vehicle_type='$typeFilter' ";
}

if($laneFilter != 'all'){
    $where .= " AND v.lane_no='$laneFilter' ";
}

$query = "
SELECT v.*, u.username
FROM vehicle v
LEFT JOIN users u ON v.added_by = u.user_id
$where
ORDER BY v.vehicle_id DESC
";

$result = mysqli_query($conn,$query);
?>

<div class="content">
<div class="topbar">
    <strong>All Vehicles</strong>
</div>

<!-- SEARCH BAR -->
<form method="GET" style="margin-bottom:20px;">
    <input type="text" name="search" placeholder="Search vehicle number"
           value="<?= htmlspecialchars($search) ?>">

    <select name="type">
        <option value="all">All Types</option>
        <option value="Car" <?= $typeFilter=='Car'?'selected':'' ?>>Car</option>
        <option value="Bike" <?= $typeFilter=='Bike'?'selected':'' ?>>Bike</option>
        <option value="Truck" <?= $typeFilter=='Truck'?'selected':'' ?>>Truck</option>
    </select>

    <select name="lane">
        <option value="all">All Lanes</option>
        <option value="1" <?= $laneFilter=='1'?'selected':'' ?>>Lane 1</option>
        <option value="2" <?= $laneFilter=='2'?'selected':'' ?>>Lane 2</option>
        <option value="3" <?= $laneFilter=='3'?'selected':'' ?>>Lane 3</option>
        <option value="4" <?= $laneFilter=='4'?'selected':'' ?>>Lane 4</option>
    </select>

    <button type="submit">Filter</button>
</form>

<table>
<tr>
    <th>ID</th>
    <th>Vehicle Number</th>
    <th>Type</th>
    <th>Lane</th>
    <th>Entry Time</th>
    <th>Added By</th>
    <th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>
    <td><?php echo $row['vehicle_id']; ?></td>
    <td><?php echo $row['vehicle_number']; ?></td>
    <td><?php echo $row['vehicle_type']; ?></td>
    <td><?php echo $row['lane_no']; ?></td>
    <td><?php echo $row['entry_time']; ?></td>
    <td><?php echo $row['username']; ?></td>

    <td>
    <?php if($_SESSION['role'] == 'admin') { ?>
        <a href="?delete=<?= $row['vehicle_id'] ?>" 
           onclick="return confirm('Delete vehicle and all related violations?')" 
           style="color:red;">
           Delete
        </a>
    <?php } else { ?>
        <span style="color:gray;">—</span>
    <?php } ?>
    </td>

</tr>

<?php } ?>

</table>
</div>

</body>
</html>