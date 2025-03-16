<?php
namespace Api;
require_once "Conn.php";
use Api\Conn\Conn;

class Login
{
    private $conn;
    public function __construct()
    {
        $this->conn = new Conn;
    }
    public function loginUser()
    {

        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['email'], $data['password'])) {
            echo json_encode(["error" => "Missing required fields"]);
            exit;
        }

        $email = trim($data['email']);
        $password = trim($data['password']);

        $stmt = $this->conn->conn->prepare("SELECT * FROM `users` WHERE email = ? ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            // $token = "muzamil";
            // تخزين التوكن في كوكي آمنة
            // setcookie("jwt_token", $token, [
            //     "expires" => time() + (60 * 60),
            //     "path" => "/",
            //     "domain" => "http://localhost:3000",
            //     "httponly" => true,
            //     "samesite" => "None",

            // ]);

            // إنشاء CSRF Token

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            echo json_encode(["message" => "Login successful", "user" => $user, "token" => $_SESSION["csrf_token"], "code" => 200]);
        } else {
            echo json_encode(["error" => "Invalid email or password"]);
        }
        exit;
    }




    public function logoutUser()
    {
        $stmt = $this->conn->conn->prepare("SELECT * FROM invoices ");
        $stmt->execute();
        echo json_encode([
            "message" => "Sueccess invoices",
            "invoices" => $stmt->fetchAll(\PDO::FETCH_ASSOC),
            "code" => 200
        ]);
        exit;
    }
}

