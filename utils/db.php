<?php
$servername = "localhost";
$username = "carry";
$password = "carry";
header('Content-Type: application/json');
try {
    $db = new PDO("mysql:host=$servername;dbname=carry;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // set the PDO error mode to exception
    ]);

    // echo "Connected successfully";
} catch (PDOException $e) {
    echo json_encode(array("success" => false, "message" => "erreur base de données", "data" => "Connection failed: " . $e->getMessage()));
    die();
}
