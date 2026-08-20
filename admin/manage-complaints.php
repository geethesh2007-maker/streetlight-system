<?php

include "../config/session.php";
include "../config/database.php";

if($_SESSION['role']!="Admin"){
    header("Location:../auth/login.php");
    exit();
}

$complaints = $db->complaints;

$search = "";

$filter = [];

if(isset($_GET['search']) && trim($_GET['search']) != ""){

    $search = trim($_GET['search']);

    $filter['complaint_id'] = [
        '$regex' => $search,
        '$options' => 'i'
    ];

}

$data = $complaints->find(

    $filter,

    [
        "sort"=>[
            "created_at"=>-1
        ]
    ]

);
?>

<!DOCTYPE html>

<html>

<head>

<title>Manage Complaints</title>

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

Welcome

<b><?php echo $_SESSION['name']; ?></b>

|

<a href="dashboard.php">Dashboard</a>

|

<a href="../auth/logout.php">

Logout

</a>

</div>

</div>

<h2>

<i class="fa-solid fa-list-check"></i>

Manage Complaints
<div class="search-box">

<form method="GET">

<input

type="text"

name="search"

placeholder="Search by Complaint ID..."

value="<?php echo htmlspecialchars($search); ?>">

<button type="submit">

<i class="fa-solid fa-search"></i>

Search

</button>

</form>

</div>
</h2>

<div class="complaint-table">

<table>

<tr>

<th>Image</th>

<th>Citizen</th>

<th>Complaint ID</th>

<th>Problem</th>

<th>Location</th>

<th>Status</th>

<th>Date</th>

<th>Action</th>

</tr>

<?php

foreach($data as $row){

?>

<tr>

<td>

<img

src="../uploads/complaint-images/<?php echo $row['image'];?>"

width="90"

height="70"

style="object-fit:cover;border-radius:8px;">

</td>

<td>

<?php echo $row['user_name']; ?>

</td>

<!-- Complaint ID -->

<td>

<?php

echo isset($row['complaint_id'])

? $row['complaint_id']

: "N/A";

?>

</td>

<!-- Problem -->

<td>

<?php echo $row['problem']; ?>

</td>

<!-- Location -->

<td>

<?php

if(isset($row['address'])){

echo $row['address'];

}
elseif(isset($row['location'])){

echo $row['location'];

}
else{

echo "Location Not Available";

}

?>

</td>

<!-- Status -->

<td>

<?php

$status = $row['status'];

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

<!-- Date -->

<td>

<?php echo $row['created_at']; ?>

</td>

<!-- Action -->

<td>

<a

class="view-btn"

href="complaint-details.php?id=<?php echo $row['_id']; ?>">

View

</a>

<br><br>

<a

class="view-btn"

style="background:#16a34a;"

href="update-status.php?id=<?php echo $row['_id']; ?>">

Update

</a>

</td>

</tr>

<?php

}

?>

</table>

</div>

</div>

<script src="../js/script.js"></script>

</body>

</html>