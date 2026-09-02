<?php

session_start();

include "config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userType = $_POST["userType"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Patient login
    if ($userType == "patient") {

        $sql = "SELECT * FROM patients WHERE email = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $patient = $result->fetch_assoc();

            if (password_verify($password, $patient["password"])) {

                // Remember the logged-in patient
                $_SESSION["patient_id"] = $patient["patient_id"];

                // Go to patient dashboard
                header("Location: patient_dashboard.php");
                exit();

            } else {

                echo "Incorrect password.<br>";
                echo "<a href='login.html'>Try Again</a>";

            }

        } else {

            echo "Patient account not found.<br>";
            echo "<a href='login.html'>Try Again</a>";

        }
    }

    // Dentist login
    elseif ($userType == "dentist") {

        $sql = "SELECT * FROM dentists WHERE email = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $dentist = $result->fetch_assoc();

            if (password_verify($password, $dentist["password"])) {

                $_SESSION["dentist_id"] = $dentist["dentist_id"];

                header("Location: dentist_dashboard.php");
                exit();

            } else {

                echo "Incorrect password.<br>";
                echo "<a href='login.html'>Try Again</a>";

            }

        } else {

            echo "Dentist account not found.<br>";
            echo "<a href='login.html'>Try Again</a>";

        }
    }

    else {

        echo "Please select a valid user type.";

    }

    $conn->close();
}

?>