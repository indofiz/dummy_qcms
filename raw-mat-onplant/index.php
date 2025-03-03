<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Mengizinkan semua origin
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

date_default_timezone_set('UTC');
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

if ($method === 'POST') {
    if (!$input || !isset($input['raw_mat_id']) || !isset($input['plant_id'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid input data',
            'data' => null
        ], JSON_PRETTY_PRINT);
        exit;
    }

    $newData = [
        'id' => rand(1, 100),
        'raw_mat_id' => $input['raw_mat_id'],
        'plant_id' => $input['plant_id']
    ];

    echo json_encode([
        'status' => 'success',
        'message' => 'Data successfully added',
        'data' => $newData
    ], JSON_PRETTY_PRINT);
    exit;
}

if ($method === 'PATCH') {
    if (!$input || !isset($input['id'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID is required for update',
            'data' => null
        ], JSON_PRETTY_PRINT);
        exit;
    }

    $updatedData = [
        'id' => $input['id'],
        'raw_mat_id' => $input['raw_mat_id'] ?? 0,
        'plant_id' => $input['plant_id'] ?? 0
    ];

    echo json_encode([
        'status' => 'success',
        'message' => 'Data successfully updated',
        'data' => $updatedData
    ], JSON_PRETTY_PRINT);
    exit;
}

if ($method === 'DELETE') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'ID is required for deletion'
        ], JSON_PRETTY_PRINT);
        exit;
    }

    http_response_code(204);
    exit;
}

echo json_encode([
    'status' => 'success',
    'message' => 'Data retrieved successfully',
    'data' => array_map(function ($i) {
        return [
            'id' => $i,
            'raw_mat_id' => rand(1, 50),
            'plant_id' => rand(1, 20),
            'created_at' => date('Y-m-d H:i:s', strtotime("-{$i} days")),
            'updated_at' => date('Y-m-d H:i:s', strtotime("-{$i} hours"))
        ];
    }, range(1, 20))
], JSON_PRETTY_PRINT);
