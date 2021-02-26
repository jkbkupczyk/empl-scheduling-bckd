<?php

require_once 'UserRole.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/Utils.php';

class Employee
{
    private $conn;
    private $table = "Employees";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $qry = 'INSERT INTO ' . $this->table . ' (name, surname, email, age, status) VALUES (:name, :surname, :email, :age, :status)';

        $stmt = $this->conn->prepare($qry);

        $data = Utils::sanitizeInput($data);

        $stmt->execute(
            array(
                'name' => $data['name'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'age' => $data['age'],
                'status' => $data['status']
            )
        );

        return $stmt->rowCount();
    }

    public function findAll()
    {
        $qry = 'SELECT e.id, e.name, e.surname, e.email, e.age, e.status, e.createdAt FROM ' . $this->table . ' e';

        $stmt = $this->conn->prepare($qry);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data ? $data : null;
    }

    public function findById($id)
    {
        $qry = 'SELECT e.id, e.name, e.surname, e.email, e.age, e.status, e.createdAt FROM ' . $this->table . ' e WHERE e.id = ?';

        $stmt = $this->conn->prepare($qry);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $data : null;
    }

    public function findByName($name)
    {
        $qry = 'SELECT e.id, e.name, e.surname, e.email, e.age, e.status, e.createdAt FROM ' . $this->table . ' e WHERE e.name = ?';

        $stmt = $this->conn->prepare($qry);
        $stmt->bindParam(1, $name);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $data : null;
    }

    public function update($id, $data)
    {
        $qry = 'UPDATE ' . $this->table . '
            SET
                name = :name,
                surname = :surname,
                email = :email,
                age = :age,
                status = :status
            WHERE
                id = :id';

        $stmt = $this->conn->prepare($qry);

        $data = Utils::sanitizeInput($data);

        $stmt->execute(
            array(
                'id' => (int) $id,
                'name' => $data['name'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'age' => $data['age'],
                'status' => $data['status']
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
