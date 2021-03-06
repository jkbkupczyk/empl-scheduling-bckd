<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/Utils.php';

class Schedule
{
    private $conn;
    private $table = "Schedules";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data, $employeeId): int
    {
        $qry = 'INSERT INTO ' . $this->table . ' (scheduleName, employeeId, date, time) VALUES (:scheduleName, :employeeId, :date, :time)';

        $stmt = $this->conn->prepare($qry);

        $stmt->execute(
            array(
                'scheduleName' => $data['scheduleName'],
                'employeeId' => (int) $employeeId,
                'date' => $data['date'],
                'time' => $data['time']
            )
        );

        return $stmt->rowCount();
    }

    public function findAll($employeeId)
    {
        $qry = 'SELECT s.scheduleId, s.scheduleName, e.name, e.surname, s.date 
                FROM ' . $this->table . ' s 
                INNER JOIN employees e ON s.employeeId = e.id 
                WHERE e.id = ? ORDER BY s.date';

        $stmt = $this->conn->prepare($qry);
        $stmt->bindParam(1, $employeeId);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data ? $data : null;
    }

    public function findById($employeeId, $scheduleId)
    {
        $qry = 'SELECT s.scheduleId, s.scheduleName, e.name, e.surname, s.date, sh
                INNER JOIN employees e ON s.employeeId = e.id 
                WHERE e.id = :employeeId AND s.scheduleId = :scheduleId';

        $stmt = $this->conn->prepare($qry);
        $stmt->bindParam(':employeeId', $employeeId);
        $stmt->bindParam(':scheduleId', $scheduleId);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $data : null;
    }

    public function update($scheduleId, $employeeId, $data): int
    {
        $qry = 'UPDATE ' . $this->table . '
            SET
                scheduleName = :scheduleName,
                employeeId = :employeeId,
                date = :date,
                time = :time,
            WHERE
                scheduleId = :scheduleId';

        $stmt = $this->conn->prepare($qry);

        $stmt->execute(
            array(
                'scheduleId' => (int) $scheduleId,
                'scheduleName' => $data['username'],
                'employeeId' => $employeeId,
                'date' => $data['pass'],
                'time' => $data['name']
            )
        );

        return $stmt->rowCount();
    }

    public function delete($id): int
    {
        $qry = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        $stmt = $this->conn->prepare($qry);
        $id = htmlspecialchars(strip_tags($id));
        $stmt->bindParam(1, $id);

        $stmt->execute();

        return $stmt->rowCount();
    }
}
