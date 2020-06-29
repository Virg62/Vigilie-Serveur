<?php

use ReallySimpleJWT\Token;

function login($data)
{
    global $db;
    $rep = ["success" => false, "message" => "une erreur est survenue", "data" => null];
    $username = $data["username"];
    $password = $data["password"];

    $conn = $db->prepare("SELECT * FROM users WHERE username = ?");
    $conn->execute([$username]
    );
    $data_db = $conn->fetchAll();
    if (count($data_db) == 1) {
        if (password_verify($password, $data_db[0]["password"])) {
            // on rehash si l'algorithme par défaut à changé
            if (password_needs_rehash($data_db[0]["password"], PASSWORD_DEFAULT)) {
                $enreg = $db->prepare("UPDATE users SET password = ? WHERE username = ?");
                $enreg->execute([$username]);
            }

            $userId = $data_db[0]["id_user"];
            $secret = "sec!ReT423*&";
            $expiration = time() + 86400 * 7;
            $issuer = "localhost";
            $token = Token::create($userId, $secret, $expiration, $issuer);

            $rep["success"] = true;
            $rep["message"] = "Connexion réussie !";// $rep["data"]["user"] = $data[0];
            $rep["data"]["token"] = $token;
            $rep["data"]["redirect"] = "home";
            if (isset($data["type"]) && $data["type"] == "admin") {
                if ($data_db[0]["type_user"] != 2) {
                    $rep["success"] = false;
                    $rep["message"] = "Vous devez être administrateur pour effectuer cette action !";
                    $rep["data"] = null;
                } else {
                    $rep["data"]["redirect"] = "admin_home";
                }
            }
        } else {
            $rep["message"] = "Le mot de passe entré est faux";
        }
    } else {
        $rep["message"] = "Le nom d'utilisateur n'existe pas.";
    }
    return $rep;
}
