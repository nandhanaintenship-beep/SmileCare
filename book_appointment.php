<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["patient_id"])) {
    header("Location: login.html");
    exit();
}

$patient_id = $_SESSION["patient_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $dentist_id = $_POST["doctor_id"];
    $appointment_date = $_POST["date"];
    $appointment_time = $_POST["time"];
    $reason = $_POST["treatment"];

    $status = "Pending";

    $sql = "INSERT INTO appointments
            (patient_id, dentist_id, appointment_date, appointment_time, reason, status)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "iissss",
        $patient_id,
        $dentist_id,
        $appointment_date,
        $appointment_time,
        $reason,
        $status
    );

    if ($stmt->execute()) {
        $success = true;
    } else {
        $success = false;
        $error = $stmt->error;
    }

    $stmt->close();
    $conn->close();

} else {
    header("Location: appointment.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Appointment Booked | Smile Care</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #e0f7fa, #f5fbff);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .success-box {
            width: 90%;
            max-width: 550px;
            background: white;
            padding: 45px 35px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            animation: fadeIn 0.8s ease;
        }

        .icon {
            font-size: 60px;
            margin-bottom: 15px;
        }

        h1 {
            color: #0097a7;
            margin-bottom: 15px;
            font-size: 32px;
        }

        p {
            color: #555;
            font-size: 18px;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            padding: 13px 28px;
            background: #0097a7;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-size: 17px;
            margin: 5px;
            transition: 0.3s;
        }

        .btn:hover {
            background: #007c8a;
            transform: translateY(-2px);
        }

        .appointment-btn {
            background: #26a69a;
        }

        .appointment-btn:hover {
            background: #1b8c80;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error {
            color: #d32f2f;
            font-size: 18px;
        }
    </style>
</head>

<body>

<?php if ($success): ?>

    <div class="success-box">

        <div class="icon">🦷✨</div>

        <h1>Appointment Booked Successfully!</h1>

        <p>
            Your appointment has been submitted successfully.
        </p>

        <a href="patient_dashboard.php" class="btn">
            ← Go to Dashboard
        </a>

        <a href="myappointments.php" class="btn appointment-btn">
            📅 My Appointments
        </a>

    </div>

<?php else: ?>

    <div class="success-box">

        <div class="icon">❌</div>

        <h1>Booking Failed</h1>

        <p class="error">
            <?php echo htmlspecialchars($error); ?>
        </p>

        <a href="appointment.php" class="btn">
            ← Try Again
        </a>

    </div>

<?php endif; ?>

</body>
</html>