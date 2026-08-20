<?php

include "../config/session.php";
include "../config/database.php";

$message = "";

if(isset($_POST['submit'])){

    $problem = $_POST['problem'];
    $address = $_POST['address'];

    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $complaintId = "SLR" . date("YmdHis");
    $status = "Pending";

    $image = "";

    if ($_FILES['image']['name'] != "") {

        $uploadDir = __DIR__ . "/../uploads/complaint-images/";

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $image = time() . "_" . basename($_FILES['image']['name']);

        if (!move_uploaded_file(
            $_FILES['image']['tmp_name'],
            $uploadDir . $image
        )) {
            die("Image upload failed.");
        }
    }

    $db->complaints->insertOne([
        "complaint_id" => $complaintId,
        "user_name" => $_SESSION['name'],
        "user_email" => $_SESSION['email'],
        "problem" => $problem,
        "address" => $address,
        "latitude" => $latitude,
        "longitude" => $longitude,
        "image" => $image,
        "status" => $status,
        "created_at" => date("Y-m-d H:i:s")
    ]);

    $message = "ದೂರು ಯಶಸ್ವಿಯಾಗಿ ಸಲ್ಲಿಕೆಯಾಗಿದೆ (Complaint Submitted Successfully).";

}

?>

<!DOCTYPE html>
<html lang="kn">

<head>
    <meta charset="UTF-8">
    <title>Report Fault</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        /* Ensure map has a height so it renders correctly */
        #map { height: 320px; margin-bottom: 15px; border-radius: 6px; }
        .leaflet-popup-content { font-family: sans-serif; font-size: 13px; font-weight: 500; }
    </style>
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
            <a href="../citizen/dashboard.php">Dashboard</a>
            |
            <a href="../auth/logout.php">Logout</a>
        </div>
    </div>

    <div class="report-box">
        <h2>
            <i class="fa-solid fa-circle-exclamation"></i>
            ಬೀದಿ ದೀಪದ ದೂರು ದಾಖಲಿಸಿ (Report Streetlight Fault)
        </h2>

        <?php
        if($message != ""){
            echo "<div class='success'>$message</div>";
        }
        ?>

        <form method="POST" enctype="multipart/form-data">
            <label>ಸಮಸ್ಯೆಯ ವಿವರ (Problem Description)</label>
            <textarea name="problem" required placeholder="ಸಮಸ್ಯೆಯನ್ನು ಇಲ್ಲಿ ವಿವರಿಸಿ..."></textarea>

            <label>ಸ್ಥಳ / ವಿಳಾಸ (Location Address)</label>
            <input type="text" id="address" name="address" placeholder="ಸ್ಥಳವನ್ನು ಲೋಡ್ ಮಾಡಲಾಗುತ್ತಿದೆ..." readonly required>

            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">

            <div id="map"></div>

            <label>ಬೀದಿ ದೀಪದ ಫೋಟೋ ಅಪ್ಲೋಡ್ ಮಾಡಿ (Upload Image)</label>
            <input type="file" name="image" accept="image/*" required>

            <button type="submit" name="submit">
                ದೂರು ಸಲ್ಲಿಸಿ (Submit Complaint)
            </button>
        </form>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Live Location Integration Script -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Default fallback coordinates (Bengaluru, Karnataka)
    let defaultLat = 12.9716;
    let defaultLng = 77.5946;

    // Initialize Leaflet map
    let map = L.map('map').setView([defaultLat, defaultLng], 14);

    // 1. Esri Satellite Imagery Base Layer
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19,
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
    }).addTo(map);

    // 2. Reference Labels Overlay (Roads, Place Names & Boundaries)
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19
    }).addTo(map);

    let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

    // Function to update inputs and fetch address in Kannada (kn)
    function updateLocationDetails(lat, lng) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        document.getElementById('address').value = "ಸ್ಥಳವನ್ನು ಹುಡುಕಲಾಗುತ್ತಿದೆ...";

        // Reverse geocoding requested in Kannada (kn)
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=kn`)
            .then(response => response.json())
            .then(data => {
                let locationName = "";
                if (data && data.display_name) {
                    locationName = data.display_name;
                } else {
                    locationName = `${lat}, ${lng}`;
                }
                
                // Update input bar above the map
                document.getElementById('address').value = locationName;

                // Show location tooltip directly on the map marker
                marker.bindPopup(`<b>ಆಯ್ಕೆಮಾಡಿದ ಸ್ಥಳ:</b><br>${locationName}`).openPopup();
            })
            .catch(() => {
                let fallbackCoord = `${lat}, ${lng}`;
                document.getElementById('address').value = fallbackCoord;
                marker.bindPopup(`<b>ಸ್ಥಳ:</b><br>${fallbackCoord}`).openPopup();
            });
    }

    // Attempt to get user's live GPS location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                let userLat = position.coords.latitude;
                let userLng = position.coords.longitude;

                map.setView([userLat, userLng], 16);
                marker.setLatLng([userLat, userLng]);
                updateLocationDetails(userLat, userLng);
            },
            function (error) {
                console.warn("Geolocation access denied or unavailable. Using default location.");
                updateLocationDetails(defaultLat, defaultLng);
            },
            { timeout: 10000, enableHighAccuracy: true }
        );
    } else {
        updateLocationDetails(defaultLat, defaultLng);
    }

    // Allow user to drag marker to fine-tune location
    marker.on('dragend', function (event) {
        let position = marker.getLatLng();
        updateLocationDetails(position.lat, position.lng);
    });

    // Allow user to click on the map to change location
    map.on('click', function (event) {
        let lat = event.latlng.lat;
        let lng = event.latlng.lng;
        marker.setLatLng([lat, lng]);
        updateLocationDetails(lat, lng);
    });
});
</script>

</body>
</html>