<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

if ($_SERVER['REQUEST_METHOD'] === "PUT") {
    require_once '../../config/database.php';
    require_once '../../models/Series.php';

    $database = new Database();
    $db = $database->getConnection();

    $series = new Series($db);

    $requestData = json_decode(file_get_contents("php://input"));

    if (!empty($requestData->id)) {
        if (!empty($requestData->seriesName) && !empty($requestData->brandId)) {
            $series->id = $requestData->id;
            $series->seriesName = $requestData->seriesName;
            $series->brandId = $requestData->brandId;
            if (!$series->checkSeriesExist()) {
                if ($series->updateSeries()) {
                    http_response_code(200);
                    $res = array(
                        'message' => 'Cập nhật series thành công'
                    );
                    echo json_encode($res);
                } else {
                    html_entity_decode(404);
                    $res = array(
                        'message' => 'Cập nhật series không thành công'
                    );
                    echo json_encode($res);
                }
            } else {
                http_response_code(404);
                $res = array(
                    'message' => 'Series đã tồn tại'
                );
                echo json_encode($res);
            }
        } else {
            http_response_code(404);
            $res = array(
                'message' => 'Các mục không được để trống'
            );
            echo json_encode($res);
        }
    } else {
        http_response_code(404);
        $res = array(
            'message' => 'Series không tồn tại'
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
