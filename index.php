<?php
$insert = false;

if (isset($_POST['name'])) {
    // Database connection variables
    $server = "localhost";
    $username = "root";
    $password = "";

    // Create database connection
    $con = mysqli_connect($server, $username, $password);

    // Check for connection success
    if (!$con) {
        die("Connection to this database failed due to " . mysqli_connect_error());
    }

    // Collect POST variables
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $members = $_POST['members'];
    $duration = $_POST['duration'];
    $desc = $_POST['desc'];

    // Insert data into the database
    $sql = "INSERT INTO `trip`.`trip` (`name`, `age`, `gender`, `email`, `phone`, `other`, `members`, `duration`, `dt`) 
            VALUES ('$name', '$age', '$gender', '$email', '$phone', '$desc', '$members', '$duration', current_timestamp());";

    if ($con->query($sql) === true) {
        $insert = true;
    } else {
        echo "ERROR: $sql <br> $con->error";
    }

    // Close the connection
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Travel Form</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto|Sriracha&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to Travigo Trip Form</h1>
        <p>Enter your details and submit this form to see the recommended destination</p>
        <?php if ($insert): ?>
            <p class="submitMsg">Thanks for submitting your form !</p>
        <?php endif; ?>
        <form action="recommendations.php" method="post">
            <input type="text" name="name" id="name" placeholder="Enter your name" required>
            <input type="number" name="age" id="age" placeholder="Enter your Age" required>
            <input type="text" name="gender" id="gender" placeholder="Enter your gender" required>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>
            <input type="tel" name="phone" id="phone" placeholder="Enter your phone" required>
            <input type="number" name="members" id="members" placeholder="Enter no of members" required>
            <input type="number" name="duration" id="duration" placeholder="Enter Duration (in days)" required>
            <textarea name="desc" id="desc" cols="30" rows="10" placeholder="Enter any other information here"></textarea>
            <button class="btn">Submit</button>
        </form>
    </div>
</body>
</html>
