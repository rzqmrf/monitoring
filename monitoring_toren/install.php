<?php
// Create database if not exists
$servername = "localhost";
$username   = "root";
$password   = "";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE monitoring_toren";
if ($conn->query($sql) === TRUE) {
    echo "Database 'monitoring_toren' created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}
$conn->close();

// Connect to database and create table
$dbname = "monitoring_toren";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create table
$sql = "CREATE TABLE sensor_data (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tinggi_air FLOAT NOT NULL,
    volume_air FLOAT NOT NULL,
    persen_air FLOAT NOT NULL,
    estimasi_biaya FLOAT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'sensor_data' created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

$conn->close();
?>
