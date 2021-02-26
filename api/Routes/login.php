<?php

include_once './api/jwt/JWT.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$dbConn = new Database();
$dbConn = $dbConn->connect();

$data = json_decode(file_get_contents("php://input"), true);

