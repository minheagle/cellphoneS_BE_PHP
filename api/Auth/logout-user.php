<?php
require_once("../../helper/CORS/CORS.php");

$cors = new CORS();
$cors->cors();

include("../../config/database.php");
include("../../models/Users.php");

$database = new Database();
$db = $database->getConnection();

$user = new Users($db);

$requestData = json_decode(file_get_contents("php://input"));

if (!empty($requestData->id)) {
    $user->id = $requestData->id;
    $user->refreshToken = null;
    if ($user->deleteJWT()) {
        http_response_code(200);
        echo json_encode(array(
            "message" => "Succes"
        ));
    } else {
        http_response_code(500);
        echo json_encode(array(
            "message" => "Fail"
        ));
    }
} else {
    http_response_code(500);
    echo json_encode(array(
        "message" => "Not id"
    ));
}
