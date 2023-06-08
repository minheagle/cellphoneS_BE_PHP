<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    include_once '../../config/database.php';
    require_once '../../models/Categories.php';
    include_once '../../models/Brands.php';

    $database = new Database();
    $db = $database->getConnection();

    $category = new Categories($db);

    $category->id = isset($_GET['id']) ? $_GET['id'] : die();

    if ($category->getCategoryById()) {

        $brand = new Brands($db);
        $stmt = $brand->getBrandByCategory($category->id);
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
            $res = array(
                'brandList' => $brandList
            );
            echo json_encode($res);
        } else {
            http_response_code(404);
            $res = array(
                'message' => 'No data'
            );
            echo json_encode($res);
        }
    } else {
        http_response_code(404);
        $res = array(
            'message' => 'Danh mục không tồn tại'
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
