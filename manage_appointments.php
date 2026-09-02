<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["dentist_id"])) {
    header("Location: login.html");
    exit();
}

$dentist_id = $_SESSION["dentist_id"];
$message = "";

/* Update appointment status */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $appointment_id = $_POST["appointment_id"];
    $status = $_POST["status"];

    $sql = "UPDATE appointments
            SET status = ?
            WHERE appointment_id = ?
            AND dentist_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $status, $appointment_id, $dentist_id);

    if ($stmt->execute()) {
        $message = "Appointment status updated successfully!";
    }

    $stmt->close();
}

/* Get appointments */
$sql = "SELECT
            appointments.appointment_id,
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

<title>Manage Appointments | Smile Care</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    background:#eef8fc;
    min-height:100vh;
}

header{
    background:linear-gradient(to right,#0097a7,#00bcd4);
    color:white;
    padding:20px;
    text-align:center;
    font-size:28px;
    font-weight:bold;
}

.container{
    width:92%;
    max-width:1200px;
    margin:35px auto;
}

.heading{
    text-align:center;
    margin-bottom:25px;
}

.heading h2{
    color:#00838f;
    margin-bottom:8px;
}

.heading p{
    color:#555;
}

.message{
    background:#e0f7fa;
    color:#00796b;
    padding:12px;
    border-radius:8px;
    text-align:center;
    margin-bottom:20px;
    font-weight:bold;
}

.table-box{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,.15);
    overflow-x:auto;
    animation:fade 0.8s;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#0097a7;
    color:white;
    padding:14px;
}

td{
    padding:13px;
    text-align:center;
    border-bottom:1px solid #ddd;
}

tr:hover{
    background:#f1fbfd;
}

select{
    padding:8px;
    border:1px solid #ccc;
    border-radius:6px;
}

.update-btn{
    margin-top:6px;
    padding:8px 12px;
    background:#0097a7;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

.update-btn:hover{
    background:#006064;
}

.no-data{
    padding:30px;
    text-align:center;
    color:#777;
}

.back{
    text-align:center;
    margin:30px;
}

.back a{
    display:inline-block;
    padding:12px 25px;
    background:#0097a7;
    color:white;
    text-decoration:none;
    border-radius:8px;
}

.back a:hover{
    background:#006064;
}

footer{
    margin-top:40px;
    background:#0097a7;
    color:white;
    text-align:center;
    padding:15px;
}

@keyframes fade{

    from{
        opacity:0;
        transform:translateY(20px);
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
    🦷 Smile Care Dental Clinic
</header>

<div class="container">

<div class="heading">

<h2>📋 Manage Appointments</h2>

<p>View and update your patient appointments.</p>

</div>

<?php

if ($message != "") {
    echo "<div class='message'>" .
         htmlspecialchars($message) .
         "</div>";
}

?>

<div class="table-box">

<table>

<tr>

<th>Patient</th>
<th>Phone</th>
<th>Treatment</th>
<th>Date</th>
<th>Time</th>
<th>Status</th>
<th>Action</th>

</tr>

<?php

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

?>

<tr>

<td>
<?php echo htmlspecialchars($row["patient_name"]); ?>
</td>

<td>
<?php echo htmlspecialchars($row["patient_phone"]); ?>
</td>

<td>
<?php echo htmlspecialchars($row["reason"]); ?>
</td>

<td>
<?php echo date("d F Y", strtotime($row["appointment_date"])); ?>
</td>

<td>
<?php echo date("h:i A", strtotime($row["appointment_time"])); ?>
</td>

<td>

<form method="POST">

<input
    type="hidden"
    name="appointment_id"
    value="<?php echo $row["appointment_id"]; ?>"
>

<select name="status">

<option value="Pending"
<?php if ($row["status"] == "Pending") echo "selected"; ?>>
Pending
</option>

<option value="Confirmed"
<?php if ($row["status"] == "Confirmed") echo "selected"; ?>>
Confirmed
</option>

<option value="Completed"
<?php if ($row["status"] == "Completed") echo "selected"; ?>>
Completed
</option>

<option value="Cancelled"
<?php if ($row["status"] == "Cancelled") echo "selected"; ?>>
Cancelled
</option>

</select>

</td>

<td>

<button type="submit" class="update-btn">
Update
</button>

</form>

</td>

</tr>

<?php

    }

} else {

?>

<tr>

<td colspan="7" class="no-data">

No appointments found.

</td>

</tr>

<?php

}

?>

</table>

</div>

<div class="back">

<a href="dentist_dashboard.php">
← Back to Dashboard
</a>

</div>

</div>

<footer>

© 2026 Smile Care Dental Clinic

</footer>

</body>

</html>

<?php

$stmt->close();
$conn->close();
?>