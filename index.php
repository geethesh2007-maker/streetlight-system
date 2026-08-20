<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Streetlight Fault Reporting System</title>

    <link rel="stylesheet" href="css/style.css">

    <!-- Font Awesome -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

<!-- ================= NAVBAR ================= -->

<nav class="navbar">

    <div class="logo">
        <i class="fa-solid fa-lightbulb"></i>
        StreetLight
    </div>

    <ul>

        <li><a href="index.php">Home</a></li>

        <li><a href="#about">About</a></li>

        <li><a href="#features">Features</a></li>

        <li><a href="auth/login.php">Login</a></li>

        <li><a href="auth/register.php" class="register-btn">Register</a></li>

    </ul>

</nav>


<!-- ================= HERO SECTION ================= -->

<section class="hero">

    <div class="hero-content">

        <h1>Streetlight Fault Reporting System</h1>

        <p>
            Report faulty streetlights using photographs and GPS location.
            Help build safer and smarter communities.
        </p>

        <div class="buttons">

            <a href="auth/register.php" class="btn1">
                Report Now
            </a>

            <a href="#about" class="btn2">
                Learn More
            </a>

        </div>

    </div>

</section>


<!-- ================= ABOUT ================= -->

<section id="about" class="about">

<h2>About</h2>

<p>

This system enables citizens to report faulty streetlights quickly by uploading
a photograph and sharing their GPS location.

The complaint is sent to the municipality for quick resolution.

</p>

</section>



<!-- ================= FEATURES ================= -->

<section id="features" class="features">

<h2>Features</h2>

<div class="feature-container">

<div class="card">

<i class="fa-solid fa-camera"></i>

<h3>Upload Photo</h3>

<p>Capture and upload the faulty streetlight image.</p>

</div>

<div class="card">

<i class="fa-solid fa-location-dot"></i>

<h3>GPS Location</h3>

<p>Automatically detect the complaint location.</p>

</div>

<div class="card">

<i class="fa-solid fa-list-check"></i>

<h3>Track Complaint</h3>

<p>Track complaint status until it is resolved.</p>

</div>

<div class="card">

<i class="fa-solid fa-bolt"></i>

<h3>Quick Resolution</h3>

<p>Fast reporting and faster maintenance.</p>

</div>

</div>

</section>



<!-- ================= HOW IT WORKS ================= -->

<section class="steps">

<h2>How It Works</h2>

<div class="step-box">

<div>1. Register</div>

<div>2. Login</div>

<div>3. Report Fault</div>

<div>4. Admin Reviews</div>

<div>5. Complaint Resolved</div>

</div>

</section>



<!-- ================= FOOTER ================= -->

<footer>

<p>

© 2026 Streetlight Fault Reporting System

</p>

</footer>


<script src="js/script.js"></script>

</body>

</html>