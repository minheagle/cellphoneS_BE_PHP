<?php
require '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

require "../../config/database.php";
require "../../models/Brands.php";
$database = new Database();
$db = $database->getConnection();

$brands = new Brands($db);



$categoryId = $_GET['categoryId'];
if($categoryId != null){
    $stmt = $brands->getBrandByCategory($categoryId);
    $count = $stmt->rowCount();
    if($count > 0){
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
    }else{
        http_response_code(200);
        $res = array(
                "message" => "Không có danh mục"
            );
        echo json_encode($res);
    }
} else{
    http_response_code(404);
    $res = array(
        "message" => "Không có danh mục"
    );
    echo json_encode($res);
}
