<?php


require '../vendor/autoload.php';

use Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: http://localhost:5173"); // Ganti dengan domain frontend-mu
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


header("Content-Type: application/json");

// Dummy user data
$users = [
    "user@example.com" => "Password!123"
];

// Secret key for JWT
$secret_key = "your_secret_key";

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email']) || !isset($data['password'])) {
    echo json_encode(["status" => "error", "message" => "Email and password are required"]);
    exit;
}

$email = $data['email'];
$password = $data['password'];

// Verify user credentials
if (isset($users[$email]) && $users[$email] === $password) {
    // Generate access token
    $access_payload = [
        "email" => $email,
        "iat" => time(),
        "exp" => time() + (60 * 60) // 1 hour expiration
    ];
    $access_token = JWT::encode($access_payload, $secret_key, 'HS256');

    // Generate refresh token
    $refresh_payload = [
        "email" => $email,
        "iat" => time(),
        "exp" => time() + (7 * 24 * 60 * 60) // 7 days expiration
    ];
    $refresh_token = JWT::encode($refresh_payload, $secret_key, 'HS256');

    // Set refresh token in HTTP-only cookie
    setcookie("refresh_token", $refresh_token, [
        "httponly" => true,
        "secure" => false, // Enable if using HTTPS
        // "samesite" => "None"
    ]);

    echo json_encode([
        "status" => "success",
        "message" => "Login successful",
        "data" => [
            "user" => [
                "id" => 1,
                "name" => "John Doe",
                "role" => "admin",
                "email" => $email
            ],
            "token" => $access_token
        ]
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid email or password"]);
}
