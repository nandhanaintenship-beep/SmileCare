<?php
session_start();

include "config/db.php";

// Check whether patient is logged in
if (!isset($_SESSION["patient_id"])) {
    header("Location: login.html");
    exit();
}

$patient_id = $_SESSION["patient_id"];

// Get logged-in patient's details
$sql = "SELECT * FROM patients WHERE patient_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows != 1) {
    echo "Patient not found.";
    exit();
}

$patient = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Patient Dashboard | Smile Care</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f0f9ff;
            min-height: 100vh;
        }

        header {
            background: #0ea5e9;
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            font-size: 28px;
        }

        .logout-btn {
            background: white;
            color: #0ea5e9;
            padding: 10px 18px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: bold;
        }

        .logout-btn:hover {
            background: #e0f2fe;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 40px auto;
        }

        .welcome {
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome h2 {
            color: #0369a1;
            font-size: 30px;
        }

        .welcome p {
            margin-top: 8px;
            color: #555;
        }

        .profile-card {
            background: white;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 35px;
        }

        .profile-card h3 {
            color: #0284c7;
            margin-bottom: 20px;
        }

        .profile-details p {
            margin: 12px 0;
            color: #444;
        }

        .profile-details b {
            color: #0369a1;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 18px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: 0.3s;
            text-decoration: none;
            color: #333;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .card .icon {
            font-size: 40px;
            margin-bottom: 12px;
        }

        .card h3 {
            color: #0284c7;
            margin-bottom: 8px;
        }

        .card p {
            font-size: 14px;
            color: #666;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            padding: 20px;
            color: #777;
        }
    </style>
</head>

<body>

<header>

    <h1>🦷 Smile Care</h1>

    <a href="logout.php" class="logout-btn">
        Logout
    </a>

</header>


<div class="container">

    <div class="welcome">

        <h2>
            Welcome, <?php echo htmlspecialchars($patient["name"]); ?>! 😊
        </h2>

        <p>
            We're happy to have you with Smile Care.
        </p>

    </div>


    <!-- Patient Profile -->

    <div class="profile-card">

        <h3>👤 My Profile</h3>

        <div class="profile-details">

            <p>
                <b>Name :</b>
                <?php echo htmlspecialchars($patient["name"]); ?>
            </p>

            <p>
                <b>Age :</b>
                <?php echo htmlspecialchars($patient["age"]); ?>
            </p>

            <p>
                <b>Gender :</b>
                <?php echo htmlspecialchars($patient["gender"]); ?>
            </p>

            <p>
                <b>Phone :</b>
                <?php echo htmlspecialchars($patient["phone"]); ?>
            </p>

            <p>
                <b>Email :</b>
                <?php echo htmlspecialchars($patient["email"]); ?>
            </p>

        </div>

    </div>


    <!-- Dashboard Cards -->

    <div class="cards">

        <a href="appointment.php" class="card">

            <div class="icon">📅</div>

            <h3>Book Appointment</h3>

            <p>
                Schedule a new dental appointment.
            </p>

        </a>


        <a href="myappointments.php" class="card">

            <div class="icon">🗓️</div>

            <h3>My Appointments</h3>

            <p>
                View your upcoming appointments.
            </p>

        </a>


        <a href="treatment_history.php" class="card">

            <div class="icon">🦷</div>

            <h3>Treatment History</h3>

            <p>
                View your previous dental treatments.
            </p>

        </a>


        <a href="edit_profile.php" class="card">

            <div class="icon">✏️</div>

            <h3>Edit Profile</h3>

            <p>
                Update your personal information.
            </p>

        </a>


        <a href="change_password.php" class="card">

            <div class="icon">🔐</div>

            <h3>Change Password</h3>

            <p>
                Change your account password.
            </p>

        </a>

    </div>

</div>


<footer>

    © 2026 Smile Care Dental Clinic. All Rights Reserved.

</footer>

</body>
</html>