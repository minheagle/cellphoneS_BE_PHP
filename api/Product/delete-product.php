<?php
require '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

require_once '../../config/database.php';
require_once '../../models/Products.php';

$database = new Database();
$db = $database->getConnection();

$product = new Products($db);
$id = $_GET['id'];

try {
    if ($id) {
        $product->id = $id;
        $isCheck = true;

        require_once '../../models/Description.php';
        $description = new Description($db);
        $description->productId = $product->id;

        if (!$description->deleteDescription()) {
            $isCheck = false;
        }

        require_once '../../models/Images.php';
        $image = new Images($db);
        $image->productId = $product->id;

        if (!$image->deleteImageProduct()) {
            $isCheck = false;
        }

        require_once '../../models/SalientFeatures.php';
        $salientFeature = new SalientFeatures($db);
        $salientFeature->productId = $product->id;

        if (!$salientFeature->deleteSalientFeature()) {
            $isCheck = false;
        }

        require_once '../../models/ShortDescription.php';
        $shortDescription = new ShortDescription($db);
        $shortDescription->productId = $product->id;

        if (!$shortDescription->deleteShortDescription()) {
            $isCheck = false;
        }

        require_once '../../models/TechnicalSpecs.php';
        $technicalSpecs = new TechnicalSpecs($db);
        $technicalSpecs->productId = $product->id;

        if (!$technicalSpecs->deleteTechnicalSpecs()) {
            $isCheck = false;
        }

        if (!$product->deleteProduct()) {
            $isCheck = false;
        }

        if ($isCheck) {
            http_response_code(200);
            $res = array(
                "message" => "Xóa sản phẩm thành công"
            );
            echo json_encode($res);
        } else {
            http_response_code(404);
            $res = array(
                "message" => "Xóa sản phẩm không thành công"
            );
            echo json_encode($res);
        }
    }
} catch (Exception $error) {
    http_response_code(404);
    $res = array(
        "message" => $error
    );
    echo json_encode($res);
}
