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
        $qry = 'INSERT INTO ' . $this->table . ' (username, email, pass, name, surname, role) VALUES (:username, :email, :pass, :name, :surname, :role)';

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

    public function findAll()
    {
        $qry = 'SELECT u.username, u.email, u.name, u.surname, u.role FROM ' . $this->table . ' u';

        $stmt = $this->conn->prepare($qry);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    public function findById($id)
    {
        $qry = 'SELECT u.username, u.email, u.name, u.surname, u.role FROM ' . $this->table . ' u WHERE u.id = ? LIMIT 1';

        $stmt = $this->conn->prepare($qry);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data;
    }

    public function update($id, $data)
    {
        $qry = 'UPDATE ' . $this->table . '
            SET
                username = :username,
                email = :email,
                pass = :pass,
                name = :name,
                surname = :surname,
                role = :role
            WHERE
                id = :id';

        $stmt = $this->conn->prepare($qry);

        $data['username'] = htmlspecialchars(strip_tags($data['username']));
        $data['email'] = htmlspecialchars(strip_tags($data['email']));
        $data['pass'] = htmlspecialchars(strip_tags($data['pass']));
        $data['name'] = htmlspecialchars(strip_tags($data['name']));
        $data['surname'] = htmlspecialchars(strip_tags($data['surname']));
        $data['role'] = htmlspecialchars(strip_tags($data['role']));

        $stmt->execute(
            array(
                'id' => (int) $id,
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
