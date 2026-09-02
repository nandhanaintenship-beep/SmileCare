<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["dentist_id"])) {
    header("Location: login.html");
    exit();
}

$dentist_id = $_SESSION["dentist_id"];

$sql = "SELECT * FROM dentists WHERE dentist_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dentist_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows != 1) {
    echo "Dentist not found.";
    exit();
}

$dentist = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dentist Dashboard | Smile Care</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;
}

body{
    background-image:url("dentistimage.jpg");
    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
    background-attachment:fixed;
}

/* Header */

header{
background:#0097a7;
color:white;
padding:20px;
text-align:center;
font-size:28px;
font-weight:bold;
}

/* Welcome */

.welcome{
text-align:center;
margin:30px;
animation:fade 1s;
}

.welcome h2{
color:#00838f;
margin-bottom:10px;
}

.welcome p{
color:#555;
}

/* Profile */

.profile{
width:90%;
margin:auto;
background:white;
padding:20px;
border-radius:15px;
box-shadow:0 0 10px lightgray;
margin-bottom:30px;

display:flex;
align-items:center;
gap:25px;
}

.profile-img{
width:120px;
height:120px;
border-radius:50%;
object-fit:cover;
border:4px solid #0097a7;
box-shadow:0 5px 15px rgba(0,0,0,0.2);
transition:0.4s;
}

.profile-img:hover{
transform:scale(1.08);
}

.profile-details h3{
color:#0097a7;
margin-bottom:15px;
}

.profile-details p{
margin:8px 0;
color:#555;
}

/* Dashboard Cards */

.container{
width:90%;
margin:auto;
display:grid;
grid-template-columns:repeat(3,1fr);
gap:20px;
margin-bottom:40px;
}

.card{
background:white;
padding:25px;
text-align:center;
border-radius:10px;
box-shadow:0 0 10px lightgray;
cursor:pointer;
transition:.3s;
}

.card:hover{
background:#0097a7;
color:white;
transform:translateY(-8px);
}

.card h2{
font-size:40px;
margin-bottom:10px;
}

.card h3{
margin-bottom:10px;
}

/* Footer */

footer{
background:#0097a7;
color:white;
text-align:center;
padding:15px;
margin-top:30px;
}

@keyframes fade{

from{
opacity:0;
transform:translateY(-30px);
}

to{
opacity:1;
transform:translateY(0);
}

}

</style>
</head>

<body>

<header>
Smile Care Dentist Dashboard
</header>

<!-- Welcome -->

<div class="welcome">

<h2 id="greeting">
Welcome, <?php echo htmlspecialchars($dentist["name"]); ?> 👨‍⚕️
</h2>

<p>
Manage appointments and patient treatment records.
</p>

</div>


<!-- Dentist Profile -->

<div class="profile">

<?php
if ($dentist["name"] == "Dr. Anjali Nair") {
    $doctorImage = "jpg 1.jpg";
} elseif ($dentist["name"] == "Dr. Rahul Menon") {
    $doctorImage = "jpg 2.jpg";
} elseif ($dentist["name"] == "Dr. Meera Joseph") {
    $doctorImage = "jpg 3.jpg";
} else {
    $doctorImage = "dentistimage.jpg";
}
?>

<img src="<?php echo $doctorImage; ?>" alt="Doctor" class="profile-img">
<div class="profile-details">

<h3>Dentist Profile</h3>

<p>
<b>Name:</b>
<?php echo htmlspecialchars($dentist["name"]); ?>
</p>

<p>
<b>Specialization:</b>
<?php echo htmlspecialchars($dentist["specialization"]); ?>
</p>

<p>
<b>Phone:</b>
<?php echo htmlspecialchars($dentist["phone"]); ?>
</p>

<p>
<b>Email:</b>
<?php echo htmlspecialchars($dentist["email"]); ?>
</p>

</div>

</div>


<!-- Dashboard Cards -->

<div class="container">

<div class="card" onclick="location.href='today_appointments.html'">

<h2>📅</h2>

<h3>Today's Appointments</h3>

<p>View today's bookings</p>

</div>


<div class="card" onclick="location.href='update_treatment.html'">

<h2>👨‍⚕️</h2>

<h3>Update Records</h3>

<p>Update patient's records</p>

</div>


<div class="card" onclick="location.href='treatment_records.html'">

<h2>🦷</h2>

<h3>Treatment Records</h3>

<p>View treatment details</p>

</div>


<div class="card" onclick="location.href='my_schedule.html'">

<h2>⏰</h2>

<h3>My Schedule</h3>

<p>Check today's schedule</p>

</div>


<div class="card" onclick="location.href='patient_list.html'">

<h2>👥</h2>

<h3>Patient List</h3>

<p>View patient details</p>

</div>


<div class="card" onclick="location.href='logout.php'">

<h2>🚪</h2>

<h3>Logout</h3>

<p>Exit dashboard</p>

</div>

</div>


<footer>

© 2026 Smile Care Dental Clinic

</footer>


<script>

let hour = new Date().getHours();

let dentistName = <?php echo json_encode($dentist["name"]); ?>;

if(hour < 12){

document.getElementById("greeting").innerHTML =
"Good Morning " + dentistName + " ☀️";

}

else if(hour < 18){

document.getElementById("greeting").innerHTML =
"Good Afternoon " + dentistName + " 🌤️";

}

else{

document.getElementById("greeting").innerHTML =
"Good Evening " + dentistName + " 🌙";

}

</script>

</body>

</html>