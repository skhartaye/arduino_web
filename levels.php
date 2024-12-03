<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root"; // change if necessary
$password = ""; // change if necessary
$dbname = "smart_kitchen";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(array('error' => 'Connection failed: ' . $conn->connect_error)));
}

// Function to classify data
function classify_data($data, $type) {
    switch ($type) {
        case 'temperature':
            return ($data > 25) ? "Hot" : "Cold"; // Adjust temperature threshold as needed
        case 'humidity':
            return ($data > 60) ? "High Humid" : "Low Humid"; // Adjust humidity threshold as needed
        case 'gas':
            return ($data > 300) ? "Danger" : (($data > 150) ? "Moderate" : "Low"); // Adjust gas thresholds as needed
        case 'water':
            return ($data > 75) ? "Danger" : (($data > 50) ? "Not Safe" : "Safe"); // Adjust water level thresholds as needed
        case 'motion':
            return ($data == 0) ? "Detected" : "None Detected"; // Adjust motion detection status as needed
        default:
            return "Unknown";
    }
}

// Fetch and classify data
$response = array();

// Fetch latest temperature and humidity data
$sql = "SELECT * FROM dht_data ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $response['temperature_status'] = classify_data($row['temperature'], 'temperature');
    $response['humidity_status'] = classify_data($row['humidity'], 'humidity');
} else {
    $response['temperature_status'] = 'Error fetching data';
    $response['humidity_status'] = 'Error fetching data';
}

// Fetch latest gas smoke data
$sql = "SELECT * FROM gas_smoke_data ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $response['gas_status'] = classify_data($row['gas_smoke_level'], 'gas');
} else {
    $response['gas_status'] = 'Error fetching data';
}

// Fetch latest water level data
$sql = "SELECT * FROM water_level_data ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $response['water_status'] = classify_data($row['water_level'], 'water');
} else {
    $response['water_status'] = 'Error fetching data';
}

// Fetch latest motion detection data
$sql = "SELECT * FROM ir_data ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $response['motion_status'] = classify_data($row['ir_state'], 'motion');
} else {
    $response['motion_status'] = 'Error fetching data';
}

echo json_encode($response);
$conn->close();
?>
