<?php

include "../config/session.php";
include "../config/database.php";

if($_SESSION['role']!="Admin"){
    header("Location: ../auth/login.php");
    exit();
}

$complaints = $db->complaints;

$total = $complaints->countDocuments();

$pending = $complaints->countDocuments([
    "status"=>"Pending"
]);

$progress = $complaints->countDocuments([
    "status"=>"In Progress"
]);

$resolved = $complaints->countDocuments([
    "status"=>"Resolved"
]);

$recent = $complaints->find(
    [],
    [
        "sort"=>["created_at"=>-1],
        "limit"=>5
    ]
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Administrator Dashboard</title>

<link rel="stylesheet" href="../css/style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

</head>

<body>

<div class="dashboard">

<div class="topbar">

<h2>

<i class="fa-solid fa-lightbulb"></i>

Streetlight Fault Reporting System

</h2>

<div>

Welcome,

<b><?php echo $_SESSION['name']; ?></b>

|

<a href="../auth/logout.php">

Logout

</a>

</div>

</div>

<h2>

<i class="fa-solid fa-user-shield"></i>

Administrator Dashboard

</h2>

<div class="dashboard-cards">

<div class="dash-card">

<i class="fa-solid fa-file-circle-check"></i>

<h3>Total Complaints</h3>

<h1><?php echo $total; ?></h1>

</div>

<div class="dash-card">

<i class="fa-solid fa-clock"></i>

<h3>Pending</h3>

<h1><?php echo $pending; ?></h1>

</div>

<div class="dash-card">

<i class="fa-solid fa-spinner"></i>

<h3>In Progress</h3>

<h1><?php echo $progress; ?></h1>

</div>

<div class="dash-card">

<i class="fa-solid fa-circle-check"></i>

<h3>Resolved</h3>

<h1><?php echo $resolved; ?></h1>

</div>

</div>

<div class="quick-actions">

<a href="manage-complaints.php">

<i class="fa-solid fa-list-check"></i>

Manage Complaints

</a>

</div>

<h2 style="margin-top:40px;">

Recent Complaints

</h2>

<table>

<tr>

<th>Citizen</th>

<th>Problem</th>

<th>Status</th>

<th>Date</th>

</tr>

<?php

foreach($recent as $row){

?>

<tr>

<td><?php echo $row['user_name']; ?></td>

<td><?php echo $row['problem']; ?></td>

<td>

<?php

$status=$row['status'];

if($status=="Pending"){

echo "<span class='pending'>Pending</span>";

}

elseif($status=="In Progress"){

echo "<span class='progress'>In Progress</span>";

}

else{

echo "<span class='resolved'>Resolved</span>";

}

?>

</td>

<td><?php echo $row['created_at']; ?></td>

</tr>

<?php

}

?>

</table>

</div>

<script src="../js/script.js"></script>

</body>

</html>