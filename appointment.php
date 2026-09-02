<?php
session_start();

if (!isset($_SESSION["patient_id"])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Book Appointment | Smile Care</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background-image:url("backimage1.jpg");
    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
    background-attachment:fixed;
}

.container{
    width:420px;
    background:rgba(255,255,255,0.2);
    backdrop-filter:blur(10px);
    padding:30px;
    border-radius:15px;
    box-shadow:0 8px 20px rgba(0,0,0,0.3);
    animation:fade 1s;
}

h1{
    text-align:center;
    color:white;
    margin-bottom:20px;
}

input,select{
    width:100%;
    padding:12px;
    margin:10px 0;
    border:none;
    border-radius:8px;
    outline:none;
    font-size:15px;
}

button{
    width:100%;
    padding:12px;
    margin-top:10px;
    background:#0097a7;
    color:white;
    border:none;
    border-radius:8px;
    font-size:18px;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#006064;
    transform:scale(1.03);
}

#message{
    text-align:center;
    margin-top:15px;
    font-weight:bold;
    color:white;
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

<div class="container">

<h1>Book Appointment</h1>

<form action="book_appointment.php" method="POST">

<input type="text"
       name="name"
       placeholder="Patient Name"
       required>

<input type="text"
       name="phone"
       placeholder="Phone Number"
       required>

<select name="doctor_id" required>

<option value="">Select Doctor</option>

<option value="1">Dr. Anjali Nair</option>
<option value="2">Dr. Rahul Menon</option>
<option value="3">Dr. Meera Joseph</option>

</select>

<select name="treatment" required>

<option value="">Select Treatment</option>

<option value="Dental Check-up">Dental Check-up</option>
<option value="Teeth Cleaning">Teeth Cleaning</option>
<option value="Root Canal">Root Canal</option>
<option value="Dental Filling">Dental Filling</option>
<option value="Braces">Braces</option>
<option value="Tooth Extraction">Tooth Extraction</option>

</select>

<input type="date"
       name="date"
       required>

<input type="time"
       name="time"
       required>

<button type="submit">
    Book Appointment
</button>

</form>

</div>

</body>
</html>