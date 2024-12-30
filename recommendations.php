<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection variables
    $server = "localhost";
    $username = "root";
    $password = "";
    $dbname = "trip";

    // Create database connection
    $con = mysqli_connect($server, $username, $password, $dbname);

    // Check for connection success
    if (!$con) {
        die("Connection to this database failed due to " . mysqli_connect_error());
    }

    // Collect POST variables and validate inputs
    $age = isset($_POST['age']) ? (int)$_POST['age'] : 0;
    $members = isset($_POST['members']) ? (int)$_POST['members'] : 0;
    $duration = isset($_POST['duration']) ? (int)$_POST['duration'] : 0;
    $email = isset($_POST['email']) ? $_POST['email'] : "";

    // Ensure inputs are valid
    $recommendations = [];
    if ($age > 0 && $members > 0 && $duration > 0) {
        // Fetch recommended destinations based on criteria
        $sql = "SELECT destination, trip_type, hotel_name, travel_mode, tourist_place_name 
                FROM destinations 
                WHERE $age BETWEEN min_age AND max_age 
                  AND $members <= max_members 
                  AND $duration BETWEEN min_duration AND max_duration";

        $result = $con->query($sql);

        // Check if any destinations match
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $recommendations[] = [
                    'destination' => $row['destination'] ?? "N/A",
                    'trip_type' => $row['trip_type'] ?? "N/A",
                    'hotel_name' => $row['hotel_name'] ?? "N/A",
                    'travel_mode' => $row['travel_mode'] ?? "N/A",
                    'tourist_place_name' => $row['tourist_place_name'] ?? "N/A"
                ];
            }
        }
    }

    // Save recommendation back to the trip table
    if (!empty($recommendations) && !empty($email)) {
        $recommended_destination = implode(", ", array_column($recommendations, 'destination')); // Combine multiple destinations if applicable
        $update_sql = "UPDATE `trip` 
                       SET `recommended_destination` = '$recommended_destination' 
                       WHERE `email` = '$email' 
                       ORDER BY `dt` DESC 
                       LIMIT 1";

        if ($con->query($update_sql) === TRUE) {
            // Optional success message for debugging (not displayed to user)
            // echo "Recommendation saved successfully!";
        } else {
            echo "Error updating record: " . $con->error;
        }
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
    <title>Recommended Destinations</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto|Sriracha&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Your Recommended Travel Destinations</h1>
        <?php if (!empty($recommendations)): ?>
            <ul>
                <?php foreach ($recommendations as $recommendation): ?>
                    <li>
                        <strong>Destination:</strong> <?php echo htmlspecialchars($recommendation['destination']); ?><br>
                        <strong>Trip Type:</strong> <?php echo htmlspecialchars($recommendation['trip_type']); ?><br>
                        <strong>Hotel Name:</strong> <?php echo htmlspecialchars($recommendation['hotel_name']); ?><br>
                        <strong>Travel Mode:</strong> <?php echo htmlspecialchars($recommendation['travel_mode']); ?><br>
                        <strong>Tourist Place:</strong> <?php echo htmlspecialchars($recommendation['tourist_place_name']); ?>
                    </li>
                    <hr>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Sorry, no destinations match your preferences. Please try again with different criteria.</p>
        <?php endif; ?>
        <div class="btn-container">
            <a href="index.php" class="btn">Go Back</a>
        </div>
    </div>
</body>
</html>
