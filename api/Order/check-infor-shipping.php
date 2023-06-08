<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

require("../../config/database.php");
require("../../models/Users.php");
require("../../models/Guests.php");

$database = new Database();
$db = $database->getConnection();

$requestData = json_decode(file_get_contents("php://input"));

if ($requestData->userId) {
    if (!empty($requestData->fullName) && !empty($requestData->phone) && !empty($requestData->address)) {
        $user = new Users($db);

        $user->id = $requestData->userId;
        $user->fullName = $requestData->fullName;
        $user->phone = $requestData->phone;
        $user->userAddress = $requestData->address;
        $user->email = $requestData->email;

        if ($user->updateInfoShipping()) {
            http_response_code(200);
            $res = array(
                'message' => 'Cập nhật địa chỉ giao hàng thành công'
            );
            echo json_encode($res);
        } else {
            http_response_code(404);
            $res = array(
                'message' => 'Cập nhật không thành công'
            );
            echo json_encode($res);
        }
    } else {
        http_response_code(404);
        $res = array(
            'message' => 'Không được để trống'
        );
        echo json_encode($res);
    }
} else {
    if (!empty($requestData->fullName) && !empty($requestData->phone) && !empty($requestData->address)) {
        $guest = new Guest($db);

        $guest->fullName = $requestData->fullName;
        $guest->phone = $requestData->phone;
        $guest->address = $requestData->address;
        $guest->email = $requestData->email;


        $lastId = $guest->createGuest();

        if ($lastId != null) {
            http_response_code(200);
            $res = array(
                'id' => $lastId,
                'message' => 'Cập nhật địa chỉ giao hàng thành công'
            );
            echo json_encode($res);
        } else {
            http_response_code(404);
            $res = array(
                'message' => 'Tạo khách hàng không thành công'
            );
            echo json_encode($res);
        }
    } else {
        http_response_code(404);
        $res = array(
            'message' => 'Không được để trống'
        );
        echo json_encode($res);
    }
}
