<?php
$servername = "localhost";
$username = "carry";
$password = "carry";
header('Content-Type: application/json');
try {
    $db = new PDO("mysql:host=$servername;dbname=carry", $username, $password,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";
} catch(PDOException $e) {
    echo json_encode(array("success" => false, "message" => "erreur base de données", "data" => "Connection failed: " . $e->getMessage()));
    die();
}


?>