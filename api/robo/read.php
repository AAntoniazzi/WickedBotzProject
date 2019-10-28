<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
require_once '../config/database.php';
require_once '../objects/robo.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$robo = new Robo($db);
 
// read products will be here
$robos = array();

if (isset($_GET['id'])) {
    $robo->id  = $_GET['id'];
}

if (isset($_GET['equipeid'])) {
    $robo->equipeid  = $_GET['equipeid'];
}

$robos['records'] = $robo->read();

if (count($robos) > 0) {
    http_response_code(200);
    echo json_encode($robos);
} else {
    http_response_code(404);

    echo json_encode(
        array("message" => "Nenhum robo encontrado.")
    );
}
