<?php
namespace Api;
require_once "Conn.php";

use Api\Conn\Conn;

class Users
{
    private $conn;
    public function __construct()
    {
        $this->conn = new Conn;
    }
    public function getUsers()
    {
        try {
            $headers = $_SERVER;
            if (!isset($headers["HTTP_X_CSRF_TOKEN"]) || $headers["HTTP_X_CSRF_TOKEN"] !== $_SESSION['csrf_token']) {
                echo json_encode(["error" => "Invalid CSRF Token"]);
                exit;
            }

            $stmt = $this->conn->conn->prepare("SELECT * FROM `users` ");
            $stmt->execute();
            echo json_encode([
                "message" => "Sueccess users",
                "users" => $stmt->fetchAll(\PDO::FETCH_ASSOC),
                "code" => 200
            ]);
            // echo json_encode(["success" => $_SESSION['csrf_toke'], "user" => $headers["HTTP_X_CSRF_TOKEN"]]);
        } catch (\Exception $e) {
            echo json_encode(["error" => "Invalid token: " . $e->getMessage()]);
        }
        exit;
    }
    public function createOrDelUser()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (count($data) > 1 && !isset($data['idd'])) {
            $stmt = $this->conn->conn->prepare("INSERT INTO `users`(`name`,`address`, `email`, `password`) VALUES (?,?,?,?)");
            $stmt->execute([trim($data['name']), trim($data['address']), trim($data['email']), password_hash(trim($data['password']), PASSWORD_DEFAULT)]);
            echo json_encode(["message" => "Aid added successfully", "code" => 201]);
            exit;
        }
        if (count($data) > 1 && isset($data['idd'])) {
            $stmt = $this->conn->conn->prepare("UPDATE `users` SET `name` = ?,`email` = ?, `address` = ? WHERE id = ?");
            $stmt->execute([trim($data['name']), trim($data['email']), trim($data['address']), trim($data['idd'])]);
            echo json_encode(["message" => "user update successfully", "code" => 201]);
            exit;
        }
        $stmt = $this->conn->conn->prepare("DELETE FROM `users` WHERE id = ?");
        $stmt->execute([$data['id']]);
        echo json_encode(["message" => "Aid deleted successfully", "code" => 203]);
        exit;
    }
}

