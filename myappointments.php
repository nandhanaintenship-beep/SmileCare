<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["patient_id"])) {
    header("Location: login.html");
    exit();
}

$patient_id = $_SESSION["patient_id"];

$sql = "SELECT
            appointments.appointment_date,
            appointments.appointment_time,
            appointments.reason,
            appointments.status,
            dentists.name AS dentist_name,
            dentists.specialization
        FROM appointments
        INNER JOIN dentists
        ON appointments.dentist_id = dentists.dentist_id
        WHERE appointments.patient_id = ?
        ORDER BY appointments.appointment_date ASC,
                 appointments.appointment_time ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Appointments | Smile Care</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;
}

body{
background:#eef8fc;
}

header{
background:#0097a7;
color:white;
padding:18px;
text-align:center;
font-size:30px;
font-weight:bold;
}

.heading{
text-align:center;
margin:30px;
animation:fade 1s;
}

.heading h2{
color:#00838f;
}

.heading p{
color:#555;
margin-top:8px;
}

.container{
width:90%;
margin:auto;
display:grid;
grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
gap:25px;
padding-bottom:40px;
}

.card{
background:white;
padding:25px;
border-radius:15px;
box-shadow:0 5px 15px rgba(0,0,0,.15);
transition:.4s;
}

.card:hover{
transform:translateY(-8px);
}

.card h3{
color:#0097a7;
margin-bottom:15px;
}

.card p{
margin:8px 0;
}

.confirmed{
color:green;
font-weight:bold;
}

.pending{
color:orange;
font-weight:bold;
}

.completed{
color:green;
font-weight:bold;
}

.cancelled{
color:red;
font-weight:bold;
}

.button{
text-align:center;
margin:30px;
}

.button button{
padding:12px 28px;
background:#0097a7;
color:white;
border:none;
border-radius:8px;
font-size:17px;
cursor:pointer;
transition:.3s;
}

.button button:hover{
background:#006064;
}

footer{
background:#0097a7;
color:white;
text-align:center;
padding:18px;
margin-top:30px;
}

@keyframes fade{

from{
opacity:0;
transform:translateY(-20px);
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

Smile Care Dental Clinic

</header>


<div class="heading">

<h2>📅 My Appointments</h2>

<p>Here are your appointments.</p>

</div>


<div class="container">

<?php

if ($result->num_rows > 0) {

    $count = 1;

    while ($row = $result->fetch_assoc()) {

        $statusClass = strtolower($row["status"]);

        echo "<div class='card'>";

        echo "<h3>Appointment " . $count . "</h3>";

        echo "<p><b>👨‍⚕️ Doctor :</b> " .
             htmlspecialchars($row["dentist_name"]) .
             "</p>";

        echo "<p><b>🦷 Treatment :</b> " .
             htmlspecialchars($row["reason"]) .
             "</p>";

        echo "<p><b>📅 Date :</b> " .
             date("d F Y", strtotime($row["appointment_date"])) .
             "</p>";

        echo "<p><b>⏰ Time :</b> " .
             date("h:i A", strtotime($row["appointment_time"])) .
             "</p>";

        echo "<p><b>Specialization :</b> " .
             htmlspecialchars($row["specialization"]) .
             "</p>";

        echo "<p><b>Status :</b> ";

        echo "<span class='" .
             htmlspecialchars($statusClass) .
             "'>" .
             htmlspecialchars($row["status"]) .
             "</span>";

        echo "</p>";

        echo "</div>";

        $count++;
    }

} else {

    echo "<div class='card'>";

    echo "<h3>No Appointments</h3>";

    echo "<p>You don't have any appointments yet.</p>";

    echo "</div>";
}

?>

</div>


<div class="button">

<button onclick="backDashboard()">

← Back to Dashboard

</button>

</div>


<footer>

© 2026 Smile Care Dental Clinic

</footer>


<script>

function backDashboard(){

window.location.href="patient_dashboard.php";

}

</script>

</body>

</html>

<?php

$stmt->close();
$conn->close();

?>