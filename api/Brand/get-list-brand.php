<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    include_once '../../config/database.php';
    include_once '../../models/Brands.php';

    $database = new Database();
    $db = $database->getConnection();

    $brand = new Brands($db);
    $stmt = $brand->getListBrand();
    $count = $stmt->rowCount();

    if ($count > 0) {
        $brandList = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $brand = array(
                'id' => $id,
                'brandName' => $brandName,
                'categoryId' => $categoryId
            );
            array_push($brandList, $brand);
        }
        http_response_code(200);

        echo json_encode($brandList);
    } else {
        http_response_code(200);
        $brandList = array();
        echo json_encode($brandList);
    }
} else {
    http_response_code(503);
    $res = array(
        'message' => 'Phương thức không hợp lệ'
    );
    echo json_encode($res);
}
