<?php

class Schedule
{
    private $conn;
    private $table = "Schedules";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $qry = 'INSERT INTO ' . $this->table . ' (scheduleId, scheduleName, employeeId, date) VALUES (:scheduleId, :scheduleName, :pass, :name, :surname, :role)';

        $stmt = $this->conn->prepare($qry);

        $data['username'] = htmlspecialchars(strip_tags($data['username']));
        $data['email'] = htmlspecialchars(strip_tags($data['email']));
        $data['pass'] = htmlspecialchars(strip_tags($data['pass']));
        $data['name'] = htmlspecialchars(strip_tags($data['name']));
        $data['surname'] = htmlspecialchars(strip_tags($data['surname']));
        $data['role'] = htmlspecialchars(strip_tags($data['role']));

        $stmt->execute(
            array(
                'username' => $data['username'],
                'email' => $data['email'],
                'pass' => $data['pass'],
                'name' => $data['name'],
                'surname' => $data['surname'],
                'role' => $data['role']
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
        $qry = 'SELECT s.scheduleId, s.scheduleName, e.name, e.surname, s.date, s.time
                FROM ' . $this->table . ' s 
                INNER JOIN employees e ON s.employeeId = e.id 
                WHERE e.id = :employeeId AND s.scheduleId = :scheduleId';

        $stmt = $this->conn->prepare($qry);
        $stmt->bindParam(':employeeId', $employeeId);
        $stmt->bindParam(':scheduleId', $scheduleId);

        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $data : null;
    }

    public function update($scheduleId, $employeeId, $data)
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

        $data['scheduleName'] = htmlspecialchars(strip_tags($data['scheduleName']));
        $data['employeeId'] = htmlspecialchars(strip_tags($data['employeeId']));
        $data['date'] = htmlspecialchars(strip_tags($data['date']));
        $data['time'] = htmlspecialchars(strip_tags($data['time']));

        $stmt->execute(
            array(
                'scheduleId' => (int) $scheduleId,
                'scheduleName' => $data['username'],
                'employeeId' => $data['email'],
                'date' => $data['pass'],
                'time' => $data['name']
            )
        );

        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $qry = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        $stmt = $this->conn->prepare($qry);
        $id = htmlspecialchars(strip_tags($id));
        $stmt->bindParam(1, $id);

        $stmt->execute();

        return $stmt->rowCount();
    }
}
