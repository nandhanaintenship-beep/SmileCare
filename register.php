<?php

include "config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $age = $_POST["age"];
    $gender = $_POST["gender"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Securely hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO patients
            (name, age, gender, email, phone, password)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sissss",
        $name,
        $age,
        $gender,
        $email,
        $phone,
        $hashed_password
    );

    if ($stmt->execute()) {

        echo "<h2>Registration Successful!</h2>";
        echo "<p>Patient account created successfully.</p>";
        echo "<a href='login.html'>Go to Login</a>";

    } else {

        echo "Registration failed: " . $stmt->error;

    }

    $stmt->close();
    $conn->close();
}

?>