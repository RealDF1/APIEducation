<?php

header("Content-Type: application/json");

require 'vendor/autoload.php';

use Api\Users;

// Метод обращения к странице
$requestMethod = $_SERVER["REQUEST_METHOD"];
// Uri запроса
$requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

if ($requestUri[0] === 'users') {
    Users::requestUsers($requestMethod, $requestUri);
    die();
}

http_response_code(404);
echo json_encode(["message" => "Ресурс не найден"]);