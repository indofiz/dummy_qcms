<?php

require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Access-Control-Allow-Origin: http://localhost:5173"); // Ganti dengan domain frontend-mu
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

$secret_key = "your_secret_key";

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Mendapatkan token dari header Authorization
$headers = getallheaders();
error_log("Token received: " . $headers['Authorization']);

if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Authorization token is missing"]);
    exit;
}

$token = str_replace("Bearer ", "", $headers['Authorization']);

try {
    // Decode token
    $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));

    // Kirim data user
    echo json_encode([
        "status" => "success",
        "message" => "Token is valid",
        "data" => [
            "user" => [
                "id" => 1,
                "name" => "John Doe",
                "role" => "admin",
                "email" => $decoded->email
            ],
            "token" => $token
        ]
    ]);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Invalid or expired token"]);
}
