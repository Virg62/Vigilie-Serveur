<?php
header("Access-Control-Allow-Origin: *");
session_start();
require 'vendor/autoload.php';
require "utils/db.php";
header('Content-Type: application/json');
$request = $_POST["request"];
if (is_string($_POST["data"])) {
    $data = json_decode($_POST["data"], true);
} else {
    $data = $_POST["data"];
}
$token = $_POST["token"];
$rep = array();
switch ($request) {

    case "check":
        $rep = ["success" => true, "message" => "connexion disponible", "data" => ["ver" => "a0.1", "sver" => "a0.1"]];
        break;

    case "login":
        require "auth/login.php";
        $rep = login($data);
        break;

    case "register":
        require "auth/register.php";
        $rep = register($data);
        break;

    case "send_alert":
        require "data/alerts.php";
        $rep = sendAlert($data, $token);
        break;

    case "get_alerts":
        require "data/alerts.php";
        if (!isset($data["all"])) {
            $data["all"] = false;
        }
        $rep = getAlerts($data, $token);
        break;

    case "connected":
        require "auth/logged.php";
        $rep = logged($token);
        break;

    case "get_page":
        require "data/pages.php";
        $rep = getPage($data, $token);
        break;

    case "profile":
        require "auth/profile.php";
        $rep = profile($data, $token);
        break;

    case "admin":
        require "admin/admin.php";
        $rep = admin($data, $token);
        break;

    default:
        $rep = ["success" => false, "message" => "La commande n'a pas été trouvée : " . $request, "data" => null];
        break;
}

echo json_encode($rep);

/*
* Le PHP, c'est bien ! 👍
*/
