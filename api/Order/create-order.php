<?php
include_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

require_once '../../config/database.php';
require_once '../../models/Orders.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $requestData = json_decode(file_get_contents("php://input"));
    if ($requestData->userId != null) {
        if (!empty($requestData->listProductOrder)) {
            $listProductOrder = $requestData->listProductOrder;
            // Check hang ton kho
            $isCheck = true;
            $message = '';
            foreach ($listProductOrder as $item) {
                require_once '../../models/Products.php';
                $product = new Products($db);
                $product->id = $item->productId;
                $product->getProductById();
                if ($item->quantity > $product->quantity) {
                    $isCheck = false;
                    $message = 'Bạn đã đặt ' . $item->productName . ' quá số lượng hàng tồn kho.';
                    break;
                }
            }

            if ($isCheck) {
                $order = new Order($db);
                $order->userId = $requestData->userId;

                $lastInsertId = $order->createOrder();

                foreach ($listProductOrder as $item) {
                    require_once '../../models/OrderDetails.php';
                    require_once '../../models/Products.php';
                    $orderDetail = new OrderDetail($db);
                    $product->id = $item->productId;
                    $product->getProductById();
                    $newQuantity = $product->quantity - $item->quantity;
                    $product->updateQuantityProduct($newQuantity);

                    $orderDetail->orderId = $lastInsertId;
                    $orderDetail->productName = $item->productName;
                    $orderDetail->quantity = $item->quantity;
                    $orderDetail->unitPrice = $item->price;

                    $orderDetail->createOrderDetail();
                }

                http_response_code(200);
                $res = array(
                    'message' => 'Đặt hàng thành công'
                );
                echo json_encode($res);
            } else {
                http_response_code(404);
                $res = array(
                    'message' => $message
                );
                echo json_encode($res);
            }
        } else {
            http_response_code(404);
            $res = array(
                'message' => 'Không có thông tin đơn hàng'
            );
            echo json_encode($res);
        }
    } else if ($requestData->guestId != null) {
        if (!empty($requestData->listProductOrder)) {
            $listProductOrder = $requestData->listProductOrder;
            // Check hang ton kho
            $isCheck = true;
            $message = '';
            foreach ($listProductOrder as $item) {
                require_once '../../models/Products.php';
                $product = new Products($db);
                $product->id = $item->productId;
                $product->getProductById();
                if ($item->quantity > $product->quantity) {
                    $isCheck = false;
                    $message = 'Bạn đã đặt ' . $item->productName . ' quá số lượng hàng tồn kho.';
                    break;
                }
            }

            if ($isCheck) {
                $order = new Order($db);
                $order->guestId = $requestData->guestId;

                $lastInsertId = $order->createOrder();

                foreach ($listProductOrder as $item) {
                    require_once '../../models/OrderDetails.php';
                    require_once '../../models/Products.php';
                    $orderDetail = new OrderDetail($db);
                    $product->id = $item->productId;
                    $product->getProductById();
                    $newQuantity = $product->quantity - $item->quantity;
                    $product->updateQuantityProduct($newQuantity);

                    $orderDetail->orderId = $lastInsertId;
                    $orderDetail->productName = $item->productName;
                    $orderDetail->quantity = $item->quantity;
                    $orderDetail->unitPrice = $item->price;

                    $orderDetail->createOrderDetail();
                }

                http_response_code(200);
                $res = array(
                    'message' => 'Đặt hàng thành công'
                );
                echo json_encode($res);
            } else {
                http_response_code(404);
                $res = array(
                    'message' => $message
                );
                echo json_encode($res);
            }
        } else {
            http_response_code(404);
            $res = array(
                'message' => 'Không có thông tin đơn hàng'
            );
            echo json_encode($res);
        }
    } else {
        http_response_code(404);
        $res = array(
            'message' => 'Không có thông tin nhận hàng'
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
