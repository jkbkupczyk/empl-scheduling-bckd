<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/Database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/api/controllers/EmployeeController.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/api/controllers/UserController.php';

require_once './api/models/User.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PATCH,DELETE");
header("Access-Control-Max-Age: 3600");

$uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($uri[1]) {
    case 'employee':
        $employeeId = null;
        if (isset($uri[2])) {
            $employeeId = (int) $uri[2];
        }

        $database = new Database();
        $dbConnection = $database->connect();

        $employeeController = new EmployeeController($dbConnection, $requestMethod, $employeeId);
        $employeeController->request();

        break;
    case 'user':
        $userId = null;
        if (isset($uri[2])) {
            $userId = (int) $uri[2];
        }
        
        $database = new Database();
        $dbConnection = $database->connect();

        $userController = new UserController($dbConnection, $requestMethod, $userId);
        $userController->request();
        break;
    default:
        exit();
        break;
}
