<?php
include_once $_SERVER['DOCUMENT_ROOT'] . 'config/Database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/EmployeeController.php';
include_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/UserController.php';
include_once $_SERVER['DOCUMENT_ROOT'] . 'controllers/ScheduleController.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE");
header("Access-Control-Max-Age: 3600");

$uri = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($uri[1]) {
    case 'employee':
        // endpoint: employee/{ID}
        $employeeId = isset($uri[2]) ? (int) $uri[2] : null;

        $database = new Database();
        $dbConnection = $database->connect();

        // endpoint: employee/{EMPLOYEE_ID}/schedule/{SCHEDULE_ID}
        if ($employeeId && $uri[3] == 'schedule') {
            $scheduleId = isset($uri[4]) ? (int) $uri[4] : null;

            $scheduleController = new ScheduleController($dbConnection, $requestMethod, $scheduleId, $employeeId);
            $scheduleController->request();

            break;
        }

        $employeeController = new EmployeeController($dbConnection, $requestMethod, $employeeId);
        $employeeController->request();

        break;
    case 'user':
        // endpoint: user/{USER_ID}
        $userId = isset($uri[2]) ? (int) $uri[2] : null;

        $database = new Database();
        $dbConnection = $database->connect();

        $userController = new UserController($dbConnection, $requestMethod, $userId);
        $userController->request();
        break;
    default:
        exit();
        break;
}

$dbConnection = null;
