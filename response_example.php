<?php
// Tunggu selama 2 detik
sleep(2);

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Ambil status dari query parameter, default ke 200 jika tidak diberikan
$status = isset($_GET['status']) ? intval($_GET['status']) : 200;

$status = 200;

// Set response header sesuai status yang diberikan
http_response_code($status);

// Tampilkan pesan sesuai status
$response_messages = [
    200 => "OK - Request successful",
    400 => "Bad Request - Invalid input",
    401 => "Unauthorized - Authentication required",
    403 => "Forbidden - Access denied",
    404 => "Not Found - Resource not found",
    500 => "Internal Server Error - Something went wrong",
];

// Tampilkan pesan sesuai status, jika tidak ada gunakan pesan default
$message = isset($response_messages[$status]) ? $response_messages[$status] : "Custom HTTP Status $status";

// Tampilkan JSON response
header('Content-Type: application/json');
echo json_encode([
    "status" => $status,
    "message" => $message
]);
