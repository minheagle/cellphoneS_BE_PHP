<?php
require '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

require "../../config/database.php";
require "../../models/Series.php";
$database = new Database();
$db = $database->getConnection();

$seriess = new Series($db);



$brandId = $_GET['brandId'];
if ($brandId != null) {
    $stmt = $seriess->getSeriesByBrandId($brandId);
    $count = $stmt->rowCount();
    if ($count > 0) {
        $seriesList = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $series = array(
                'id' => $id,
                'seriesName' => $seriesName,
                'brandId' => $brandId
            );
            array_push($seriesList, $series);
        }
        http_response_code(200);
        echo json_encode($seriesList);
    } else {
        http_response_code(200);
        $res = array(
            "message" => "Không có danh mục"
        );
        echo json_encode($res);
    }
} else {
    http_response_code(404);
    $res = array(
        "message" => "Không có danh mục"
    );
    echo json_encode($res);
}
