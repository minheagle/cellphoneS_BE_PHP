<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

require_once '../../config/database.php';
require_once '../../models/Users.php';
require_once '../../models/Orders.php';


$database = new Database();
$db = $database->getConnection();

$user = new Users($db);
$user->id = isset($_GET['id']) ? $_GET['id'] : die();

$user->getUserById();

if ($user->id != null) {
    $orders = new Order($db);

    $orderIdList = $orders->getOrderByUserId($user->id);
    $orderCount = 0;
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
        $orderCount++;
    }

    $emp_arr = array(
        "id" => $user->id,
        "phone" => $user->phone,
        "email" => $user->email,
        "fullName" => $user->fullName,
        "userAddress" => $user->userAddress,
        "avatar" => $user->avatar,
        "userRole" => $user->userRole,
        "refreshToken" => $user->refreshToken,
        "created_at" => $user->created_at,
        "orderList" => $orderList,
        "orderCount" => $orderCount
    );

    http_response_code(200);
    echo json_encode($emp_arr);
} else {
    http_response_code(404);
    echo json_encode("User not found.");
}
