<?php
include("includes/db.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: pages/dashboard.php");
            exit();

        } else {
            $error = "Invalid Password";
        }

    } else {
        $error = "User Not Found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>TMS Login</title>
<style>
body {
    font-family: Arial;
    background: #2c3e50;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.login-box {
    background: white;
    padding: 30px;
    width: 300px;
}
input {
    width: 100%;
    padding: 8px;
    margin: 10px 0;
}
button {
    width: 100%;
    padding: 10px;
    background: #3498db;
    color: white;
    border: none;
}
.error { color: red; }
</style>
</head>
<body>

<div class="login-box">
<h2>Login</h2>

<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

<form method="POST">
<input type="text" name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit">Login</button>
</form>
</div>

</body>
</html>