<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/api/config/Database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/api/model/User.php';

$dbConn = new Database();
$dbConn = $dbConn->connect();

$user = new User($dbConn);

$data = json_decode(file_get_contents("php:://input"), TRUE);

if ($user->create($data)) {
    http_response_code(200);
    echo json_encode(
        array("msg" => "User created")
    );
} else {
    http_response_code(400);
    echo json_encode(
        array("msg" => "Unable to create user")
    );
}
