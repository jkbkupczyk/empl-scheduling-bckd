<?php

require_once './api/models/Employee.php';

class EmployeeController
{

    private $db;
    private $requestMethod;
    private $employeeId;
    private $employee;

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
                $response = $this->employeeId ? $this->getEmployee($this->employeeId) : $this->getAllEmployees();
                break;
            case 'POST':
                $response = $this->create();
                break;
            case 'PATCH':
                $response = $this->update($this->employeeId);
                break;
            case 'DELETE':
                $response = $this->delete($this->employeeId);
                break;
            default:
                $response = $this->requestNotFound();
                break;
        }

        header($response['status_code_header']);

        if ($response['body']) {
            echo $response['body'];
        }
    }

    public function getEmployee($employeeId)
    {
        $result = $this->employee->findById($employeeId);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }

    public function getAllEmployees()
    {
        $result = $this->employee->findAll();

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }

    public function create()
    {
        $data = json_decode(file_get_contents('php://input'), TRUE);

        $this->employee->create($data);
    }

    public function update()
    {
        # code...
    }

    public function delete()
    {
        # code...
    }

    public function requestNotFound()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;

        return $response;
    }
}
