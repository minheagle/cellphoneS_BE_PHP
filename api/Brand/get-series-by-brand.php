<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    include_once '../../config/database.php';
    require_once '../../models/Series.php';
    include_once '../../models/Brands.php';

    $database = new Database();
    $db = $database->getConnection();

    $brand = new Brands($db);

    $brand->id = isset($_GET['id']) ? $_GET['id'] : die();

    if ($brand->getBrandById()) {

        $series = new Series($db);
        $stmt = $series->getSeriesByBrand($brand->id);
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
            $res = array(
                'seriesList' => $seriesList
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
