<?php

    function getPage($data, $token) {
        $rep = array("success" => false, "message" => "impossible de récupérer la page demandée", "data" => null);
        $asked_page = $data["page"];
        $url = "data/pages/".$asked_page.".html";
        if(file_exists($url)) {
            if (strpos($asked_page, "admin") !== false && $asked_page != "admin_login") {
                require "auth/logged.php";
                require "admin/admin.php";
                $uid = getUser($token);
                if ($uid == false || !isAdmin($uid)) {
                    $rep["success"] = false;
                    $rep["message"] = "Vous devez être administrateur pour effectuer cette action.";
                    $rep["data"] = null;
                    return $rep;
                }
            } else if ($asked_page == "send_alert") {
                require "auth/profile.php";
                $uid = getUser($token);
                if ($uid == false || getProfile($uid)["data"]["validated"] !=1) {
                    //error_log(getProfile($uid)["data"]["validated"]);
                    $rep["success"] = false;
                    $rep["message"] = "Votre compte doit être validé pour afficher cette page !";
                    $rep["data"] = null;
                    return $rep;
                }
            }
            $page_data = file_get_contents($url);
            
            $rep["data"]["page"] = $page_data;
            $rep["success"] = true;
            $rep["message"] = "Page récupérée avec succès !";

        } else {
            $rep["message"] = "La page demandée n'existe pas.";
        }
        return $rep;
    }


?>