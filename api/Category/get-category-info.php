<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

require_once '../../config/database.php';
require_once '../../models/Categories.php';
$database = new Database();
$db = $database->getConnection();

$category = new Categories($db);

$category->id = isset($_GET['id']) ? $_GET['id'] : die();

if ($category->getCategoryById()) {
    http_response_code(200);
    $categoryInfo = array(
        'id' => $category->id,
        'categoryName' => $category->categoryName
    );
    echo json_encode($categoryInfo);
} else {
    http_response_code(404);
    $res = array(
        'message' => 'Danh mục không tồn tại'
    );
    echo json_encode($res);
}
