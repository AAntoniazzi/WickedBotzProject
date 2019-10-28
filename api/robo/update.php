<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
require_once '../config/database.php';
require_once '../objects/robo.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$robo = new Robo($db);
 
// get posted data
parse_str(file_get_contents("php://input"), $put_str);
$data = json_decode(json_encode($put_str));

switch (json_last_error()) {
    case JSON_ERROR_NONE:
        $error =  'No errors';
    break;
    case JSON_ERROR_DEPTH:
        $error = 'Maximum stack depth exceeded';
    break;
    case JSON_ERROR_STATE_MISMATCH:
        $error = 'Underflow or the modes mismatch';
    break;
    case JSON_ERROR_CTRL_CHAR:
        $error =   'Unexpected control character found';
    break;
    case JSON_ERROR_SYNTAX:
        $error =  'Syntax error, malformed JSON';
    break;
    case JSON_ERROR_UTF8:
        $error =  'Malformed UTF-8 characters, possibly incorrectly encoded';
    break;
    default:
        $error =   'Unknown error';
    break;
}

if (!empty($data->roboid)) {
    $robo->id = $data->roboid; 
} else {
    // set response code - 400 Bad Request
    http_response_code(400);
 
    // tell the user
    echo json_encode(array("message" => "Necessário enviar roboid.", "error" => $error, "put_str" => $put_str, "data" => $data));

    exit();
}

$robo->categoriaid = $data->categoria;
$robo->armaid = $data->arma;
$robo->qtdarmas = $data->qtdarma;
$robo->qtdroda = $data->qtdroda;
$robo->qtdmotor = $data->qtdmotor;
$robo->tipomotor = $data->motor;
$robo->tiporoda = $data->roda;
$robo->carenagem = $data->carenagem;

// update the product
try {
    $robo->update($data->email);

    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("message" => "Robô atualizado."));
} catch (Exception $e) {
    // set response code - 500 internal server error
    http_response_code(500);

    // tell the user
    echo json_encode(array("message" => var_dump($e)));
}

?>