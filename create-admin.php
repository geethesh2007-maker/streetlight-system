<?php
require_once "config/database.php";

$users = $db->users;

// Update role and reset password for the admin account
$result = $users->updateOne(
    ["email" => "admin@streetlight.com"],
    [
        '$set' => [
            "role" => "Admin",
            "password" => password_hash("admin123", PASSWORD_DEFAULT),
            "name" => "Administrator"
        ]
    ]
);

if ($result->getModifiedCount() > 0 || $result->getMatchedCount() > 0) {
    echo "<h2 style='color:green;'>Role updated to 'Admin' successfully!</h2>";
    echo "<p>Email: admin@streetlight.com</p>";
    echo "<p>Password: admin123</p>";
    echo "<a href='auth/login.php'>Go to Login</a>";
} else {
    echo "<h2 style='color:red;'>No matching user found with email admin@streetlight.com</h2>";
}
?>





