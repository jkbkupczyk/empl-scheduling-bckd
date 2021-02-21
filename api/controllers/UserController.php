<?php

require_once './api/models/User.php';

class UserController
{

    private $db;
    private $requestMethod;
    private $userId;
    private $user;

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
                $response = $this->userId ? $this->getEmployee($this->userId) : $this->getAllEmployees();
                break;
            case 'POST':
                $response = $this->create();
                break;
            case 'PATCH':
                $response = $this->update($this->userId);
                break;
            case 'DELETE':
                $response = $this->delete($this->userId);
                break;
            default:
                $response = $this->resourceNotFound();
                break;
        }

        header($response['status_code_header']);

        if ($response['body']) {
            echo $response['body'];
        }
    }

    public function getEmployee($userId)
    {
        $result = $this->user->findById($userId);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }

    public function getAllEmployees()
    {
        $result = $this->user->findAll();

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }

    public function create()
    {
        $data = json_decode(file_get_contents('php://input'), TRUE);

        $this->user->create($data);
    }

    public function update($id)
    {
        if ($this->user->findById($id)) {
            $data = json_decode(file_get_contents('php://input'), TRUE);
            $this->user->update($id, $data);

            $response['status_code_header'] = 'HTTP/1.1 204 No Content';
            $response['body'] = null;

            return $response;
        }

        return $this->resourceNotFound();
    }

    public function delete($id)
    {
        if ($this->user->findById($id)) {
            $this->user->delete($id);

            $response['status_code_header'] = 'HTTP/1.1 204 No Content';
            $response['body'] = null;

            return $response;
        }

        return $this->resourceNotFound();
    }

    public function resourceNotFound()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;

        return $response;
    }
}
