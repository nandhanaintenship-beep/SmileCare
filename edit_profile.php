<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["patient_id"])) {
    header("Location: login.html");
    exit();
}

$patient_id = $_SESSION["patient_id"];

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $age = $_POST["age"];
    $gender = $_POST["gender"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];

    $sql = "UPDATE patients
            SET name = ?, age = ?, gender = ?, phone = ?, email = ?
            WHERE patient_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sisssi",
        $name,
        $age,
        $gender,
        $phone,
        $email,
        $patient_id
    );

    if ($stmt->execute()) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Failed to update profile.";
    }

    $stmt->close();
}

/* Get current patient details */

$sql = "SELECT name, age, gender, phone, email
        FROM patients
        WHERE patient_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();

$result = $stmt->get_result();
$patient = $result->fetch_assoc();

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Profile | Smile Care</title>

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
    width:90%;
    max-width:600px;
    margin:40px auto;
    background:white;
    padding:35px;
    border-radius:18px;
    box-shadow:0 5px 20px rgba(0,0,0,.15);
    animation:fade 0.8s;
}

h2{
    text-align:center;
    color:#00838f;
    margin-bottom:25px;
}

.message{
    text-align:center;
    background:#e0f7fa;
    color:#00796b;
    padding:12px;
    border-radius:8px;
    margin-bottom:20px;
    font-weight:bold;
}

label{
    display:block;
    margin-top:15px;
    margin-bottom:7px;
    color:#444;
    font-weight:bold;
}

input,
select{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:16px;
    outline:none;
}

input:focus,
select:focus{
    border-color:#0097a7;
}

.update-btn{
    width:100%;
    margin-top:25px;
    padding:13px;
    background:#0097a7;
    color:white;
    border:none;
    border-radius:8px;
    font-size:17px;
    cursor:pointer;
    transition:.3s;
}

.update-btn:hover{
    background:#006064;
    transform:translateY(-2px);
}

.back{
    text-align:center;
    margin-top:20px;
}

.back a{
    text-decoration:none;
    color:#0097a7;
    font-weight:bold;
}

footer{
    margin-top:50px;
    background:#0097a7;
    color:white;
    text-align:center;
    padding:15px;
}

@keyframes fade{

    from{
        opacity:0;
        transform:translateY(25px);
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

<div class="container">

<h2>👤 Edit Profile</h2>

<?php

if ($message != "") {
    echo "<div class='message'>" .
         htmlspecialchars($message) .
         "</div>";
}

?>

<form method="POST">

<label>Full Name</label>

<input
    type="text"
    name="name"
    value="<?php echo htmlspecialchars($patient['name']); ?>"
    required
>

<label>Age</label>

<input
    type="number"
    name="age"
    value="<?php echo htmlspecialchars($patient['age']); ?>"
    required
>

<label>Gender</label>

<select name="gender" required>

<option value="Male"
<?php if ($patient['gender'] == "Male") echo "selected"; ?>>
Male
</option>

<option value="Female"
<?php if ($patient['gender'] == "Female") echo "selected"; ?>>
Female
</option>

<option value="Other"
<?php if ($patient['gender'] == "Other") echo "selected"; ?>>
Other
</option>

</select>

<label>Phone</label>

<input
    type="text"
    name="phone"
    value="<?php echo htmlspecialchars($patient['phone']); ?>"
    required
>

<label>Email</label>

<input
    type="email"
    name="email"
    value="<?php echo htmlspecialchars($patient['email']); ?>"
    required
>

<button type="submit" class="update-btn">

💾 Update Profile

</button>

</form>

<div class="back">

<a href="patient_dashboard.php">
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

$conn->close();

?>