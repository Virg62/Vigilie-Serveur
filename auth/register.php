<?php
function register($data)
{
    global $db;
    $rep = ["success" => false, "message" => "une erreur est survenue", "data" => null];
    $user = [];
    $user["username"] = $data["username"];

    $user["password"] = $data["password"];
    $user["password_conf"] = $data["password_conf"];

    $user["address"] = $data["address"];

    $user["mail"] = $data["mail"];

    $user["lastname"] = htmlspecialchars($data["lastname"], ENT_QUOTES);
    $user["firstname"] = htmlspecialchars($data["firstname"], ENT_QUOTES);

    $user["pse"] = $data["pse"];

    $fault = false;
    foreach ($user as $dt) {
        if ($dt == "" || $dt == null) {
            $fault = true;
            $rep["success"] = false;
            $rep["message"] = "un ou plusieurs champ(s) n'a / n'ont pas été remplis";
            $rep["data"] = $user;
        }
    }
    if (!$fault) {
        if ($user["password"] === $user["password_conf"]) {
            if (user_dont_exists($user["mail"], $user["username"])) {
                $enreg = $db->prepare("INSERT INTO users(username, lastname, firstname, mail, address, password, pse) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $enreg->execute(
                    [
                        $user["username"],
                        $user["lastname"],
                        $user["firstname"],
                        $user["mail"],
                        $user["address"],
                        password_hash($user["password"], PASSWORD_DEFAULT),
                        $user["pse"]
                    ]
                );
                require "utils/notify.php";
                notify(null, "register", true, "Un nouvel utilisateur s'est inscrit : " . $user["firstname"] . " " . $user["lastname"], "Inscription d'un Utilisateur");
                $rep["success"] = true;
                $rep["message"] = "L'utilisateur a bien été inscrit. Vous pouvez à présent vous connecter !";
                $rep["data"]["redirect"] = "login";

                //$rep["data"] = $user;


            } else {
                $rep["success"] = false;
                $rep["message"] = "L'utilisateur existe déjà";

                //$rep["data"] = $user;
            }
        } else {
            $rep["message"] = "les mots de passe ne correspondent pas.";
        }

    }


    return $rep;
}


function user_dont_exists($mail, $username)
{
    global $db;
    $check = $db->prepare("SELECT id_user FROM users WHERE mail = ? OR username = ?");
    $check->execute([$mail, $username]);

    $rep = $check->fetchAll();

    return count($rep) == 0;
}
