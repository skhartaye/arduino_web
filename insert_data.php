<?php
// Database connection
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = "";     // Your MySQL password
$dbname = "smart_kitchen"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if POST data is set
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $timestamp = date("Y-m-d H:i:s");

    // Insert gas and smoke data
    if (!empty($_POST['gas_smoke_level']) && isset($_POST['threshold_status'])) {
        $gasSmokeLevel = $_POST['gas_smoke_level'];
        $thresholdStatus = $_POST['threshold_status'];

        // Use prepared statements
        $stmt = $conn->prepare("INSERT INTO gas_smoke_data (gas_smoke_level, threshold_status, timestamp) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $gasSmokeLevel, $thresholdStatus, $timestamp);

        if ($stmt->execute()) {
            echo "Gas and smoke data inserted successfully<br>";
        } else {
            echo "Error inserting gas and smoke data: " . $stmt->error . "<br>";
        }

        $stmt->close();
    } else {
        echo "Gas or smoke data is missing.<br>";
    }

    // Insert water level data
    if (!empty($_POST['water_level'])) {
        $waterLevel = $_POST['water_level'];

        // Use prepared statements
        $stmt = $conn->prepare("INSERT INTO water_level_data (water_level, timestamp) VALUES (?, ?)");
        $stmt->bind_param("ss", $waterLevel, $timestamp);

        if ($stmt->execute()) {
            echo "Water level data inserted successfully<br>";
        } else {
            echo "Error inserting water level data: " . $stmt->error . "<br>";
        }

        $stmt->close();
    } else {
        echo "Water level data is missing.<br>";
    }

    // Insert DHT22 data (temperature and humidity)
    if (isset($_POST['temperature']) && isset($_POST['humidity'])) {
        $temperature = $_POST['temperature'];
        $humidity = $_POST['humidity'];

        // Use prepared statements
        $stmt = $conn->prepare("INSERT INTO dht_data (temperature, humidity, timestamp) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $temperature, $humidity, $timestamp);

        if ($stmt->execute()) {
            echo "DHT22 data inserted successfully<br>";
        } else {
            echo "Error inserting DHT22 data: " . $stmt->error . "<br>";
        }

        $stmt->close();
    } else {
        echo "DHT22 data is missing.<br>";
    }

    // Insert IR sensor data
    if (isset($_POST['ir_state'])) {
        $irState = $_POST['ir_state'];

        // Use prepared statements
        $stmt = $conn->prepare("INSERT INTO ir_data (ir_state, timestamp) VALUES (?, ?)");
        $stmt->bind_param("ss", $irState, $timestamp);

        if ($stmt->execute()) {
            echo "IR sensor data inserted successfully<br>";
        } else {
            echo "Error inserting IR sensor data: " . $stmt->error . "<br>";
        }

        $stmt->close();
    } else {
        echo "IR sensor data is missing.<br>";
    }
}

$conn->close();
?>
