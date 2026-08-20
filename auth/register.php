<?php

include "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password != $confirm) {

        $message = "Passwords do not match!";

    } else {

        $users = $db->users;

        $existingUser = $users->findOne([
            "email" => $email
        ]);

        if ($existingUser) {

            $message = "Email already registered!";

        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $users->insertOne([

                "name" => $name,
                "email" => $email,
                "phone" => $phone,
                "address" => $address,
                "password" => $hashedPassword,
                "role" => "Citizen",
                "created_at" => date("Y-m-d H:i:s")

            ]);

            echo "<script>
                    alert('Registration Successful');
                    window.location='login.php';
                  </script>";

            exit();
        }
    }
}
?>
<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Register</title>

<link rel="stylesheet"
href="../css/style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>

<div class="form-container">

<div class="form-box">

<h2>Create Account</h2>

<p style="color:red;"><?php echo $message; ?></p>

<form method="POST">

<input
type="text"
name="name"
placeholder="Full Name"
required>

<input
type="email"
name="email"
placeholder="Email Address"
required>

<input
type="text"
name="phone"
placeholder="Mobile Number"
required>

<textarea
name="address"
placeholder="Address"
required></textarea>

<input
type="password"
name="password"
placeholder="Password"
required>

<input
type="password"
name="confirm"
placeholder="Confirm Password"
required>

<button type="submit">

Register

</button>

</form>

<p>

Already have an account?

<a href="login.php">

Login

</a>

</p>

</div>

</div>

<script src="../js/script.js"></script>

</body>

</html>