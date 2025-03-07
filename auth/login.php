<?php
require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// Dummy data admin
$admin = [
    'email' => 'testing@gmail.com',
    'password' => password_hash('admin', PASSWORD_BCRYPT), // Hash password
    'id' => 2,
    'name' => 'Add your name in the body',
    'created_at' => '2024-11-17T11:30:13.000000Z',
    'updated_at' => '2024-11-17T11:30:13.000000Z'
];

$secret_key = "your_secret_key";
$issued_at = time();
$expiration_time = $issued_at + (60 * 60); // Token berlaku 1 jam
$refresh_expiration = $issued_at + (60 * 60 * 24 * 7); // Refresh token berlaku 7 hari

// Mendapatkan input dari request
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['email']) || !isset($input['password'])) {
    echo json_encode(["status" => "error", "message" => "Email atau password salah"]);
    exit;
}

// Validasi user
if ($input['email'] === $admin['email'] && password_verify($input['password'], $admin['password'])) {

    $payload = [
        "iss" => "http://qcms_dummy.test/login.php",
        "iat" => $issued_at,
        "exp" => $expiration_time,
        "sub" => $admin['id'],
    ];

    $token = JWT::encode($payload, $secret_key, 'HS256');
    $refresh_token = JWT::encode(["sub" => $admin['id'], "exp" => $refresh_expiration], $secret_key, 'HS256');

    echo json_encode([
        "status" => "success",
        "message" => "Berhasil login",
        "data" => [
            "user" => [
                "id" => $admin['id'],
                "name" => $admin['name'],
                "email" => $admin['email'],
                "email_verified_at" => null,
                "created_at" => $admin['created_at'],
                "updated_at" => $admin['updated_at'],
            ],
            "token" => $token,
            "refresh_token" => $refresh_token
        ]
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Email atau password salah"]);
}
