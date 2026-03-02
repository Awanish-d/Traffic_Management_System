<?php
session_start();
date_default_timezone_set("Asia/Kolkata");

include("../includes/db.php");
include("../includes/header.php");
include("../includes/sidebar.php");

if(!isset($_SESSION['role'])){
    header("Location: ../login.php");
    exit();
}

$role = $_SESSION['role'];
$today = date("Y-m-d");

/* ===============================
   COMMON DATA
=================================*/

$totalVehicles = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM vehicle")
)['total'];

$totalViolations = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM violation")
)['total'];

$todaysViolations = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM violation WHERE violation_date='$today'")
)['total'];

/* ===============================
   ADMIN DATA (7 Day Rule)
=================================*/

if($role == "admin"){

    $paidViolations = mysqli_fetch_assoc(
        mysqli_query($conn,"
            SELECT COUNT(*) as total 
            FROM violation 
            WHERE payment_status='paid'
        ")
    )['total'];

    $overdueCount = mysqli_fetch_assoc(
        mysqli_query($conn,"
            SELECT COUNT(*) as total 
            FROM violation 
            WHERE payment_status='pending' 
            AND due_date < CURDATE()
        ")
    )['total'];

    $pendingViolations = mysqli_fetch_assoc(
        mysqli_query($conn,"
            SELECT COUNT(*) as total 
            FROM violation 
            WHERE payment_status='pending' 
            AND due_date >= CURDATE()
        ")
    )['total'];

    $totalCollected = mysqli_fetch_assoc(
        mysqli_query($conn,"
            SELECT SUM(fine_amount) as total 
            FROM violation 
            WHERE payment_status='paid'
        ")
    )['total'];

    if($totalCollected == NULL){
        $totalCollected = 0;
    }
}
?>

<div class="content">

<div class="topbar">
    <strong>Dashboard Overview (<?php echo ucfirst($role); ?>)</strong>
</div>

<div style="display:grid; grid-template-columns: repeat(3,1fr); gap:20px;">

<div class="card">
    <h3>Total Vehicles</h3>
    <h1><?php echo $totalVehicles; ?></h1>
</div>

<div class="card">
    <h3>Total Violations</h3>
    <h1><?php echo $totalViolations; ?></h1>
</div>

<?php if($role == "admin"){ ?>
<div class="card">
    <h3>Pending Fines</h3>
    <h1 style="color:orange;"><?php echo $pendingViolations; ?></h1>
</div>
<?php } ?>

<div class="card">
    <h3>Today's Violations</h3>
    <h1><?php echo $todaysViolations; ?></h1>
</div>

<?php if($role == "admin"){ ?>

<div class="card">
    <h3>Overdue Fines</h3>
    <h1 style="color:red;"><?php echo $overdueCount; ?></h1>
</div>

<div class="card">
    <h3>Paid Fines</h3>
    <h1 style="color:green;"><?php echo $paidViolations; ?></h1>
</div>

<div class="card">
    <h3>Total Collected</h3>
    <h1 style="color:green;">₹<?php echo $totalCollected; ?></h1>
</div>

<?php } ?>

</div>


<?php if($role == "admin"){ ?>

<div class="card" style="margin-top:30px;">
    <h3>Fine Status Overview</h3>
    <canvas id="fineChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('fineChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Paid', 'Pending', 'Overdue'],
        datasets: [{
            label: 'Fine Count',
            data: [
                <?php echo $paidViolations; ?>,
                <?php echo $pendingViolations; ?>,
                <?php echo $overdueCount; ?>
            ],
            backgroundColor: [
                '#16a34a',
                '#f59e0b',
                '#dc2626'
            ],
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                precision: 0
            }
        }
    }
});
</script>

<?php } ?>

</div>

</body>
</html>