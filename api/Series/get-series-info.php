<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

require_once '../../config/database.php';
require_once '../../models/Series.php';

$database = new Database();
$db = $database->getConnection();

$series = new Series($db);

$series->id = isset($_GET['id']) ? $_GET['id'] : die();

if ($series->getSeriesById()) {
    http_response_code(200);
    $seriesInfo = array(
        'id' => $series->id,
        'seriesName' => $series->seriesName,
        'brandId' => $series->brandId
    );
    echo json_encode($seriesInfo);
} else {
    http_response_code(404);
    $res = array(
        'message' => 'Series không tồn tại'
    );
    echo json_encode($res);
}
