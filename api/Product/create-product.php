<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $requestData = json_decode(file_get_contents("php://input"));
    if (!empty($requestData->productName) && !empty($requestData->standardCost) && !empty($requestData->seriesId) && !empty($requestData->categoryId) && !empty($requestData->brandId) && !empty($requestData->quantity)) {

        include_once '../../config/database.php';
        include_once '../../models/Products.php';
        $database = new Database();
        $db = $database->getConnection();

        $product = new Products($db);

        $product->categoryId = $requestData->categoryId;
        $product->brandId = $requestData->brandId;
        $product->seriesId = $requestData->seriesId;
        $product->productName = $requestData->productName;
        $product->standardCost = $requestData->standardCost;
        $product->quantity = $requestData->quantity;
        $product->isNew = $requestData->isNew || 0;
        $lastId = $product->createProduct();

        if ($lastId != null) {
            http_response_code(200);
            $res = array(
                'id' => $lastId
            );
            echo json_encode($res);
        } else {
            http_response_code(404);
            $res = array(
                'message' => 'Tạo sản phẩm không thành công'
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
    http_response_code(503);
    $res = array(
        'message' => 'Phương thức không hợp lệ'
    );
    echo json_encode($res);
}
