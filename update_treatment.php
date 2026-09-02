<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["dentist_id"])) {
    header("Location: login.html");
    exit();
}

$dentist_id = $_SESSION["dentist_id"];

$patients = $conn->query("SELECT patient_id, name FROM patients ORDER BY name ASC");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $patient_id = $_POST["patient_id"];
    $treatment_name = $_POST["treatment_name"];
    $treatment_date = $_POST["treatment_date"];
    $description = $_POST["description"];
    $cost = $_POST["cost"];

    $sql = "INSERT INTO treatments
            (patient_id, dentist_id, treatment_name, treatment_date, description, cost)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "iisssd",
        $patient_id,
        $dentist_id,
        $treatment_name,
        $treatment_date,
        $description,
        $cost
    );

    if ($stmt->execute()) {
        $message = "Treatment record added successfully! 🦷";
    } else {
        $message = "Failed to add treatment record.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Update Treatment | Smile Care</title>

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
    width:90%;
    max-width:650px;
    margin:35px auto;
    background:white;
    padding:30px;
    border-radius:15px;
    box-shadow:0 0 15px lightgray;
}

h2{
    color:#0097a7;
    text-align:center;
    margin-bottom:10px;
}

.subtitle{
    text-align:center;
    color:#666;
    margin-bottom:25px;
}

label{
    display:block;
    margin-top:15px;
    margin-bottom:7px;
    font-weight:bold;
    color:#444;
}

input,
select,
textarea{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:16px;
}

textarea{
    height:100px;
    resize:none;
}

button{
    width:100%;
    margin-top:25px;
    padding:13px;
    background:#0097a7;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:16px;
    transition:.3s;
}

button:hover{
    background:#006064;
    transform:scale(1.02);
}

.message{
    text-align:center;
    margin-bottom:15px;
    color:#008000;
    font-weight:bold;
}

.back{
    display:block;
    text-align:center;
    margin-top:20px;
    color:#0097a7;
    text-decoration:none;
    font-weight:bold;
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

<h2>🦷 Update Treatment</h2>

<p class="subtitle">
    Add treatment details for a patient
</p>

<?php if ($message != "") { ?>

<p class="message">
    <?php echo htmlspecialchars($message); ?>
</p>

<?php } ?>


<form method="POST">

<label>Select Patient</label>

<select name="patient_id" required>

<option value="">Select Patient</option>

<?php

if ($patients->num_rows > 0) {

    while ($patient = $patients->fetch_assoc()) {

        echo "<option value='" .
             htmlspecialchars($patient["patient_id"]) .
             "'>" .
             htmlspecialchars($patient["name"]) .
             "</option>";
    }

}

?>

</select>


<label>Treatment</label>

<select name="treatment_name" required>

<option value="">Select Treatment</option>

<option value="Dental Check-up">Dental Check-up</option>
<option value="Teeth Cleaning">Teeth Cleaning</option>
<option value="Root Canal">Root Canal</option>
<option value="Dental Filling">Dental Filling</option>
<option value="Braces">Braces</option>
<option value="Tooth Extraction">Tooth Extraction</option>
<option value="Dental Crown">Dental Crown</option>
<option value="Dental Implant">Dental Implant</option>

</select>


<label>Treatment Date</label>

<input
    type="date"
    name="treatment_date"
    required
>


<label>Description</label>

<textarea
    name="description"
    placeholder="Enter treatment details..."
></textarea>


<label>Cost (₹)</label>

<input
    type="number"
    name="cost"
    step="0.01"
    min="0"
    placeholder="Enter treatment cost"
    required
>


<button type="submit">
    Save Treatment Record
</button>

</form>


<a href="dentist_dashboard.php" class="back">
    ⬅ Back to Dashboard
</a>

</div>


<footer>
    © 2026 Smile Care Dental Clinic. All Rights Reserved.
</footer>

</body>

</html>

<?php
$conn->close();
?>