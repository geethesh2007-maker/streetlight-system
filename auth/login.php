<?php

session_start();

include "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST['password'];

    $users = $db->users;

    $user = $users->findOne([
        "email" => $email
    ]);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = (string)$user['_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == "Admin") {

            header("Location: ../admin/dashboard.php");

        } else {

            header("Location: ../citizen/dashboard.php");

        }

        exit();

    } else {

        $message = "Invalid Email or Password.";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login</title>

<link rel="stylesheet" href="../css/style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>

<div class="form-container">

<div class="form-box">

<h2>Login</h2>

<p style="color:red;"><?php echo $message; ?></p>

<form method="POST">

<input
type="email"
name="email"
placeholder="Enter Email"
required>

<input
type="password"
name="password"
placeholder="Enter Password"
required>

<button type="submit">

Login

</button>
<p style="text-align:right;">
    <a href="forgot-password.php">
        Forgot Password?
    </a>
</p>

</form>

<p>

Don't have an account?

<a href="register.php">

Register

</a>

</p>

</div>

</div>

<script src="../js/script.js"></script>

</body>

</html>
