<?php

include_once "./api/model/Employee.php";

class EmployeeController
{
    private $db;
    private $requestMethod;
    private $employeeId;
    private $employee;
    private $responseCode;
    private $responseBody;

    public function __construct($db, $requestMethod, $employeeId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->employeeId = $employeeId;
        $this->employee = new Employee($db);
    }

    public function request()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $this->employeeId ? $this->getEmployee($this->employeeId) : $this->getAllEmployees();
                break;
            case 'POST':
                $this->create();
                break;
            case 'PATCH':
                $this->update($this->employeeId);
                break;
            case 'DELETE':
                $this->delete($this->employeeId);
                break;
            default:
                $this->methodNotFound();
                break;
        }

        header($this->responseCode);

        if ($this->responseBody) {
            echo $this->responseBody;
        }
    }

    public function create()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if ($this->employee->create($data)) {
            $this->responseCode = 'HTTP/1.1 204 No Content';
            $this->responseBody = null;
        } else {
            $this->responseCode = 'HTTP/1.1 409 Conflict';
            $this->responseBody = null;
        }
    }

    public function getEmployee($employeeId)
    {
        $result = $this->employee->findById($employeeId);

        $this->responseCode = 'HTTP/1.1 200 OK';
        $this->responseBody = json_encode($result);
    }

    public function getAllEmployees()
    {
        $result = $this->employee->findAll();

        $this->responseCode = 'HTTP/1.1 200 OK';
        $this->responseBody = json_encode($result);
    }

    public function update($id)
    {
        if ($this->employee->findById($id)) {
            $data = json_decode(file_get_contents('php://input'), TRUE);
            $this->employee->update($id, $data);

            $this->responseCode = 'HTTP/1.1 204 No Content';
            $this->responseBody = null;
        }

        return $this->resourceNotFound();
    }

    public function delete($id)
    {
        if ($this->employee->findById($id)) {
            $this->employee->delete($id);

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
