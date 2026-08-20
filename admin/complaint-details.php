<?php

include "../config/session.php";
include "../config/database.php";

if ($_SESSION['role'] != "Admin") {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage-complaints.php");
    exit();
}

$id = new MongoDB\BSON\ObjectId($_GET['id']);

$complaint = $db->complaints->findOne([
    "_id" => $id
]);

if (!$complaint) {
    die("Complaint not found.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Complaint Details</title>

<link rel="stylesheet" href="../css/style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<link rel="stylesheet"
href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

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

<a href="manage-complaints.php">Manage Complaints</a>

|

<a href="../auth/logout.php">Logout</a>

</div>

</div>

<div class="details-card">

<h2>

<i class="fa-solid fa-circle-info"></i>

Complaint Details

</h2>

<img src="../uploads/complaint-images/<?php echo $complaint['image']; ?>"

class="detail-image">

<div class="detail-row">

<label>Citizen Name</label>

<p><?php echo $complaint['user_name']; ?></p>

</div>
<div class="detail-row">

<label>Complaint ID</label>

<p>

<?php echo $complaint['complaint_id']; ?>

</p>

</div>
<div class="detail-row">

<label>Email</label>

<p><?php echo $complaint['user_email']; ?></p>

</div>

<div class="detail-row">

<label>Problem</label>

<p><?php echo $complaint['problem']; ?></p>

</div>

<div class="detail-row">

<label>Address</label>

<p><?php echo $complaint['address']; ?></p>

</div>

<div class="detail-row">

<label>Latitude</label>

<p><?php echo $complaint['latitude']; ?></p>

</div>

<div class="detail-row">

<label>Longitude</label>

<p><?php echo $complaint['longitude']; ?></p>

</div>

<div class="detail-row">

<label>Status</label>

<p>

<?php

$status = $complaint['status'];

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

</p>

</div>

<div class="detail-row">

<label>Reported On</label>

<p><?php echo $complaint['created_at']; ?></p>

</div>

<div class="detail-row">

<label>Admin Remarks</label>

<p>

<?php

echo isset($complaint['remarks'])

? $complaint['remarks']

: "No remarks added.";

?>

</p>

</div>

<h3 style="color:white;margin-top:30px;">

<i class="fa-solid fa-map-location-dot"></i>

Complaint Location

</h3>

<div id="adminComplaintMap"></div>

<br>

<a

href="update-status.php?id=<?php echo $complaint['_id']; ?>"

class="view-btn">

<i class="fa-solid fa-pen"></i>

Update Status

</a>

&nbsp;

<a

href="manage-complaints.php"

class="back-btn">

<i class="fa-solid fa-arrow-left"></i>

Back

</a>

</div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

var lat=<?php echo $complaint['latitude'];?>;
var lng=<?php echo $complaint['longitude'];?>;

var map=L.map('adminComplaintMap').setView([lat,lng],17);

L.tileLayer(

'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',

{

maxZoom:19,

attribution:'© OpenStreetMap'

}

).addTo(map);

L.marker([lat,lng])

.addTo(map)

.bindPopup("Streetlight Complaint")

.openPopup();

</script>

</body>

</html>