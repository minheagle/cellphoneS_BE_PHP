<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

include_once '../../config/database.php';
include_once '../../models/Orders.php';

$database = new Database();
$db = $database->getConnection();

$orders = new Order($db);

$orderIdList = $orders->getListOrder();

$orderList = array();
foreach ($orderIdList as $item) {
    require_once '../../models/OrderDetails.php';
    $orderDetail = new OrderDetail($db);

    $stmt = $orderDetail->getOrderDetailByOrderId($item['id']);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $order = array(
            "id" => $row['id'],
            "productName" => $row['productName'],
            "quantity" => $row['quantity'],
            "unitPrice" => $row['unitPrice'],
            "createdAt" => $item['createdAt']
        );
        array_push($orderList, $order);
    }
    // $orderCount++;
}

http_response_code(200);
echo json_encode($orderList);
