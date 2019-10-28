<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
require_once '../config/database.php';
require_once '../objects/categoria.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$categoria = new Categoria($db);
 
// read products will be here
$categorias = array();

if (isset($_GET['id'])) {
    $categoria->id  = $_GET['id'];
}

$categorias['records'] = $categoria->read();

if (count($categorias) > 0) {
    http_response_code(200);
    echo json_encode($categorias);
} else {
    http_response_code(404);

    echo json_encode(
        array("message" => "Nenhuma categoria encontrada.")
    );
}
