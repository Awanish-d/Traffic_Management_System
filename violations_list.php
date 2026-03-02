<?php
session_start();
date_default_timezone_set("Asia/Kolkata");

include("../includes/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$today = date("Y-m-d");

/* ===============================
   DELETE (Admin Only)
=================================*/
if(isset($_GET['delete']) && $_SESSION['role'] == 'admin') {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM violation WHERE violation_id=$id");
    header("Location: violations_list.php");
    exit();
}

/* ===============================
   MARK AS PAID (Admin Only)
=================================*/
if(isset($_GET['pay']) && $_SESSION['role'] == 'admin') {
    $id = $_GET['pay'];
    mysqli_query($conn,"
        UPDATE violation 
        SET payment_status='paid'
        WHERE violation_id=$id
    ");
    header("Location: violations_list.php");
    exit();
}

/* ===============================
   SEARCH & FILTER
=================================*/
$search = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? 'all';

$where = " WHERE 1=1 ";

if(!empty($search)){
    $where .= " AND (veh.vehicle_number LIKE '%$search%' 
                OR v.violation_type LIKE '%$search%') ";
}

if($statusFilter == 'paid'){
    $where .= " AND v.payment_status='paid' ";
}
elseif($statusFilter == 'pending'){
    $where .= " AND v.payment_status='pending' AND v.due_date >= CURDATE() ";
}
elseif($statusFilter == 'overdue'){
    $where .= " AND v.payment_status='pending' AND v.due_date < CURDATE() ";
}

$query = "
SELECT v.*, 
       veh.vehicle_number,
       u.username
FROM violation v
LEFT JOIN vehicle veh ON v.vehicle_id = veh.vehicle_id
LEFT JOIN users u ON v.recorded_by = u.user_id
$where
ORDER BY v.violation_id DESC
";

$result = mysqli_query($conn,$query);
?>

<div class="content">
<div class="topbar">
    <strong>All Violations</strong>
</div>

<!-- SEARCH BAR -->
<form method="GET" style="margin-bottom:20px;">
    <input type="text" name="search" placeholder="Search vehicle or type"
           value="<?= htmlspecialchars($search) ?>">
    
    <select name="status">
        <option value="all">All</option>
        <option value="paid" <?= $statusFilter=='paid'?'selected':'' ?>>Paid</option>
        <option value="pending" <?= $statusFilter=='pending'?'selected':'' ?>>Pending</option>
        <option value="overdue" <?= $statusFilter=='overdue'?'selected':'' ?>>Overdue</option>
    </select>

    <button type="submit">Filter</button>
</form>

<table>
<tr>
    <th>ID</th>
    <th>Vehicle</th>
    <th>Type</th>
    <th>Fine</th>
    <th>Date</th>
    <th>Status</th>
    <th>Recorded By</th>
    <th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)) { ?>

<tr>
    <td><?= $row['violation_id']; ?></td>
    <td><?= $row['vehicle_number']; ?></td>
    <td><?= $row['violation_type']; ?></td>
    <td>₹<?= $row['fine_amount']; ?></td>
    <td><?= $row['violation_date']; ?></td>

<?php
if($row['payment_status'] == 'paid') {
    $status_label = "<span style='color:green;font-weight:bold;'>🟢 Paid</span>";
}
elseif($row['payment_status'] == 'pending' && $today > $row['due_date']) {
    $status_label = "<span style='color:red;font-weight:bold;'>🔴 Overdue</span>";
}
else {
    $status_label = "<span style='color:orange;font-weight:bold;'>🟡 Pending</span>";
}
?>

<td><?= $status_label ?></td>
<td><?= $row['username']; ?></td>

<td>
<?php if($_SESSION['role'] == 'admin') { ?>

    <?php if($row['payment_status'] == 'pending' && $today <= $row['due_date']) { ?>
        <a href="?pay=<?= $row['violation_id'] ?>" 
           onclick="return confirm('Mark this fine as paid?')" 
           style="color:green; margin-right:10px;">
           Mark Paid
        </a>
    <?php } ?>

    <a href="?delete=<?= $row['violation_id'] ?>" 
       onclick="return confirm('Delete this violation?')" 
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