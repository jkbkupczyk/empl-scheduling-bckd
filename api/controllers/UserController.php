<?php

include_once "./api/model/User.php";

class UserController
{
    private $db;
    private $requestMethod;
    private $userId;
    private $user;
    private $responseCode;
    private $responseBody;

    public function __construct($db, $requestMethod, $userId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;
        $this->user = new User($db);
    }

    public function request()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $this->userId ? $this->getEmployee($this->userId) : $this->getAllEmployees();
                break;
            case 'POST':
                $this->create();
                break;
            case 'PATCH':
                $this->update($this->userId);
                break;
            case 'DELETE':
                $this->delete($this->userId);
                break;
            default:
                $this->methodNotFound();
                break;
        }

        header($this->responseCode);

        if (isset($this->responseBody)) {
            echo $this->responseBody;
        }
    }

    public function create()
    {
        $data = json_decode(file_get_contents('php://input'), TRUE);

        if ($this->user->create($data)) {
            $this->responseCode = 'HTTP/1.1 204 No Content';
            $this->responseBody = null;
        } else {
            $this->responseCode = 'HTTP/1.1 409 Conflict';
            $this->responseBody = null;
        }
    }

    public function getEmployee($userId)
    {
        $result = $this->user->findById($userId);

        $this->responseCode = 'HTTP/1.1 200 OK';
        $this->responseBody = json_encode($result);
    }

    public function getAllEmployees()
    {
        $result = $this->user->findAll();

        $this->responseCode = 'HTTP/1.1 200 OK';
        $this->responseBody = json_encode($result);
    }

    public function update($id)
    {
        if ($this->user->findById($id)) {
            $data = json_decode(file_get_contents('php://input'), TRUE);
            $this->user->update($id, $data);

            $this->responseCode = 'HTTP/1.1 204 No Content';
            $this->responseBody = null;
        }

        return $this->resourceNotFound();
    }

    public function delete($id)
    {
        if ($this->user->findById($id)) {
            $this->user->delete($id);

            $this->responseCode = 'HTTP/1.1 204 No Content';
            $this->responseBody = null;
        }

        return $this->resourceNotFound();
    }

    public function resourceNotFound()
    {
        $this->responseCode = 'HTTP/1.1 404 Not Found';
        $this->responseBody = null;
    }

    public function methodNotFound()
    {
        $this->responseCode = 'HTTP/1.1 405 Method Not Allowed';
        $this->responseBody = json_encode(array("msg" => "Method Not Allowed, try again with with different method"));
    }
}
