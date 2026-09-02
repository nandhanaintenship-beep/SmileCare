<?php

include "config/db.php";

$dentists = [
    ["Dr. Anjali Nair", "Orthodontist", "9876543210", "anjali@smilecare.com", "anjali123"],
    ["Dr. Rahul Menon", "General Dentist", "9876543211", "rahul@smilecare.com", "rahul123"],
    ["Dr. Meera Joseph", "Dental Surgeon", "9876543212", "meera@smilecare.com", "meera123"]
];

foreach ($dentists as $dentist) {

    $name = $dentist[0];
    $specialization = $dentist[1];
    $phone = $dentist[2];
    $email = $dentist[3];
    $password = password_hash($dentist[4], PASSWORD_DEFAULT);

    $sql = "INSERT INTO dentists
            (name, specialization, phone, email, password)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssss",
        $name,
        $specialization,
        $phone,
        $email,
        $password
    );

    if ($stmt->execute()) {
        echo $name . " added successfully.<br>";
    } else {
        echo $name . " failed: " . $stmt->error . "<br>";
    }

    $stmt->close();
}

$conn->close();

?>