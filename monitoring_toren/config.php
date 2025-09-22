<?php
header('Content-Type: application/json');

// setting toren/galon bisa kamu ganti di sini
$config = [
    "tinggi" => 19,   // cm, tinggi toren/galon penuh
    "volume" => 0.5    // Liter, volume penuh
];

echo json_encode($config);
?>
