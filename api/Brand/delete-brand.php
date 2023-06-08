<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
    require_once '../../config/database.php';
    require_once '../../models/Brands.php';
    $database = new Database();
    $db = $database->getConnection();

    $brand = new Brands($db);

    $requestData = json_decode(file_get_contents("php://input"));
    if (!empty($requestData->id)) {
        $brand->id = $requestData->id;
        if ($brand->deleteBrand()) {
            http_response_code(200);
            $res = array(
                'message' => 'Xóa thương hiệu thành công'
            );
            echo json_encode($res);
        } else {
            http_response_code(404);
            $res = array(
                'message' => 'Xóa thương hiệu không thành công'
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
