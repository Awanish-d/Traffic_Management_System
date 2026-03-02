<?php
session_start();
include("../includes/db.php");

/* =========================
   DATE FILTER
=========================*/
$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';

$where = " WHERE 1=1 ";
if(!empty($from) && !empty($to)){
    $where .= " AND violation_date BETWEEN '$from' AND '$to' ";
}

/* =========================
   EXPORT CSV
=========================*/
if(isset($_GET['export']) && $_GET['export']=="csv"){

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=traffic_report.csv');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen("php://output", "w");

    fputcsv($output, ["Violation ID","Vehicle ID","Type","Fine","Date","Status"]);

    $exportQuery = mysqli_query($conn,"SELECT * FROM violation $where");

    while($row = mysqli_fetch_assoc($exportQuery)){
        fputcsv($output, [
            $row['violation_id'],
            $row['vehicle_id'],
            $row['violation_type'],
            $row['fine_amount'],
            $row['violation_date'],
            $row['payment_status']
        ]);
    }

    fclose($output);
    exit();
}

/* =========================
   EXPORT PDF
=========================*/
if(isset($_GET['export']) && $_GET['export']=="pdf"){

    require('../fpdf/fpdf.php');

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(190,10,"Traffic Management Report",0,1,'C');

    $pdf->SetFont('Arial','',10);

    $exportQuery = mysqli_query($conn,"SELECT * FROM violation $where");

    while($row = mysqli_fetch_assoc($exportQuery)){
        $pdf->Cell(190,8,
            "ID: ".$row['violation_id'].
            " | Type: ".$row['violation_type'].
            " | Fine: ".$row['fine_amount'].
            " | Date: ".$row['violation_date'].
            " | Status: ".$row['payment_status'],
            0,1);
    }

    $pdf->Output();
    exit();
}

include("../includes/header.php");
include("../includes/sidebar.php");

/* =========================
   NORMAL REPORT DATA
=========================*/
$totalVehicles = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM vehicle")
)['total'];

$totalViolations = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM violation $where")
)['total'];

$totalCollected = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT SUM(fine_amount) as total 
                        FROM violation 
                        $where AND payment_status='paid'")
)['total'];

if($totalCollected == NULL){
    $totalCollected = 0;
}

$laneQuery = mysqli_query($conn,"
    SELECT lane_no, COUNT(*) as total
    FROM vehicle
    GROUP BY lane_no
");

$typeQuery = mysqli_query($conn,"
    SELECT violation_type, COUNT(*) as total
    FROM violation
    $where
    GROUP BY violation_type
");
?>

<div class="content">

<div class="topbar">
    <strong>System Reports</strong>
</div>

<form method="GET" style="margin-bottom:20px;">

    From:
    <input type="date" name="from" value="<?= $from ?>">

    To:
    <input type="date" name="to" value="<?= $to ?>">

    <button type="submit">Filter</button>

    <select name="export" onchange="this.form.submit()" style="margin-left:10px;">
        <option value="">Export</option>
        <option value="csv">Export CSV</option>
        <option value="pdf">Export PDF</option>
    </select>

</form>

<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:20px;">

<div class="card">
    <h3>Total Vehicles</h3>
    <h1><?= $totalVehicles ?></h1>
</div>

<div class="card">
    <h3>Total Violations</h3>
    <h1><?= $totalViolations ?></h1>
</div>

<div class="card">
    <h3>Total Collected</h3>
    <h1 style="color:green;">₹<?= $totalCollected ?></h1>
</div>

</div>

<br><br>

<h3>Lane-wise Vehicle Count</h3>
<table>
<tr>
    <th>Lane Number</th>
    <th>Total Vehicles</th>
</tr>

<?php while($lane = mysqli_fetch_assoc($laneQuery)){ ?>
<tr>
    <td><?= $lane['lane_no'] ?></td>
    <td><?= $lane['total'] ?></td>
</tr>
<?php } ?>
</table>

<br><br>

<h3>Violation Type Summary</h3>
<table>
<tr>
    <th>Violation Type</th>
    <th>Total Cases</th>
</tr>

<?php while($type = mysqli_fetch_assoc($typeQuery)){ ?>
<tr>
    <td><?= $type['violation_type'] ?></td>
    <td><?= $type['total'] ?></td>
</tr>
<?php } ?>
</table>

</div>

</body>
</html>