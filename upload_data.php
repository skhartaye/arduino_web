<?php
$servername = "localhost";
$username = "root";  // Default XAMPP username
$password = "";  // Default XAMPP password
$dbname = "arduino";  // Make sure this is the correct database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the temperature and humidity values from the URL
if (isset($_GET['temperature']) && isset($_GET['humidity'])) {
    $temperature = $_GET['temperature'];
    $humidity = $_GET['humidity'];

    // Ensure the temperature and humidity are valid numbers
    if (is_numeric($temperature) && is_numeric($humidity)) {
        // Use prepared statements to avoid SQL injection
        $stmt = $conn->prepare("INSERT INTO readings (temperature, humidity) VALUES (?, ?)");
        $stmt->bind_param("dd", $temperature, $humidity);

        if ($stmt->execute()) {
            echo "Data uploaded successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Invalid temperature or humidity values.";
    }
} else {
    echo "Temperature or humidity data not received.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sensor Data</title>
</head>
<body>
    <h1>Temperature and Humidity Readings</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Temperature</th>
                <th>Humidity</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch data from the database
            $conn = new mysqli("localhost", "root", "", "arduino");  // Use the same database here
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM readings ORDER BY timestamp DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>".$row['id']."</td><td>".$row['temperature']."</td><td>".$row['humidity']."</td><td>".$row['timestamp']."</td></tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No data available</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>
