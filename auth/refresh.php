<?php

require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header("Content-Type: application/json");

// Secret key for JWT
$secret_key = "your_secret_key";

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Check if refresh token exists in HTTP-only cookie
if (!isset($_COOKIE['refresh_token'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Refresh token is required"]);
    exit;
}

$refresh_token = $_COOKIE['refresh_token'];

try {
    $decoded = JWT::decode($refresh_token, new Key($secret_key, 'HS256'));

    // Generate new access token
    $new_payload = [
        "email" => $decoded->email,
        "iat" => time(),
        "exp" => time() + (60 * 60) // New token expires in 1 hour
    ];

    $new_access_token = JWT::encode($new_payload, $secret_key, 'HS256');

    echo json_encode([
        "status" => "success",
        "message" => "Token refreshed successfully",
        "user" => [
            "id" => 1,
            "name" => "John Doe",
            "role" => "admin",
            "email" => $decoded->email
        ],
        "token" => $new_access_token
    ]);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Invalid or expired refresh token"]);
}