<?php
function notify($id, $channel, $custom=false, $body=null, $type=null) {
    if (!$custom) {
        global $db;
        // vérification si le type nécessite notification.
        $req = $db->prepare("SELECT title, notify, label FROM alerts JOIN type_alert ON alerts.type_alert = type_alert.id_type WHERE id_alert = ?");
        $req->execute([$id]);
        $data = $req->fetchAll();
        $adata = $data[0];
        if($adata["notify"] != 1) {
            return ["success" => true, "message" => "pas besoin d'envoyer de notifs", "msg"=> false, "data" => null];
        }
        $body = $adata["title"];
        $type = $adata["label"];
    }
    
    // envoi des données au serveur firebase
    define( 'API_ACCESS_KEY', 'VOTRE CLÉ FIREBASE' );
    $msg = array
    (
        'body'  => $body,
        'title'     => "Vigilie - " . $type,
        'vibrate'   => 1,
        'sound'     => 1,
    );

    $fields = array
    (
        'to' => "/topics"."/" . $channel,
        'notification'=> $msg
    );

    $headers = array
    (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $result = curl_exec($ch );
    curl_close( $ch );
    
    try {
        $d = json_decode($result, true);
        return ["success" => true, "message" => "envoi réussi avec succès", "msg"=> true, "messid" => $d["message_id"], "data" => null];
    } catch(Exception $e) {
        return ["success" => false, "message" => "erreur envoi notif" . $e->getMessage(), "msg"=> false, "data" => null];
    }

}
?>