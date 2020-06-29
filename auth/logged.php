<?php

use ReallySimpleJWT\Token;

function logged($data)
{
    $rep = ["success" => false, "message" => "une erreur est survenue", "data" => null];

    $secret = "sec!ReT423*&";
    $token = $data;
    if (Token::validate($token, $secret)) {
        $rep["success"] = true;
        $rep["message"] = "L'utilisateur est connecté";

        $rep['data'] = [Token::getHeader($token, $secret), Token::getPayload($token, $secret)];
    } else {
        $rep["success"] = true;
        $rep["message"] = "L'utilisateur n'est pas connecté";
    }
    return $rep;
}

function getUser($token)
{
    $secret = "sec!ReT423*&";
    if (Token::validate($token, $secret)) {
        return Token::getPayload($token, $secret)["user_id"];
    } else {
        return false;
    }
}
