<?php

include "../config/session.php";
include "../config/database.php";

if (!isset($_GET['id'])) {
    header("Location: my-complaints.php");
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
            Welcome <b><?php echo $_SESSION['name']; ?></b> |

            <a href="dashboard.php">Dashboard</a> |

            <a href="my-complaints.php">My Complaints</a> |

            <a href="../auth/logout.php">Logout</a>
        </div>

    </div>

    <div class="details-card">

        <h2>
            <i class="fa-solid fa-circle-info"></i>
            Complaint Details
        </h2>

        <!-- Complaint ID -->
        <div class="detail-row">
            <label>
                <i class="fa-solid fa-hashtag"></i>
                Complaint ID
            </label>

            <p>
                <?php echo $complaint['complaint_id']; ?>
            </p>
        </div>

        <img
        src="../uploads/complaint-images/<?php echo $complaint['image']; ?>"
        class="detail-image">

        <div class="detail-row">

            <label>
                <i class="fa-solid fa-circle-exclamation"></i>
                Problem
            </label>

            <p>
                <?php echo $complaint['problem']; ?>
            </p>

        </div>

        <div class="detail-row">

            <label>
                <i class="fa-solid fa-location-dot"></i>
                Address
            </label>

            <p>
                <?php echo $complaint['address']; ?>
            </p>

        </div>

        <div class="detail-row">

            <label>Latitude</label>

            <p>
                <?php echo $complaint['latitude']; ?>
            </p>

        </div>

        <div class="detail-row">

            <label>Longitude</label>

            <p>
                <?php echo $complaint['longitude']; ?>
            </p>

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

            <p>
                <?php echo $complaint['created_at']; ?>
            </p>

        </div>

        <div class="detail-row">

            <label>Admin Remarks</label>

            <p>

            <?php

            echo isset($complaint['remarks'])
                ? $complaint['remarks']
                : "No remarks yet.";

            ?>

            </p>

        </div>

        <h3 style="color:white;margin-top:35px;">

            <i class="fa-solid fa-map-location-dot"></i>

            Streetlight Location

        </h3>

        <div id="complaintMap"></div>

        <a
        href="my-complaints.php"
        class="back-btn">

            <i class="fa-solid fa-arrow-left"></i>

            Back

        </a>

    </div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

var latitude = <?php echo $complaint['latitude']; ?>;
var longitude = <?php echo $complaint['longitude']; ?>;

var map = L.map('complaintMap').setView([latitude, longitude], 17);

L.tileLayer(
'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
{
maxZoom:19,
attribution:'© OpenStreetMap'
}
).addTo(map);

L.marker([latitude, longitude])
.addTo(map)
.bindPopup("Streetlight Complaint")
.openPopup();

</script>

</body>
</html>