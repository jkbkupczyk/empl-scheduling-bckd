<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/api/jwt/JWT.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/config/Database.php';

use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

class LoginController
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function login()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $qry = "SELECT id, username, email, pass FROM users u WHERE u.email = ? LIMIT 0, 1";

        $stmt = $this->conn->prepare($qry);
        $stmt->bindParam(1, $data['email']);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($data['password'], $row['pass'])) {
                $secretKey = "Nevada";
                $issuedAt = time();
                $expAt = $issuedAt + 1800;

                $payload = array(
                    "iss" => "SCHEDE",
                    "aud" => "AUDIENCE",
                    "iat" => $issuedAt,
                    "nbf" => $issuedAt + 10,
                    "exp" => $expAt,
                    "data" => array(
                        "id" => $row['id'],
                        "username" => $row['username'],
                        "email" => $row['email'],
                    )
                );

                // sign JWT token
                $jwt = JWT::encode($payload, $secretKey);

                header('HTTP/1.1 200 OK');

                echo json_encode(
                    array(
                        "msg" => "Successfull login",
                        "email" => $data['email'],
                        "jwt" => $jwt,
                        "expire" => $expAt
                    )
                );
            } else {
                header('HTTP/1.1 401 Unauthorized');

                echo json_encode(
                    array(
                        "code" => 401,
                        "msg" => "Login failed - Invalid password"
                    )
                );
            }
        } else {
            header('HTTP/1.1 401 Unauthorized');

            echo json_encode(
                array(
                    "code" => 401,
                    "msg" => "Login failed - Account with specified email does not exist"
                )
            );
        }
    }
}
