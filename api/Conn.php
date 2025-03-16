<?php

namespace Api\Conn;

class Conn
{
    private $db = "vat";
    private $h = "localhost";
    private $dsn = "mysql:host=localhost;dbname=vat;charset=utf8";
    private $username = "root";
    private $password = "";
    public $conn;

    public function __construct()
    {
        try {
            $this->conn = new \PDO($this->dsn, $this->username, $this->password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
        } catch (\PDOException $e) {
            echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
            exit;
        }
    }


}
