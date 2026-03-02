<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Traffic Management System</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: #f1f5f9;
    opacity: 0;
    animation: fadeIn 0.4s ease-in forwards;
}

@keyframes fadeIn {
    to { opacity: 1; }
}

/* SIDEBAR */
.sidebar {
    width: 240px;
    height: 100vh;
    background: linear-gradient(180deg, #0f172a, #1e293b);
    position: fixed;
    padding-top: 25px;
}

.sidebar h2 {
    color: #fff;
    text-align: center;
    margin-bottom: 40px;
}

.sidebar a {
    display: block;
    color: #cbd5e1;
    padding: 14px 25px;
    text-decoration: none;
    transition: 0.3s;
}

.sidebar a:hover,
.sidebar a.active {
    background: rgba(255,255,255,0.1);
    color: #fff;
}

/* CONTENT */
.content {
    margin-left: 240px;
    padding: 40px;
}

/* TOPBAR */
.topbar {
    background: white;
    padding: 18px 25px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
}

/* CARD */
.card {
    background: white;
    padding: 25px;
    border-radius: 14px;
    box-shadow: 0 8px 18px rgba(0,0,0,0.06);
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-4px);
}

/* TABLE */
table {
    border-collapse: collapse;
    width: 100%;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 6px 16px rgba(0,0,0,0.06);
}

table th {
    background: #0f172a;
    color: white;
}

table th, table td {
    padding: 12px;
    text-align: center;
}
</style>

<script>
function confirmLogout() {
    return confirm("Are you sure you want to logout?");
}
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>