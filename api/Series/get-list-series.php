<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    include_once '../../config/database.php';
    include_once '../../models/Series.php';

    $database = new Database();
    $db = $database->getConnection();

    $series = new Series($db);
    $stmt = $series->getListSeries();
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
        $seriesList = array();
        echo json_encode($seriesList);
    }
} else {
    http_response_code(503);
    $res = array(
        'message' => 'Phương thức không hợp lệ'
    );
    echo json_encode($res);
}
