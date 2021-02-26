<?php

require_once './api/model/Schedule.php';

class ScheduleController
{

    private $db;
    private $requestMethod;
    private $scheduleId;
    private $schedule;
    private $employeeId;

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
                $response = $this->scheduleId ? $this->getScheduleById($this->employeeId, $this->scheduleId) : $this->getAllSchedules($this->employeeId);
                break;
            case 'POST':
                $response = $this->create();
                break;
            case 'PATCH':
                $response = $this->update($this->scheduleId);
                break;
            case 'DELETE':
                $response = $this->delete($this->scheduleId);
                break;
            default:
                $response = $this->resourceNotFound();
                break;
        }

        header($response['status_code_header']);
        echo $response['body'] ? $response['body'] : null;
    }

    public function getScheduleById($employeeId, $scheduleId)
    {
        $result = $this->schedule->findById($employeeId, $scheduleId);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }

    public function getAllSchedules($employeeId)
    {
        $result = $this->schedule->findAll($employeeId);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }

    public function create()
    {
        $data = json_decode(file_get_contents('php://input'), TRUE);

        if ($this->schedule->create($data)) {
            $response['status_code_header'] = 'HTTP/1.1 204 No Content';
            $response['body'] = null;
        } else {
            $response['status_code_header'] = 'HTTP/1.1 409 Conflict';
            $response['body'] = null;
        }
    }

    public function update($scheduleId)
    {
        if ($this->schedule->findById($scheduleId, $this->employeeId)) {
            $data = json_decode(file_get_contents('php://input'), TRUE);
            $this->schedule->update($scheduleId, $this->employeeId, $data);

            $response['status_code_header'] = 'HTTP/1.1 204 No Content';
            $response['body'] = null;

            return $response;
        }

        return $this->resourceNotFound();
    }

    public function delete($scheduleId)
    {
        if ($this->schedule->findById($this->employeeId, $scheduleId)) {
            $this->schedule->delete($scheduleId);

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
