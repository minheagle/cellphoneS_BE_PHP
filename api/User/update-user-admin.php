<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

include_once("../../config/database.php");
include_once("../../models/Users.php");

$db = new Database();

$connection = $db->getConnection();

$user = new Users($connection);

if ($_SERVER['REQUEST_METHOD'] === "PUT") {
    $data = json_decode(file_get_contents("php://input"));
    if (!empty($data->id) && !empty($data->phone) && !empty($data->email) && !empty($data->fullName) && !empty($data->userAddress)) {

        $user->id = $data->id;
        $user->phone = $data->phone;
        $user->email = $data->email;
        $user->fullName = $data->fullName;
        // $user->userPassword = password_hash($data->userPassword, PASSWORD_DEFAULT);
        $user->userAddress = $data->userAddress;
        // $user->avatar = $data->avatar || null;
        // $user->userRole = $data->userRole;
        // $user->rememberToken = $data->$rememberToken || null;

        if ($user->updateUserForAdmin()) {
            http_response_code(200);
            echo json_encode(array(
                "status" => 1,
                "message" => "Cập nhật thành công"
            ));
        } else {
            http_response_code(500);
            echo json_encode(array(
                "status" => 0,
                "message" => "Cập nhật không thành công"
            ));
        }
    } else {
        http_response_code(500);
        echo json_encode(array(
            "status" => 0,
            "message" => "Không được để trống"
        ));
    }
} else {
    http_response_code(503);
    echo json_encode(array(
        "status" => 0,
        "message" => "Phương thức không được hỗ trợ"
    ));
}
