<?php
// install.php
$servername = "localhost";
$username   = "root";     // ganti kalau MySQL kamu pakai user lain
$password   = "";         // ganti kalau MySQL kamu pakai password
$dbname     = "monitoring_toren";

try {
    // Koneksi tanpa DB dulu
    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Buat database
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql) === TRUE) {
        echo "Database '$dbname' berhasil dibuat/ada.<br>";
    } else {
        die("Error membuat database: " . $conn->error);
    }

    // Pilih database
    $conn->select_db($dbname);

    // Buat tabel sensor_data
    $sql = "CREATE TABLE IF NOT EXISTS sensor_data (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tinggi_air FLOAT NOT NULL,
        volume_air FLOAT NOT NULL,
        persen_air FLOAT NOT NULL,
        estimasi_biaya FLOAT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB";

    if ($conn->query($sql) === TRUE) {
        echo "Tabel 'sensor_data' berhasil dibuat/ada.<br>";
    } else {
        die("Error membuat tabel: " . $conn->error);
    }

    echo "<br><b>Instalasi database selesai!</b>";

    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
