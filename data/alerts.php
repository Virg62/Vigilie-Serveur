<?php

function getAlerts($data, $token = null)
{
    global $db;
    $rep = ["success" => false, "message" => "une erreur est survenue", "data" => null];
    // renvoie toutes les alertes au système demandeur ...
    require "auth/logged.php";
    if (is_null($token)) {
        $id_user = 0;
    } else {
        $id_user = getUser($token);
    }

    try {
        if ($data["all"]) {
            $requete = $db->prepare("SELECT * FROM alerts a JOIN users u ON a.user_id = u.id_user JOIN type_alert ta ON ta.id_type=a.type_alert ORDER BY id_alert DESC");
        } else {
            $requete = $db->prepare("SELECT * FROM alerts a JOIN users u ON a.user_id = u.id_user JOIN type_alert ta ON ta.id_type=a.type_alert WHERE validated=1 OR user_id = ? ORDER BY id_alert DESC");
        }

        $requete->execute([$id_user]);
        error_log($id_user);
        $data = $requete->fetchAll();

        $alerts = [];
        foreach ($data as $alert) {
            $alerts[] = [
                "id" => $alert["id_alert"],
                "author" => $alert["firstname"] . " " . $alert["lastname"],
                "title" => $alert["title"],
                "content" => $alert["content"],
                "img" => $alert["pic"],
                "location" => $alert["location"],
                "type_id" => $alert["type_alert"],
                "type" => $alert["label"],
                "date" => $alert["date"],
                "validated" => $alert["validated"]
            ];
        }
        $rep["success"] = true;
        $rep["message"] = "Alertes récupérées avec succès !";
        $rep["data"]["alerts"] = $alerts;


    } catch (Exception $e) {
        $rep["message"] = "Une erreur de serveur lors de la requête...";
    }

    return $rep;
}

function sendAlert($data, $token)
{
    global $db;
    $rep = ["success" => false, "message" => "l'utilisateur n'est pas connecté !", "data" => null];
    // récupération de l'identifiant de l'utilisateur.
    require "auth/profile.php";
    $id_user = getUser($token);
    if ($id_user === false) {
        return $rep;
    } else {
        if (getProfile($id_user)["data"]["validated"] == 0) {
            $rep["message"] = "Votre compte doit être validé pour envoyer une alerte.";
            return $rep;
        }
    }

    $alert = [];

    $alert["content"] = htmlspecialchars($data["content"], ENT_QUOTES);
    $alert["user_id"] = $id_user;
    foreach ($alert as $elt) {
        if ($elt == "" || $elt == null) {
            $rep["message"] = "Un champ obligatoire n'a pas été remplis";
            return $rep;
        }
    }

    $alert["title"] = htmlspecialchars($data["title"], ENT_QUOTES);

    // gestion de l'image
    //error_log(getcwd());
    if (!is_null($data["img"]) && $data["img"] != "") {
        $image = base64_decode($data["img"]);
        $img_type = explode(";", explode("data:image/", $image)[1])[0];
        $img_b64 = explode("base64,", $image)[1];
        $img_filename = sha1($img_b64) . "." . $img_type;
        $img_content = base64_decode($img_b64);
        $file = fopen("./data/pictures/" . $img_filename, "w");
        fwrite($file, $img_content);
        fclose($file);


        // A CHANGER QUAND SERVEUR MODIFIE!
        $alert["img"] = "/data/pictures/" . $img_filename;
    } else {
        $alert["img"] = null;
    }


    //$alert["img"] = null;
    $alert["location"] = htmlspecialchars($data["location"], ENT_QUOTES);

    // récupération de la date
    $date = new DateTime();
    $alert["date"] = $date->format("d/m/Y à H:i");

    try {
        $request = $db->prepare("INSERT INTO alerts(user_id, title, content, pic, location, date) VALUES (?, ?, ?, ?, ?, ?)");
        $request->execute(
            [
                $alert["user_id"],
                $alert["title"],
                $alert["content"],
                $alert["img"],
                $alert["location"],
                $alert["date"]
            ]
        );
        require "utils/notify.php";
        notify(null, "alert", true, "Une alerte à été ajoutée par un utilisateur : " . $alert["title"], "Ajout d'alerte");

        $rep["success"] = true;
        $rep["message"] = "L'alerte a été envoyée avec succès.";
        $rep["data"]["redirect"] = "home";

    } catch (Exception $e) {
        $rep["message"] = "Une erreur est survenue avec la base de données.";
    }
    return $rep;
}
