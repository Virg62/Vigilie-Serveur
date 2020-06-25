<?php
require "auth/logged.php";
use ReallySimpleJWT\Token;

function profile($data, $token) {
    $uid = getUser($token);
    $rep = array("success" => false, "message" => "une erreur est survenue", "data" => null);
    if ($uid !== false) {
        $profile_request = $data["request"];
        switch($profile_request) {
            case "getProfile":
                $rep = getProfile($uid);
            break;

            case "edit":
                $rep = editProfile($uid, $data["key"], $data["value"], $data);
            break;

            default:
                $rep["message"] = "La commande profil n'a pas été trouvée";
            break;
        }
    } else {
        $rep["message"] = "L'utilisateur n'est pas connecté";
    }
    return $rep;
}

function editProfile($uid, $key, $value, $data) {
    global $db;
    $rep = array("success" => false, "message" => "impossible de modifier le profil.", "data" => null);
    try {
        if ($key === "password") {
            // il faut valider le mot de passe
            // $value est le mot de passe actuel
            // $data["new_pswd"] est le nouveau mot de passe
            // $data["new_pswd_conf"] est la confirmation du nouveau mot de passe.
            $request1 = $db->prepare("SELECT password FROM users WHERE id_user = ?");
            $request1->execute([$uid]);
            $resp1 = $request1->fetchAll();
            $password = $resp1[0]["password"];
            if(password_verify($value, $password)) {
                $new_pswd = $data["new_pswd"];
                $new_pswd_conf = $data["new_pswd_conf"];
                if ($new_pswd === $new_pswd_conf) {

                    $password_hashed = password_hash($new_pswd, PASSWORD_DEFAULT);

                    $request2 = $db->prepare("UPDATE users SET password = ? WHERE id_user = ?");
                    $request2->execute([$password_hashed, $uid]);
                    
                    $rep["message"] = "Le mot de passe a bien été changé !";
                    $rep["success"] = true;
                } else {
                    $rep["message"] = "Les deux mot de passes ne correspondent pas.";
                }
            } else {
                $rep["message"] = "Le mot de passe actuel est incorrect.";
            }
        } else {
            $available_choices = ["username","lastname","firstname","address","pse"];
            if(in_array($key, $available_choices)) {

                $request = $db->prepare("UPDATE users SET $key = ? WHERE id_user = ?");
                $request->execute([$value, $uid]);
                $rep["success"] = true; $rep["message"] = "La valeur à bien été changée";               
            } else {
                $rep["message"] = "La valeur indiquée ne peut être changée ou n'existe pas.";
            }
        }
    } catch(Exception $e) {
        $rep["message"] = "Echec de la modification du compte : ".$e;
    }


    return $rep;
}

function getProfile($uid) {
    global $db;
    $rep = array("success" => false, "message" => "echec de la récupération du profil", "data" => null);
    try {
        $request = $db->prepare("SELECT * FROM users u JOIN type_user tu ON u.type_user=tu.id_type WHERE id_user = ?");
        $request->execute(array($uid));
        $data = $request->fetchAll();
        $data = $data[0];
        
        $profile = [
            "id" => intval($data["id_user"]),
            "username" => $data["username"],
            "lastname" => $data["lastname"],
            "firstname" => $data["firstname"],
            "mail" => $data["mail"],
            "address" => $data["address"],
            "validated" => intval($data["valide"]),
            "pse" => $data["pse"],
            "type" => $data["label"]
        ];
        
        $rep["success"] = true; $rep["message"] = "Le profil a été récupéré avec succès !"; $rep["data"] = $profile;

    } catch (Exception $e) {
        $rep["message"] = "Une erreur est survenue avec la base de données : ".$e->getMessage();
    }
    return $rep;
}


?>