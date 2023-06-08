<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    include_once '../../config/database.php';
    include_once '../../models/Categories.php';

    $database = new Database();
    $db = $database->getConnection();

    $categories = new Categories($db);
    $stmt = $categories->getListCategory();
    $count = $stmt->rowCount();

    if ($count > 0) {
        $categoryList = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $category = array(
                'id' => $id,
                'categoryName' => $categoryName,
                'path' => $path
            );
            array_push($categoryList, $category);
        }
        http_response_code(200);
        echo json_encode($categoryList);
    } else {
        http_response_code(404);
        $categoryList = array();
        echo json_encode($categoryList);
    }
} else {
    http_response_code(503);
    $res = array(
        'message' => 'Phương thức không hợp lệ'
    );
    echo json_encode($res);
}
