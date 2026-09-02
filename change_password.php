<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["patient_id"])) {
    header("Location: login.html");
    exit();
}

$patient_id = $_SESSION["patient_id"];
$message = "";
$message_class = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $current = $_POST["current"];
    $newpass = $_POST["newpass"];
    $confirm = $_POST["confirm"];

    if (empty($current) || empty($newpass) || empty($confirm)) {

        $message = "⚠ Please fill all the fields.";
        $message_class = "warning";

    } elseif (strlen($newpass) < 6) {

        $message = "⚠ Password must contain at least 6 characters.";
        $message_class = "warning";

    } elseif ($newpass !== $confirm) {

        $message = "❌ New Password and Confirm Password do not match.";
        $message_class = "error";

    } else {

        // Get current password
        $sql = "SELECT password FROM patients WHERE patient_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $patient = $result->fetch_assoc();

        $stmt->close();

        // Check current password
        if (!password_verify($current, $patient["password"])) {

            $message = "❌ Current password is incorrect.";
            $message_class = "error";

        } else {

            // Hash the new password
            $hashed_password = password_hash($newpass, PASSWORD_DEFAULT);

            // Update password
            $sql = "UPDATE patients
                    SET password = ?
                    WHERE patient_id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $hashed_password, $patient_id);

            if ($stmt->execute()) {

                $message = "✅ Password changed successfully!";
                $message_class = "success";

            } else {

                $message = "❌ Failed to change password.";
                $message_class = "error";
            }

            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Change Password | Smile Care</title>

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
background:linear-gradient(135deg,#00bcd4,#0288d1);
overflow:hidden;
}

.circle{
position:absolute;
border-radius:50%;
background:rgba(255,255,255,.2);
animation:float 6s infinite;
}

.circle1{
width:150px;
height:150px;
top:40px;
left:60px;
}

.circle2{
width:90px;
height:90px;
bottom:60px;
right:120px;
}

.circle3{
width:70px;
height:70px;
top:280px;
right:250px;
}

.box{
width:420px;
background:rgba(255,255,255,.18);
backdrop-filter:blur(10px);
padding:35px;
border-radius:18px;
box-shadow:0 10px 25px rgba(0,0,0,.3);
text-align:center;
animation:fade 1s;
}

.box h2{
color:white;
margin-bottom:10px;
}

.box p{
color:white;
margin-bottom:20px;
}

.password{
position:relative;
margin:15px 0;
}

.password input{
width:100%;
padding:12px;
border:none;
border-radius:8px;
outline:none;
font-size:16px;
}

.password span{
position:absolute;
right:15px;
top:12px;
cursor:pointer;
font-size:18px;
}

button{
width:100%;
padding:12px;
margin-top:15px;
border:none;
border-radius:8px;
font-size:17px;
cursor:pointer;
transition:.3s;
}

.save{
background:#0097a7;
color:white;
}

.save:hover{
background:#006064;
transform:scale(1.03);
}

.back{
background:white;
color:#0097a7;
}

.back:hover{
background:#eeeeee;
}

.message{
margin-top:15px;
font-weight:bold;
}

.success{
color:lightgreen;
}

.error{
color:#ffb3b3;
}

.warning{
color:yellow;
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

@keyframes float{

0%{
transform:translateY(0);
}

50%{
transform:translateY(-20px);
}

100%{
transform:translateY(0);
}

}

</style>

</head>

<body>

<div class="circle circle1"></div>
<div class="circle circle2"></div>
<div class="circle circle3"></div>

<div class="box">

<h2>🔒 Change Password</h2>

<p>Keep your account secure.</p>

<form method="POST">

<div class="password">

<input
type="password"
name="current"
id="current"
placeholder="Current Password"
required
>

<span onclick="togglePassword('current')">👁️</span>

</div>

<div class="password">

<input
type="password"
name="newpass"
id="newpass"
placeholder="New Password"
required
>

<span onclick="togglePassword('newpass')">👁️</span>

</div>

<div class="password">

<input
type="password"
name="confirm"
id="confirm"
placeholder="Confirm Password"
required
>

<span onclick="togglePassword('confirm')">👁️</span>

</div>

<button type="submit" class="save">

Save Password

</button>

</form>

<button class="back" onclick="goBack()">

Back to Dashboard

</button>

<?php

if ($message != "") {

echo "<div class='message $message_class'>" .
     htmlspecialchars($message) .
     "</div>";

}

?>

</div>

<script>

function togglePassword(id){

let input = document.getElementById(id);

if(input.type === "password"){

input.type = "text";

}else{

input.type = "password";

}

}

function goBack(){

window.location.href="patient_dashboard.php";

}

</script>

</body>

</html>

<?php

$conn->close();

?>