<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/api/jwt/JWT.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/config/Database.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$sec = "SCHEDE";
$jwt = null;

$db = new Database();

$dbConn = $db->connect();

$data = json_decode(file_get_contents("php://input"));

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];

echo $authHeader;

die();
