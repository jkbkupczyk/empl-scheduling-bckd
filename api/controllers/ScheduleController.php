<?php

include_once "./api/model/Schedule.php";

class ScheduleController
{
    private $db;
    private $requestMethod;
    private $scheduleId;
    private $schedule;
    private $employeeId;
    private $responseCode;
    private $responseBody;

    public function __construct($db, $requestMethod, $scheduleId, $employeeId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->scheduleId = $scheduleId;
        $this->schedule = new Schedule($db);
        $this->employeeId = $employeeId;
    }

    public function request()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $this->scheduleId ? $this->getScheduleById($this->employeeId, $this->scheduleId) : $this->getAllSchedules($this->employeeId);
                break;
            case 'POST':
                $this->create();
                break;
            case 'PATCH':
                $this->update($this->scheduleId);
                break;
            case 'DELETE':
                $this->delete($this->scheduleId);
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

        if ($this->schedule->create($data, $this->employeeId)) {
            $this->responseCode = 'HTTP/1.1 204 No Content';
            $this->responseBody = null;
        } else {
            $this->responseCode = 'HTTP/1.1 409 Conflict';
            $this->responseBody = null;
        }
    }

    public function getScheduleById($employeeId, $scheduleId)
    {
        $result = $this->schedule->findById($employeeId, $scheduleId);

        $this->responseCode = 'HTTP/1.1 200 OK';
        $this->responseBody = json_encode($result);
    }

    public function getAllSchedules($employeeId)
    {
        $result = $this->schedule->findAll($employeeId);

        $this->responseCode = 'HTTP/1.1 200 OK';
        $this->responseBody = json_encode($result);
    }

    public function update($scheduleId)
    {
        if ($this->schedule->findById($scheduleId, $this->employeeId)) {
            $data = json_decode(file_get_contents('php://input'), TRUE);
            $this->schedule->update($scheduleId, $this->employeeId, $data);

            $this->responseCode = 'HTTP/1.1 204 No Content';
            $this->responseBody = null;
        }

        return $this->resourceNotFound();
    }

    public function delete($scheduleId)
    {
        if ($this->schedule->findById($this->employeeId, $scheduleId)) {
            $this->schedule->delete($scheduleId);

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
