<?php

require_once './api/models/Schedule.php';

class ScheduleController
{

    private $db;
    private $requestMethod;
    private $scheduleId;
    private $schedule;

    public function __construct($db, $requestMethod, $scheduleId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->scheduleId = $scheduleId;
        $this->schedule = new Schedule($db);
    }

    public function request()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->scheduleId ? $this->getScheduleById($this->scheduleId) : $this->getAllSchedules();
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
                $response = $this->notFound();
                break;
        }

        header($response['status_code_header']);

        if ($response['body']) {
            echo $response['body'];
        }
    }

    public function getScheduleById($scheduleId)
    {
        $result = $this->schedule->findById($scheduleId);

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }

    public function getAllSchedules()
    {
        $result = $this->schedule->findAll();

        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);

        return $response;
    }

    public function create()
    {
        $data = json_decode(file_get_contents('php://input'), TRUE);

        $this->schedule->create($data);
    }

    public function update($id)
    {
        if ($this->schedule->findById($id)) {
            $data = json_decode(file_get_contents('php://input'), TRUE);
            $this->schedule->update($id, $data);

            $response['status_code_header'] = 'HTTP/1.1 204 No Content';
            $response['body'] = null;

            return $response;
        }

        return $this->notFound();
    }

    public function delete($id)
    {
        if ($this->schedule->findById($id)) {
            $this->schedule->delete($id);

            $response['status_code_header'] = 'HTTP/1.1 204 No Content';
            $response['body'] = null;

            return $response;
        }

        return $this->notFound();
    }

    public function notFound()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;

        return $response;
    }
}
