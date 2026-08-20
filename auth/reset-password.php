<?php

session_start();

include "../config/database.php";

if (!isset($_SESSION['reset_email'])) {

    header("Location: forgot-password.php");
    exit();

}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password != $confirm) {

        $message = "Passwords do not match.";

    } else {

        $users = $db->users;

        $users->updateOne(

            [
                "email" => $_SESSION['reset_email']
            ],

            [
                '$set' => [
                    "password" => password_hash($password, PASSWORD_DEFAULT)
                ]
            ]

        );

        unset($_SESSION['reset_email']);

        echo "<script>

        alert('Password Updated Successfully');

        window.location='login.php';

        </script>";

    }

}

?>

<!DOCTYPE html>
<html>
<head>

<title>Reset Password</title>

<link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="form-container">

<div class="form-box">

<h2>Reset Password</h2>

<p style="color:red;"><?php echo $message; ?></p>

<form method="POST">

<input
type="password"
name="password"
placeholder="New Password"
required>

<input
type="password"
name="confirm"
placeholder="Confirm Password"
required>

<button type="submit">

Reset Password

</button>

</form>

</div>

</div>

</body>

</html>



