<?php

 header('Content-Type: Application/json');

 if(!isset($_GET['address'])){
    echo json_encode(["Error" => "No Address Provided"]);
    exit;
 }
 $address = urlencode($_GET['address']);

 $urlAPI = "http://nominatim.openstreetmap.org/search?q={$address}&format=json";
 $options = [
    'http' => [
        'header' => "User-Agent: CommunityConnect/1.0\r\n"
    ]
 ];
 $context = stream_context_create($options);
 $reponse = file_get_contents($urlAPI, false, $context);
 echo $reponse;
?>