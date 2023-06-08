<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

if ($_SERVER['REQUEST_METHOD'] === "PUT") {
    require_once '../../config/database.php';
    require_once '../../models/Brands.php';

    $database = new Database();
    $db = $database->getConnection();

    $brand = new Brands($db);

    $requestData = json_decode(file_get_contents("php://input"));

    if (!empty($requestData->id)) {
        if (!empty($requestData->brandName) && !empty($requestData->categoryId)) {
            $brand->id = $requestData->id;
            $brand->brandName = $requestData->brandName;
            $brand->categoryId = $requestData->categoryId;
            if (!$brand->checkBrandExist()) {
                if ($brand->updateBrand()) {
                    http_response_code(200);
                    $res = array(
                        'message' => 'Cập nhật thương hiệu thành công'
                    );
                    echo json_encode($res);
                } else {
                    html_entity_decode(404);
                    $res = array(
                        'message' => 'Cập nhật thương hiệu không thành công'
                    );
                    echo json_encode($res);
                }
            } else {
                http_response_code(404);
                $res = array(
                    'message' => 'Thương hiệu đã tồn tại'
                );
                echo json_encode($res);
            }
        } else {
            http_response_code(404);
            $res = array(
                'message' => 'Các mục không được để trống'
            );
            echo json_encode($res);
        }
    } else {
        http_response_code(404);
        $res = array(
            'message' => 'Thương hiệu không tồn tại'
        );
        echo json_encode($res);
    }
} else {
    http_response_code(503);
    $res = array(
        'message' => 'Phương thức không hợp lệ'
    );
    echo json_encode($res);
}
