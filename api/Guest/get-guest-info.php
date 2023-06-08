<?php
require_once '../../helper/CORS/CORS.php';
$cors = new CORS();
$cors->cors();

include_once '../../config/database.php';
include_once '../../models/Guests.php';

$database = new Database();
$db = $database->getConnection();

$guest = new Guest($db);
$guest->id = isset($_GET['id']) ? $_GET['id'] : die();

$guest->getGuestById();

if ($guest->id != null) {
    $emp_arr = array(
        "id" => $guest->id,
        "phone" => $guest->phone,
        "email" => $guest->email,
        "fullName" => $guest->fullName,
        "address" => $guest->address,
    );

    http_response_code(200);
    echo json_encode($emp_arr);
} else {
    http_response_code(404);
    echo json_encode("Guest not found.");
}
