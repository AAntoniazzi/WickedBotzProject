<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
require_once '../config/database.php';
require_once '../objects/equipe.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$equipe = new Equipe($db);
 
// read products will be here
$equipes = array();

if (isset($_GET['id'])) {
    $equipe->id  = $_GET['id'];
}

$equipes['records'] = $equipe->read();

if (count($equipes) > 0) {
    http_response_code(200);
    echo json_encode($equipes);
} else {
    http_response_code(404);

    echo json_encode(
        array("message" => "Nenhuma equipe encontrada.")
    );
}
