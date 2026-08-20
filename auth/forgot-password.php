<?php

include "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);

    $users = $db->users;

    $user = $users->findOne([
        "email" => $email
    ]);

    if ($user) {

        session_start();
        $_SESSION['reset_email'] = $email;

        header("Location: reset-password.php");
        exit();

    } else {

        $message = "Email not found.";

    }

}

?>

<!DOCTYPE html>
<html>
<head>

<title>Forgot Password</title>

<link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="form-container">

<div class="form-box">

<h2>Forgot Password</h2>

<p style="color:red;"><?php echo $message; ?></p>

<form method="POST">

<input
type="email"
name="email"
placeholder="Enter Registered Email"
required>

<button type="submit">

Next

</button>

</form>

<p>

<a href="login.php">

Back to Login

</a>

</p>

</div>

</div>

</body>

</html>

