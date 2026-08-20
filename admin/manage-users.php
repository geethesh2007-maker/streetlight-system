
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

$complaints = $db->complaints;

if (isset($_POST['update'])) {

    $status = $_POST['status'];
    $remarks = trim($_POST['remarks']);

    $complaints->updateOne(

        ["_id" => $id],

        [
            '$set' => [
                "status" => $status,
                "remarks" => $remarks
            ]
        ]

    );

    echo "<script>

    alert('Complaint Updated Successfully');

    window.location='manage-complaints.php';

    </script>";

    exit();

}

$data = $complaints->findOne([
    "_id" => $id
]);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Update Complaint</title>

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

Welcome,

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

<i class="fa-solid fa-pen-to-square"></i>

Update Complaint

</h2>

<img

src="../uploads/complaint-images/<?php echo $data['image']; ?>"

class="detail-image">

<div class="detail-row">

<label>Citizen Name</label>

<p><?php echo $data['user_name']; ?></p>

</div>

<div class="detail-row">

<label>Email</label>

<p><?php echo $data['user_email']; ?></p>

</div>

<div class="detail-row">

<label>Problem</label>

<p><?php echo $data['problem']; ?></p>

</div>

<div class="detail-row">

<label>Address</label>

<p><?php echo $data['address']; ?></p>

</div>

<div class="detail-row">

<label>Latitude</label>

<p><?php echo $data['latitude']; ?></p>

</div>

<div class="detail-row">

<label>Longitude</label>

<p><?php echo $data['longitude']; ?></p>

</div>

<h3 style="color:white;margin-top:30px;">

<i class="fa-solid fa-map-location-dot"></i>

Complaint Location

</h3>

<div id="adminMap"></div>

<form method="POST">

<label>Status</label>

<select name="status" required>

<option value="Pending"
<?php if($data['status']=="Pending") echo "selected";?>>

Pending

</option>

<option value="In Progress"
<?php if($data['status']=="In Progress") echo "selected";?>>

In Progress

</option>

<option value="Resolved"
<?php if($data['status']=="Resolved") echo "selected";?>>

Resolved

</option>

</select>

<label>Admin Remarks</label>

<textarea

name="remarks"

placeholder="Enter remarks here..."><?php

echo isset($data['remarks']) ? $data['remarks'] : "";

?></textarea>

<button

type="submit"

name="update">

Update Complaint

</button>

</form>

</div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

var lat = <?php echo $data['latitude']; ?>;
var lng = <?php echo $data['longitude']; ?>;

var map = L.map('adminMap').setView([lat, lng], 17);

L.tileLayer(
'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
{
maxZoom:19,
attribution:'© OpenStreetMap'
}
).addTo(map);

L.marker([lat,lng])

.addTo(map)

.bindPopup("Complaint Location")

.openPopup();

</script>

</body> 
