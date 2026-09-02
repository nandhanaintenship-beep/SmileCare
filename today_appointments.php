<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["dentist_id"])) {
    header("Location: login.html");
    exit();
}

$dentist_id = $_SESSION["dentist_id"];

/* Get today's appointments for logged-in dentist */

$sql = "SELECT 
            appointments.appointment_time,
            appointments.reason,
            appointments.status,
            patients.name,
            patients.phone
        FROM appointments
        INNER JOIN patients 
        ON appointments.patient_id = patients.patient_id
        WHERE appointments.dentist_id = ?
        AND appointments.appointment_date = CURDATE()
        ORDER BY appointments.appointment_time ASC";

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

<title>Today's Appointments | Smile Care</title>

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
background:#0097a7;
color:white;
padding:20px;
text-align:center;
font-size:28px;
font-weight:bold;
animation:fade 1s;
}

/* Main Container */

.container{
width:90%;
margin:30px auto;
background:white;
padding:25px;
border-radius:15px;
box-shadow:0 0 15px lightgray;
animation:fade 1.2s;
}

/* Title */

.container h2{
color:#0097a7;
margin-bottom:10px;
}

.date{
color:#666;
margin-bottom:20px;
}

/* Search */

.search-box{
margin-bottom:20px;
}

.search-box input{
width:100%;
padding:12px;
border:1px solid #ccc;
border-radius:8px;
font-size:16px;
}

/* Table */

table{
width:100%;
border-collapse:collapse;
margin-top:20px;
}

table th{
background:#0097a7;
color:white;
padding:12px;
}

table td{
padding:12px;
text-align:center;
border-bottom:1px solid #ddd;
}

tr:hover{
background:#f1fdff;
}

/* Status */

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

/* Buttons */

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

footer{
margin-top:30px;
background:#0097a7;
color:white;
text-align:center;
padding:15px;
font-size:16px;
}

</style>

</head>

<body>

<header>

Smile Care Dental Clinic

</header>


<div class="container">

<h2>📅 Today's Appointments</h2>

<p class="date">
<?php
echo date("l, F j, Y");
?>
</p>


<div class="search-box">

<input 
type="text" 
id="search" 
placeholder="Search patient name..."
>

</div>


<table id="appointmentTable">

<tr>

<th>Time</th>
<th>Patient Name</th>
<th>Treatment</th>
<th>Contact</th>
<th>Status</th>

</tr>


<?php

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $statusClass = strtolower($row["status"]);

        echo "<tr>";

        echo "<td>" . date("h:i A", strtotime($row["appointment_time"])) . "</td>";

        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";

        echo "<td>" . htmlspecialchars($row["reason"]) . "</td>";

        echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";

        echo "<td class='" . htmlspecialchars($statusClass) . "'>"
             . htmlspecialchars($row["status"]) .
             "</td>";

        echo "</tr>";
    }

} else {

    echo "<tr>";

    echo "<td colspan='5'>No appointments scheduled for today.</td>";

    echo "</tr>";
}

?>

</table>


<br>

<button class="btn" onclick="goBack()">
⬅ Back to Dashboard
</button>

</div>


<footer>

<p>© 2026 Smile Care Dental Clinic. All Rights Reserved.</p>

</footer>


<script>

/* Search Function */

document.getElementById("search").addEventListener("keyup", function(){

let filter = this.value.toUpperCase();

let table = document.getElementById("appointmentTable");

let tr = table.getElementsByTagName("tr");

for(let i = 1; i < tr.length; i++){

let td = tr[i].getElementsByTagName("td")[1];

if(td){

let txtValue = td.textContent || td.innerText;

if(txtValue.toUpperCase().indexOf(filter) > -1){

tr[i].style.display="";

}
else{

tr[i].style.display="none";

}

}

}

});


/* Back Button */

function goBack(){

window.location.href="dentist_dashboard.php";

}

</script>

</body>

</html>

<?php

$stmt->close();
$conn->close();

?>