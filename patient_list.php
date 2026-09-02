<?php
session_start();
include "config/db.php";

if (!isset($_SESSION["dentist_id"])) {
    header("Location: login.html");
    exit();
}

$sql = "SELECT patient_id, name, age, gender, phone, email
        FROM patients
        ORDER BY name ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Patient List | Smile Care</title>

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

/* Header */

header{
    background:#0097a7;
    color:white;
    padding:20px;
    text-align:center;
    font-size:28px;
    font-weight:bold;
    animation:fade 1s;
}

/* Container */

.container{
    width:90%;
    margin:30px auto;
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 0 15px lightgray;
    animation:fade 1.2s;
}

h2{
    color:#0097a7;
    margin-bottom:10px;
}

.subtitle{
    color:#666;
    margin-bottom:20px;
}

/* Search */

.search-box{
    margin-bottom:20px;
}

.search-box input{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:16px;
}

/* Table */

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th{
    background:#0097a7;
    color:white;
    padding:12px;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #ddd;
}

tr:hover{
    background:#f1fdff;
}

/* Back Button */

.btn{
    margin-top:25px;
    padding:12px 25px;
    background:#0097a7;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:16px;
    transition:.3s;
}

.btn:hover{
    background:#006064;
    transform:scale(1.05);
}

/* Animation */

@keyframes fade{

    from{
        opacity:0;
        transform:translateY(30px);
    }

    to{
        opacity:1;
        transform:translateY(0);
    }

}

/* Footer */

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

<h2>👥 Patient List</h2>

<p class="subtitle">
    View registered patient information
</p>


<div class="search-box">

<input
    type="text"
    id="search"
    placeholder="Search patient name..."
>

</div>


<table id="patientTable">

<tr>
    <th>ID</th>
    <th>Patient Name</th>
    <th>Age</th>
    <th>Gender</th>
    <th>Phone</th>
    <th>Email</th>
</tr>


<?php

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        echo "<tr>";

        echo "<td>" . htmlspecialchars($row["patient_id"]) . "</td>";

        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";

        echo "<td>" . htmlspecialchars($row["age"]) . "</td>";

        echo "<td>" . htmlspecialchars($row["gender"]) . "</td>";

        echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";

        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";

        echo "</tr>";
    }

} else {

    echo "<tr>";

    echo "<td colspan='6'>No patients registered.</td>";

    echo "</tr>";
}

?>

</table>


<button class="btn" onclick="goBack()">
    ⬅ Back to Dashboard
</button>

</div>


<footer>

© 2026 Smile Care Dental Clinic. All Rights Reserved.

</footer>


<script>

/* Search Patient */

document.getElementById("search").addEventListener("keyup", function(){

    let filter = this.value.toUpperCase();

    let table = document.getElementById("patientTable");

    let rows = table.getElementsByTagName("tr");

    for(let i = 1; i < rows.length; i++){

        let nameCell = rows[i].getElementsByTagName("td")[1];

        if(nameCell){

            let name = nameCell.textContent || nameCell.innerText;

            if(name.toUpperCase().indexOf(filter) > -1){

                rows[i].style.display = "";

            }else{

                rows[i].style.display = "none";

            }

        }

    }

});


/* Back to Dashboard */

function goBack(){

    window.location.href = "dentist_dashboard.php";

}

</script>

</body>

</html>

<?php
$conn->close();
?>