<?php

session_start();

include "config/db.php";

if (!isset($_SESSION["patient_id"])) {
    header("Location: login.html");
    exit();
}

$patient_id = $_SESSION["patient_id"];

$name = $_POST["name"];
$phone = $_POST["phone"];
$doctor_id = $_POST["doctor_id"];
$treatment = $_POST["treatment"];
$date = $_POST["date"];
$time = $_POST["time"];

$reason = $treatment;

$sql = "INSERT INTO appointments
        (patient_id, dentist_id, appointment_date,
         appointment_time, reason, status)
        VALUES (?, ?, ?, ?, ?, 'Pending')";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "iisss",
    $patient_id,
    $doctor_id,
    $date,
    $time,
    $reason
);

if ($stmt->execute()) {

    echo "<h2>Appointment Booked Successfully! 🦷</h2>";
    echo "<p>Your appointment has been submitted.</p>";
    echo "<br>";
    echo "<a href='patient_dashboard.php'>Go to Dashboard</a>";

} else {

    echo "Booking failed: " . $stmt->error;

}

$stmt->close();
$conn->close();

?>