<?php
session_start();
use Api\Users;
use Api\Login;
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Method:*");
header("Access-Control-Max-Age:3600");
// header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers:*");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$url = explode("/", $_SERVER['REQUEST_URI']);



if (count($url) <= 3 || $url[3] == "" || count($url) >= 5) {
    http_response_code(404);
    echo json_encode(["message" => "not found", "code" => "404"]);
    exit;
}





$method = $_SERVER["REQUEST_METHOD"];
// if (isset($url[2]) && $url[2] == "invoices" && count($url) < 4) {
//     echo json_encode(["message" => "Sueccess", "code" => "200"]);
// }
switch ($url[3]) {
case 'login':
        require_once "Login.php";
        $c = new Login();
        $c->loginUser();
    case 'users':
        require_once "Users.php";
        $c = new Users();
        if ($method == "POST") {
            $c->createOrDelUser();
        }
        $c->getUsers();
    default:
        http_response_code(404);
        echo json_encode(["message" => "not found end point", "code" => 404]);
        exit;
}