<?php

header("Access-Control-Allor-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//incluir banco e arquivo do objeto
require_once '../config/database.php';
require_once '../objects/arma.php';

//instanciar banco 
$database = new Database();
$db = $database->getConnection();

//instanciar objeto
$arma = new Arma($db);

//read arma
$armas = array();

if (isset($_GET['id'])) {
    $arma->id = $_GET['id'];
}

$armas['records'] = $arma->read();

if (count($armas) > 0) {
    http_response_code(200);
    echo json_encode($armas);
} else {
    http_response_code(404);

    echo json_encode(
        array("message" => "Nenhuma arma encontrada.")
    );
}



