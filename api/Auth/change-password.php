<?php
require '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

require '../../config/database.php';
require '../../models/Users.php';
$database = new Database();
$db = $database->getConnection();

$user = new Users($db);

$requestData = json_decode(file_get_contents("php://input"));

if (!empty($requestData->id) && !empty($requestData->currentPassword) && !empty($requestData->newPassword)) {
    $user->id = $requestData->id;
    $user->getUserById();

    if (password_verify($requestData->currentPassword, $user->userPassword)) {
        $newPassword = password_hash($requestData->newPassword, PASSWORD_DEFAULT);

        if ($user->changePassword($newPassword)) {
            http_response_code(200);
            $res = array(
                "message" => "Thay đổi mật khẩu thành công"
            );
            echo json_encode($res);
        } else {
            http_response_code(500);
            $res = array(
                "message" => "Thay đổi mật khẩu không thành công"
            );
            echo json_encode($res);
        }
    } else {
        http_response_code(500);
        $res = array(
            "message" => "Mật khẩu hiện tại không chính xác"
        );
        echo json_encode($res);
    }
} else {
    http_response_code(500);
    $res = array(
        "message" => "Không được để trống"
    );
    echo json_encode($res);
}
