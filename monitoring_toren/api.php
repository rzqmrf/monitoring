<?php
include "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tinggi = $_POST['tinggi_air'] ?? 0;
    $volume = $_POST['volume_air'] ?? 0;
    $persen = $_POST['persen_air'] ?? 0;
    $biaya  = $_POST['estimasi_biaya'] ?? 0;

    $sql = "INSERT INTO sensor_data (tinggi_air, volume_air, persen_air, estimasi_biaya) 
            VALUES ('$tinggi', '$volume', '$persen', '$biaya')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "OK"]);
    } else {
        echo json_encode(["status" => "ERROR", "message" => mysqli_error($conn)]);
    }
} else {
    echo json_encode(["status" => "Invalid Request"]);
}
?>
