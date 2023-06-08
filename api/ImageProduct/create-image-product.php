<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $requestData = json_decode(file_get_contents("php://input"));
    if (!empty($requestData->productId) && !empty($requestData->listImage)) {
        include_once '../../config/database.php';
        include '../../helper/HandleImageBase64/HandleImageBase64.php';
        $database = new Database();
        $db = $database->getConnection();

        $handleImage = new HandleImageBase64();


        $imageList = $requestData->listImage;
        // Validate list image
        $isCheck = true;
        foreach ($imageList as $item) {
            $imageString = $item->url;
            $imageType = $item->type;
            if (!$handleImage->checkTypeImage($imageType) && !$handleImage->checkSizeImage($imageString)) {
                $isCheck = false;
                break;
            }
        }

        if ($isCheck) {
            foreach ($imageList as $item) {
                include_once '../../models/Images.php';
                $image = new Images($db);

                $image->productId = $requestData->productId;
                $image->imageName = $item->name;
                $image->imageThumbUrl = $item->thumbUrl;
                $image->imageType = $item->type;
                $image->imageUrl = $item->url;

                $image->createImageProduct();
            }

            http_response_code(200);
            $res = array(
                'message' => 'Upload ảnh thành công'
            );
            echo json_encode($res);
        } else {
            http_response_code(404);
            $res = array(
                'message' => 'Ảnh không hợp lệ'
            );
            echo json_encode($res);
        }
    } else {
        http_response_code(404);
        $res = array(
            'message' => 'Không được để trống'
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
