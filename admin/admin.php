<?php


    function admin($data, $token) {
        // il faut d'abord vérifier si l'utilisateur est administrateur
        $rep = array("success" => false, "message" => "une erreur est survenue", "data" => null);
        require "auth/logged.php";
        $uid = getUser($token);
        if ($uid !== false) {
            if (isAdmin($uid)) {
                switch($data["request"]) {
                    case "accept_alert":
                        $rep = acceptAlert($data["alert_id"], $data["type"]);
                    break;

                    case "remove_alert":
                        $rep["success"] = removeAlert($data["alert_id"]);
                    break;

                    case "accept_user":
                        $rep["success"] = acceptUser($data["user_id"]);
                    break;

                    case "delete_user":
                        $rep["success"] = removeUser($data["user_id"]);
                    break;

                    case "promote":
                        $rep["success"] = promote($data["user_id"]);
                    break;

                    case "destitute":
                        $rep["success"] = destitute($data["user_id"]);
                    break;

                    case "list_users":
                        $rep = listUsers();
                    break;

                    case "list_alerts":
                        $rep = listAlerts();
                    break;

                    case "list_atype":
                        $rep = getTypeAlert();
                    break;
                    
                    default:
                        $rep["message"] = "Commande non trouvée";
                    break;
                }
                if ($rep["success"] == true) {
                    $rep["message"] = "Commande exécutée avec succès !";
                }
            } else {
                $rep["message"] = "Vous devez être Administrateur pour effectuer cette action";
            }
        } else {
            $rep["message"] = "Vous devez être connecté pour effectuer cette action";
        }
        return $rep;

    }

    function isAdmin($uid) {
        global $db;
        try {
            $req = $db->prepare("SELECT type_user FROM users WHERE id_user = ?");
            $req->execute([$uid]);
            $data = $req->fetchAll();

            if ($data[0]["type_user"] == 2) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    function acceptAlert($alert_id, $type_alert) {
        global $db;
        try {
            $request = $db->prepare("UPDATE alerts SET validated = 1, type_alert=? WHERE id_alert = ?");
            $request->execute([$type_alert, $alert_id]);

            require "utilz/notify.php";
            

            return notify($alert_id, "notifs");
        } catch (Exception $e) {
            return ["success" => false, "message" => "Une erreur est survenue : ".$e->getMessage()];
        }
    }

    function removeAlert($alert_id) {
        global $db;
        try {
            // supression de l'image
            $req1 = $db->prepare("SELECT pic FROM alerts WHERE id_alert = ?");
            $req1->execute([$alert_id]);
            $d1 = $req1->fetchAll();
            if (!is_null($d1[0]["pic"]) && strpos($d1[0]["pic"], "http") === false) {
                $urlp = substr($d1[0]["pic"],1);
                unlink($urlp);
            }
            // supression de l'alerte
            $request = $db->prepare("DELETE FROM alerts WHERE id_alert = ?");
            $request->execute([$alert_id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    function acceptUser($user_id) {
        global $db;
        try {
            $request = $db->prepare("UPDATE users SET valide = 1 WHERE id_user = ?");
            $request->execute([$user_id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    function removeUser($user_id) {
        global $db;
        try {
            $request = $db->prepare("DELETE FROM users WHERE id_user = ?");
            $request->execute([$user_id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    function promote($user_id) {
        global $db;
        try {
            $request = $db->prepare("UPDATE users SET type_user = 2 WHERE id_user = ?");
            $request->execute([$user_id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    function destitute($user_id) {
        global $db;
        try {
            $request = $db->prepare("UPDATE users SET type_user = 1 WHERE id_user = ?");
            $request->execute([$user_id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    function getTypeAlert() {
        global $db;
        try {
            $request = $db->prepare("SELECT * FROM type_alert");
            $request->execute();
            $data = $request->fetchAll();

            $types = [];
            foreach($data as $typeA) {
                $toadd = [
                    "label" => $typeA["label"],
                    "id" => intval($typeA["id_type"])
                ];
                array_push($types, $toadd);
            }

            return ["success"=> true, "message" => "type récupérés avec succès", "data" => $types];
        } catch(Exception $e) {
            return ["success"=>false, "message" => "Une erreur est survenue avec la BDD" . $e->getMessage()];
        }
    }

    function listUsers() {
        global $db;
        try {
            $request = $db->prepare("SELECT * FROM users JOIN type_user ON users.type_user = type_user.id_type ORDER BY id_user DESC");
            $request->execute();
            $data = $request->fetchAll();

            $users = [];
            foreach($data as $user) {
                $topush = [
                    "id_user" => intval($user["id_user"]),
                    "username" => $user["username"],
                    "lastname" => $user["lastname"],
                    "firstname" => $user["firstname"],
                    "pse" => $user["pse"],
                    "mail" => $user["mail"],
                    "address" => $user["address"],
                    "type_user" => intval($user["type_user"]),
                    "valide" => intval($user["valide"]),
                    "label_utype" => $user["label"]
                ];
                array_push($users, $topush);
            }
                $resp = [
                    "success" => true,
                    "message" => "Utilisateurs récupérés avec succès",
                    "data" => $users
                ];
                return $resp;
            }


        catch (Exception $e) {
            $data = [
                "success" => false,
                "message" => "Une erreur est survenue lors de l'accès à la base de données : ".$e->getMessage()
            ];
            return $data;
        }
    }



?>