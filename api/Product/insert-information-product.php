<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $requestData = json_decode(file_get_contents("php://input"));
    if (!empty($requestData->productId) && !empty($requestData->listSalientFeature) && !empty($requestData->listShortDescription) && !empty($requestData->listTechnicalSpecs) && !empty($requestData->description)) {
        include_once '../../config/database.php';
        $database = new Database();
        $db = $database->getConnection();

        try {
            $listSalientFeature = $requestData->listSalientFeature;

            foreach ($listSalientFeature as $item) {
                include_once '../../models/SalientFeatures.php';
                $salientFeature = new SalientFeatures($db);

                $salientFeature->productId = $requestData->productId;
                $salientFeature->featureName = $item;

                $salientFeature->createSalientFeature();
            }

            $listShortDescription = $requestData->listShortDescription;

            foreach ($listShortDescription as $item) {
                include_once '../../models/ShortDescription.php';
                $shortDescription = new ShortDescription($db);

                $shortDescription->productId = $requestData->productId;
                $shortDescription->content = $item;

                $shortDescription->createShortDescription();
            }

            $listTechnicalSpecs = $requestData->listTechnicalSpecs;

            foreach ($listTechnicalSpecs as $item) {
                include_once '../../models/TechnicalSpecs.php';
                $technicalSpecs = new TechnicalSpecs($db);

                $technicalSpecs->productId = $requestData->productId;
                $technicalSpecs->nameSpecs = $item->key;
                $technicalSpecs->valueSpecs = $item->value;

                $technicalSpecs->createTechnicalSpecs();
            }

            $description = $requestData->description;


            include_once '../../models/Description.php';
            $description = new Description($db);

            $description->productId = $requestData->productId;
            $description->description = $requestData->description;

            $description->createDescription();


            http_response_code(200);
            $res = array(
                'message' => 'Upload thành công'
            );
            echo json_encode($res);
        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode($e->getMessage());
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
