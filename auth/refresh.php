<?php
require '../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Content-Type: application/json");

// Secret key for JWT
$secret_key = "your_secret_key";

// Check if refresh token exists in HTTP-only cookie
if (!isset($_COOKIE['refresh_token'])) {
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
        "access_token" => $new_access_token
    ]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Invalid refresh token"]);
}
