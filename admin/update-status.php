</html></html><?php

include "../config/session.php";
include "../config/database.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
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

if (!$data) {
    die("Complaint not found.");
}

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

<div class="report-box">

<h2>

<i class="fa-solid fa-pen-to-square"></i>

Update Complaint

</h2>

<div class="image-box">
    <img src="../uploads/complaint-images/<?php echo $data['image']; ?>" class="detail-image">
</div>

<label>Citizen</label>

<input
type="text"
value="<?php echo $data['user_name']; ?>"
readonly>

<label>Email</label>

<input
type="text"
value="<?php echo $data['user_email']; ?>"
readonly>

<label>Problem</label>

<textarea readonly><?php echo $data['problem']; ?></textarea>

<label>Address</label>

<input
type="text"
value="<?php echo isset($data['address']) ? $data['address'] : ''; ?>"
readonly>

<label>Latitude</label>

<input
type="text"
value="<?php echo isset($data['latitude']) ? $data['latitude'] : ''; ?>"
readonly>

<label>Longitude</label>

<input
type="text"
value="<?php echo isset($data['longitude']) ? $data['longitude'] : ''; ?>"
readonly>

<h3 style="margin-top:25px;color:white;">
Complaint Location
</h3>

<div id="adminMap"></div>

<form method="POST">

<label>Status</label>

<select name="status" required>

<option value="Pending"
<?php if($data['status']=="Pending") echo "selected"; ?>>

Pending

</option>

<option value="In Progress"
<?php if($data['status']=="In Progress") echo "selected"; ?>>

In Progress

</option>

<option value="Resolved"
<?php if($data['status']=="Resolved") echo "selected"; ?>>

Resolved

</option>

</select>

<label>Admin Remarks</label>

<textarea
name="remarks"
placeholder="Enter remarks..."><?php
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

var lat = parseFloat("<?php echo $data['latitude'] ?? ''; ?>");
var lng = parseFloat("<?php echo $data['longitude'] ?? ''; ?>");

if (!isNaN(lat) && !isNaN(lng)) {

    var map = L.map('adminMap').setView([lat, lng], 17);

    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            attribution: '© OpenStreetMap',
            maxZoom: 19
        }
    ).addTo(map);

    L.marker([lat, lng])
        .addTo(map)
        .bindPopup("Complaint Location")
        .openPopup();

} else {

    document.getElementById("adminMap").innerHTML =
    "<h3 style='color:white;text-align:center;padding-top:180px;'>Location not available</h3>";

}

</script>

</body>

</html>