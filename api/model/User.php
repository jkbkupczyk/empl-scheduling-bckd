<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/api/utils/Utils.php';

class User
{
    private $conn;
    private $table = "Users";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        $qry = 'INSERT INTO ' . $this->table . ' (username, email, pass, name, surname, role) VALUES (:username, :email, :pass, :name, :surname, :role)';

        $stmt = $this->conn->prepare($qry);

        $data = Utils::sanitizeInput($data);

        $hashedPass = password_hash($data['pass'], PASSWORD_DEFAULT);

        $stmt->execute(
            array(
                'username' => $data['username'],
                'email' => $data['email'],
                'pass' => $hashedPass,
                'name' => $data['name'],
                'surname' => $data['surname'],
                'role' => $data['role']
            )
        );

        return $stmt->rowCount();
    }

    public function findAll()
    {
        $qry = 'SELECT u.id, u.username, u.email, u.name, u.surname, u.role FROM ' . $this->table . ' u';

        $stmt = $this->conn->prepare($qry);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data ? $data : null;
    }

    public function findById($id)
    {
        $qry = 'SELECT u.id, u.username, u.email, u.name, u.surname, u.role, u.pass FROM ' . $this->table . ' u WHERE u.id = ?';

        $stmt = $this->conn->prepare($qry);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $data : null;
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

        $data = Utils::sanitizeInput($data);

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