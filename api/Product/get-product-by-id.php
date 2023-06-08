<?php
require '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

require_once '../../config/database.php';
require_once '../../models/Products.php';
require_once '../../models/Images.php';
require_once '../../models/SalientFeatures.php';
require_once '../../models/ShortDescription.php';
require_once '../../models/TechnicalSpecs.php';
require_once '../../models/Description.php';

$database = new Database();
$db = $database->getConnection();

$product = new Products($db);
$images = new Images($db);
$salientFeatures  = new SalientFeatures($db);
$shortDescription = new ShortDescription($db);
$technicalSpecs = new TechnicalSpecs($db);
$description = new Description($db);

$id = $_GET['id'];

try {
    if ($id) {
        $product->id = $id;
        if ($product->getProductById()) {
            http_response_code(200);
            // Get list image by productId
            $images->productId = $product->id;

            $listImage = $images->getImageByProductId();

            // Get salient feature by product id
            $salientFeatures->productId = $product->id;

            $listSalientFeature = $salientFeatures->getSalientFeatureByProductId();

            // Get short description by product id
            $shortDescription->productId = $product->id;

            $listShortDescription = $shortDescription->getShortDescriptionByProductId();

            // Get technical specs by product id
            $technicalSpecs->productId = $product->id;

            $listTechnicalSpecs = $technicalSpecs->getTechnicalSpecsByProductId();

            // Get description by product id
            $description->productId = $product->id;
            $stmt = $description->getDescriptionByProduct();
            if ($stmt->rowCount() > 0) {
                $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
                $descriptionContent = $dataRow['description'];
            } else {
                $descriptionContent = "";
            }

            $productInfo = array(
                'id' => $product->id,
                'categoryId' => $product->categoryId,
                'brandId' => $product->brandId,
                'seriesId' => $product->seriesId,
                'productName' => $product->productName,
                'standardCost' => $product->standardCost,
                'listImage' => $listImage,
                'description' => $descriptionContent,
                'listSalientFeature' => $listSalientFeature,
                'listShortDescription' => $listShortDescription,
                'listTechnicalSpecs' => $listTechnicalSpecs,
                'isNew' => $product->isNew,
                'quantity' => $product->quantity
            );
            echo json_encode($productInfo);
        }
    }
} catch (Exception $error) {
    http_response_code(404);
    $res = array(
        'message' => $error
    );
    echo json_encode($res);
}
