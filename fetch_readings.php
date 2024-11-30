<?php
header('Content-Type: application/json'); // Ensure the response is in JSON format

// Example database connection (replace with your own credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smart_kitchen"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Default response data array
$data = [];

// Determine the type of sensor data requested
if (isset($_GET['type'])) {
    $type = $_GET['type'];

    // Query based on the type of sensor requested
    switch ($type) {
        case 'gas':
            $sql = "SELECT gas_smoke_level, threshold_status, timestamp FROM gas_smoke_data"; // Adjust the table name and columns
            break;
        case 'water':
            $sql = "SELECT water_level, timestamp FROM water_level_data"; // Adjust the table name and columns
            break;
        case 'temperature':
            $sql = "SELECT temperature, humidity, timestamp FROM dht_data"; // Adjust the table name and columns
            break;
        case 'motion':
            $sql = "SELECT ir_state, timestamp FROM ir_data"; // Adjust the table name and columns
            break;
        default:
            // If no valid type is specified, return an empty response
            echo json_encode(['error' => 'Invalid sensor type']);
            exit();
    }

    // Execute the query
    $result = $conn->query($sql);

    // Check if data is available
    if ($result->num_rows > 0) {
        // Fetch all rows from the database
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
}

// Output data as JSON
echo json_encode($data);

$conn->close();
?>
