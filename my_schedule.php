<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["dentist_id"])) {
    header("Location: login.html");
    exit();
}

$dentist_id = $_SESSION["dentist_id"];

$sql = "SELECT 
            appointments.appointment_date,
            appointments.appointment_time,
            appointments.reason,
            appointments.status,
            patients.name AS patient_name,
            patients.phone AS patient_phone
        FROM appointments
        INNER JOIN patients
        ON appointments.patient_id = patients.patient_id
        WHERE appointments.dentist_id = ?
        AND appointments.appointment_date >= CURDATE()
        ORDER BY appointments.appointment_date ASC,
                 appointments.appointment_time ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dentist_id);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Schedule | Smile Care</title>

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
    padding:20px;
    text-align:center;
    font-size:28px;
    font-weight:bold;
}

.container{
    width:95%;
    margin:30px auto;
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 0 15px lightgray;
}

h2{
    color:#0097a7;
    margin-bottom:10px;
}

.subtitle{
    color:#666;
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th{
    background:#0097a7;
    color:white;
    padding:12px;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #ddd;
}

tr:hover{
    background:#f1fdff;
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

.btn{
    margin-top:25px;
    padding:12px 25px;
    background:#0097a7;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:16px;
    transition:.3s;
}

.btn:hover{
    background:#006064;
    transform:scale(1.05);
}

footer{
    margin-top:30px;
    background:#0097a7;
    color:white;
    text-align:center;
    padding:15px;
}

</style>

</head>

<body>

<header>
    Smile Care Dental Clinic
</header>

<div class="container">

<h2>⏰ My Schedule</h2>

<p class="subtitle">
    View your upcoming appointments
</p>

<table>

<tr>
    <th>Date</th>
    <th>Time</th>
    <th>Patient Name</th>
    <th>Contact</th>
    <th>Treatment</th>
    <th>Status</th>
</tr>

<?php

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $statusClass = strtolower($row["status"]);

        echo "<tr>";

        echo "<td>" .
             htmlspecialchars($row["appointment_date"]) .
             "</td>";

        echo "<td>" .
             date("h:i A", strtotime($row["appointment_time"])) .
             "</td>";

        echo "<td>" .
             htmlspecialchars($row["patient_name"]) .
             "</td>";

        echo "<td>" .
             htmlspecialchars($row["patient_phone"]) .
             "</td>";

        echo "<td>" .
             htmlspecialchars($row["reason"]) .
             "</td>";

        echo "<td class='" .
             htmlspecialchars($statusClass) .
             "'>" .
             htmlspecialchars($row["status"]) .
             "</td>";

        echo "</tr>";
    }

} else {

    echo "<tr>";

    echo "<td colspan='6'>No upcoming appointments.</td>";

    echo "</tr>";
}

?>

</table>

<button class="btn" onclick="goBack()">
    ⬅ Back to Dashboard
</button>

</div>

<footer>
    © 2026 Smile Care Dental Clinic. All Rights Reserved.
</footer>

<script>

function goBack(){

    window.location.href = "dentist_dashboard.php";

}

</script>

</body>

</html>

<?php

$stmt->close();
$conn->close();

?>