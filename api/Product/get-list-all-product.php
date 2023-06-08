<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

require_once '../../config/database.php';
require_once '../../models/Products.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $product = new Products($db);

    $products = $product->getListAllProduct();
    if ($products) {
        $listProduct = array();

        foreach ($products as $item) {
            require_once '../../models/Images.php';
            require_once '../../models/SalientFeatures.php';
            require_once '../../models/ShortDescription.php';
            require_once '../../models/TechnicalSpecs.php';
            require_once '../../models/Description.php';

            $images = new Images($db);
            $salientFeatures  = new SalientFeatures($db);
            $shortDescription = new ShortDescription($db);
            $technicalSpecs = new TechnicalSpecs($db);
            $description = new Description($db);

            // Get list image by productId
            $images->productId = $item['id'];

            $listImage = $images->getImageByProductId();
            $imageFirst = current($listImage);
            $url = $imageFirst['imageUrl'] ?? null;

            // Get salient feature by product id
            $salientFeatures->productId = $item['id'];

            $listSalientFeature = $salientFeatures->getSalientFeatureByProductId();

            // Get short description by product id
            $shortDescription->productId = $item['id'];

            $listShortDescription = $shortDescription->getShortDescriptionByProductId();

            // Get technical specs by product id
            $technicalSpecs->productId = $item['id'];

            $listTechnicalSpecs = $technicalSpecs->getTechnicalSpecsByProductId();

            // Get description by product id
            $description->productId = $item['id'];
            $description->getDescriptionByProduct();

            $newProduct = array(
                'id' => $item['id'],
                'seriesId' => $item['seriesId'],
                'productName' => $item['productName'],
                'standardCost' => $item['standardCost'],
                'url' => $url,
                'isNew' => $item['isNew'],
                'quantity' => $item['quantity']
            );

            array_push($listProduct, $newProduct);
        }
        http_response_code(200);
        echo json_encode($listProduct);
    } else {
        http_response_code(200);
        $listProduct = array();
        echo json_encode($listProduct);
    }
} else {
    http_response_code(404);
    $res = array(
        'message' => 'Access Denied'
    );
    echo json_encode($res);
}
