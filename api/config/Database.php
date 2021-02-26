<?php
class Database
{
    private $host_name = 'localhost';
    private $db_name = 'restfuldb_dev';
    private $username = 'root';
    private $password = '';
    
    private $conn;

    public function connect()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO('mysql:host=' . $this->host_name . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $err) {
            echo "Connection error: " . $err->getMessage();
        }

        return $this->conn;
    }
}
