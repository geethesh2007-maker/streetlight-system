<?php

include "../config/session.php";
include "../config/database.php";

$complaints = $db->complaints;

$search = "";
$statusFilter = "";

$filter = [
    "user_email" => $_SESSION['email']
];

if(isset($_GET['search']) && trim($_GET['search']) != ""){

    $search = trim($_GET['search']);

    $filter['complaint_id'] = [
        '$regex' => $search,
        '$options' => 'i'
    ];

}
if(isset($_GET['status']) && $_GET['status']!=""){
    $statusFilter = $_GET['status'];

    $filter['status'] = $statusFilter;
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

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Complaints</title>

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

<a href="dashboard.php">Dashboard</a>

|

<a href="../auth/logout.php">Logout</a>

</div>

</div>

<h2>

<i class="fa-solid fa-list-check"></i>

My Complaints

</h2>

<div class="search-box">

<form method="GET">

<input
type="text"
name="search"
placeholder="Search by Complaint ID (e.g. SLR20260731025251)"
value="<?php echo htmlspecialchars($search); ?>">

<select name="status">

<option value="">All Status</option>

<option value="Pending"
<?php if($statusFilter=="Pending") echo "selected"; ?>>
Pending
</option>

<option value="In Progress"
<?php if($statusFilter=="In Progress") echo "selected"; ?>>
In Progress
</option>

<option value="Resolved"
<?php if($statusFilter=="Resolved") echo "selected"; ?>>
Resolved
</option>

</select>

<button type="submit">

<i class="fa-solid fa-search"></i>

Search

</button>

</form>

</div>

<div class="complaint-table">

<table>

<tr>

<th>Image</th>

<th>Problem</th>
<th>Complaint ID</th>

<th>Location</th>

<th>Status</th>

<th>Date</th>

<th>Action</th>

</tr>

<?php

$count = 0;

foreach($data as $row){

$count++;

?>

<tr>

<td>
<img
src="../uploads/complaint-images/<?php echo $row['image']; ?>"
width="90"
height="70"
style="object-fit:cover;border-radius:8px;">
</td>

<td>
<?php echo htmlspecialchars($row['problem']); ?>
</td>

<td>
<?php echo isset($row['complaint_id']) ? htmlspecialchars($row['complaint_id']) : "N/A"; ?>
</td>

<td>
<?php echo isset($row['address']) ? htmlspecialchars($row['address']) : "N/A"; ?>
</td>

<td>

<?php

if($row['status']=="Pending"){

echo "<span class='pending'>Pending</span>";

}

elseif($row['status']=="In Progress"){

echo "<span class='progress'>In Progress</span>";

}

else{

echo "<span class='resolved'>Resolved</span>";

}

?>

</td>

<td>
<?php echo $row['created_at']; ?>
</td>

<td>

<a class="view-btn"
href="complaint-details.php?id=<?php echo (string)$row['_id']; ?>">

View

</a>

</td>

</tr>
<?php

}

if($count==0){

?>

<tr>

<td colspan="6">

No complaints found.

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

