<?php
$servername = "localhost";
$username = "root";  // Default XAMPP username
$password = "";  // Default XAMPP password
$dbname = "arduino";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
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
