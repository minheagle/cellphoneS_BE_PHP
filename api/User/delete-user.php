<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

include_once("../../config/database.php");
include_once("../../models/Users.php");

$db = new Database();

$connection = $db->getConnection();

$user = new Users($connection);

if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
    $id = $_GET["id"];

    $user->id = $id;

    if ($user->id != null) {
        if ($user->deleteUser()) {
            http_response_code(200);
            echo json_encode(array(
                "message" => "User already delete."
            ));
        } else {
            http_response_code(500);
            echo json_encode(array(
                "message" => "Failed to delete user"
            ));
        }
    } else {
        http_response_code(404);
        echo json_encode(array(
            "message" => "User not found."
        ));
    }
} else {
    http_response_code(503);
    echo json_encode(array(
        "message" => "Access Denied"
    ));
}
