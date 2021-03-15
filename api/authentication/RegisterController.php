<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/api/config/Database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/api/model/User.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class RegisterController
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function registerNewUser()
    {
        $user = new User($this->conn);

        $data = json_decode(file_get_contents("php://input"), true);

        if ($user->exists($data['email'], $data['username'])) {
            header('HTTP/1.1 409 Conflict');

            echo json_encode(
                array(
                    "code" => 409,
                    "msg" => "Username with this email or username already exists"
                )
            );
        } else {
            if ($user->create($data)) {
                header('HTTP/1.1 201 Created');

                echo json_encode(
                    array(
                        "code" => 201,
                        "msg" => "User created",
                        "username" => $data['username'],
                        "email" => $data['email']
                    )
                );
            } else {
                header('HTTP/1.1 409 Conflict');

                echo json_encode(
                    array(
                        "code" => 409,
                        "msg" => "Unable to create user"
                    )
                );
            }
        }
    }
}
