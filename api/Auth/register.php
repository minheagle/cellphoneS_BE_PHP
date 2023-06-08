<?php
require_once("../../helper/CORS/CORS.php");

$cors = new CORS();
$cors->cors();

include("../../config/database.php");
include("../../models/Users.php");

$database = new Database();
$db = $database->getConnection();

$user = new Users($db);


$data = json_decode(file_get_contents("php://input"));
if (!empty($data->phone) && !empty($data->email) && !empty($data->fullName) && !empty($data->userPassword)) {
    $user->phone = $data->phone;
    $user->email = $data->email;
    $user->fullName = $data->fullName;
    $user->userPassword = password_hash($data->userPassword, PASSWORD_DEFAULT);

    if ($user->checkEmail()) {
        http_response_code(404);
        echo json_encode(array(
            "message" => "Người dùng đã tồn tại"
        ));
    } else {
        if ($user->createUser()) {
            http_response_code(201);
            echo json_encode(array(
                "message" => "Tạo người dùng thành công"
            ));
        } else {
            // http_response_code(500);
            echo json_encode(array(
                "message" => "Tạo người dùng không thành công"
            ));
        }
    }
} else {
    http_response_code(500);
    echo json_encode(array(
        "message" => "Không được để trống"
    ));
}
