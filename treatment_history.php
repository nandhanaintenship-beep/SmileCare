<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["patient_id"])) {
    header("Location: login.html");
    exit();
}

$patient_id = $_SESSION["patient_id"];

$sql = "SELECT
            treatments.treatment_name,
            treatments.treatment_date,
            treatments.description,
            treatments.cost,
            dentists.name AS dentist_name
        FROM treatments
        INNER JOIN dentists
        ON treatments.dentist_id = dentists.dentist_id
        WHERE treatments.patient_id = ?
        ORDER BY treatments.treatment_date DESC";

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

<title>Treatment History | Smile Care</title>

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

/* Header */

header{
background:linear-gradient(to right,#0097a7,#00bcd4);
color:white;
padding:18px;
text-align:center;
font-size:28px;
font-weight:bold;
}

/* Heading */

.heading{
text-align:center;
margin:30px;
}

.heading h2{
color:#00838f;
margin-bottom:10px;
}

.heading p{
color:#555;
}

/* Table */

.table-box{
width:90%;
margin:auto;
background:white;
padding:20px;
border-radius:15px;
box-shadow:0 5px 15px rgba(0,0,0,.15);
overflow-x:auto;
animation:fade 1s;
}

table{
width:100%;
border-collapse:collapse;
}

th{
background:#0097a7;
color:white;
padding:15px;
}

td{
padding:15px;
text-align:center;
border-bottom:1px solid #ddd;
}

tr:hover{
background:#f1fbfd;
}

/* Button */

.button{
text-align:center;
margin:30px;
}

button{
padding:12px 25px;
background:#0097a7;
color:white;
border:none;
border-radius:8px;
font-size:16px;
cursor:pointer;
transition:.3s;
}

button:hover{
background:#006064;
}

/* No treatment */

.no-treatment{
padding:30px;
text-align:center;
color:#777;
font-size:18px;
}

/* Footer */

footer{
margin-top:30px;
background:#0097a7;
color:white;
text-align:center;
padding:15px;
}

/* Animation */

@keyframes fade{

from{
opacity:0;
transform:translateY(30px);
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

🏥 Smile Care Dental Clinic

</header>

<div class="heading">

<h2>🦷 Treatment History</h2>

<p>View your previous dental treatments.</p>

</div>

<div class="table-box">

<table>

<tr>

<th>Treatment</th>

<th>Dentist</th>

<th>Date</th>

<th>Description</th>

<th>Cost</th>

</tr>

<?php

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        echo "<tr>";

        echo "<td>" .
             htmlspecialchars($row["treatment_name"]) .
             "</td>";

        echo "<td>" .
             htmlspecialchars($row["dentist_name"]) .
             "</td>";

        echo "<td>" .
             date("d F Y", strtotime($row["treatment_date"])) .
             "</td>";

        echo "<td>" .
             htmlspecialchars($row["description"] ?? "Not provided") .
             "</td>";

        echo "<td>₹" .
             htmlspecialchars($row["cost"]) .
             "</td>";

        echo "</tr>";
    }

} else {

    echo "<tr>";

    echo "<td colspan='5' class='no-treatment'>";

    echo "No treatment records found.";

    echo "</td>";

    echo "</tr>";
}

?>

</table>

</div>

<div class="button">

<button onclick="goBack()">

← Back to Dashboard

</button>

</div>

<footer>

© 2026 Smile Care Dental Clinic

</footer>

<script>

function goBack(){

window.location.href="patient_dashboard.php";

}

</script>

</body>

</html>

<?php

$stmt->close();
$conn->close();

?>