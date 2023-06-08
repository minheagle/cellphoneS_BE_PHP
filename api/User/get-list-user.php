<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

include_once '../../config/database.php';
include_once '../../models/Users.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $users = new Users($db);
    $stmt = $users->getListUser();
    $userCount = $stmt->rowCount();

    if ($userCount > 0) {
        $userList = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $user = array(
                "id" => $id,
                "phone" => $phone,
                "email" => $email,
                "fullName" => $fullName,
                "userPassword" => $userPassword,
                "userAddress" => $userAddress,
                "avatar" => $avatar,
                "userRole" => $userRole,
                "accessToken" => $accessToken,
                "created_at" => $created_at
            );
            array_push($userList, $user);
        }
        echo json_encode($userList);
    } else {
        http_response_code(404);
        $userList = array();
        echo json_encode($userList);
    }
} else {
    http_response_code(404);
    $res = array(
        'message' => 'Access Denied'
    );
    echo json_encode($res);
}
