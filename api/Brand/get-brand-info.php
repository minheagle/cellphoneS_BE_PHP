<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

require_once '../../config/database.php';
require_once '../../models/Brands.php';

$database = new Database();
$db = $database->getConnection();

$brand = new Brands($db);

$brand->id = isset($_GET['id']) ? $_GET['id'] : die();

if ($brand->getBrandById()) {
    http_response_code(200);
    $brandInfo = array(
        'id' => $brand->id,
        'brandName' => $brand->brandName,
        'categoryId' => $brand->categoryId
    );
    echo json_encode($brandInfo);
} else {
    http_response_code(404);
    $res = array(
        'message' => 'Thương hiệu không tồn tại'
    );
    echo json_encode($res);
}
