<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Mengizinkan semua origin
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

date_default_timezone_set('UTC');
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

if ($method === 'POST') {
    if (!$input || !isset($input['material_name']) || !isset($input['category_id']) || !isset($input['formula']) || !isset($input['description'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid input data',
            'data' => null
        ], JSON_PRETTY_PRINT);
        exit;
    }

    $newData = [
        'id' => rand(1, 100),
        'material_name' => $input['material_name'],
        'category_id' => $input['category_id'],
        'formula' => $input['formula'],
        'description' => $input['description'],
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
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
        'material_name' => $input['material_name'] ?? '',
        'category_id' => $input['category_id'] ?? 0,
        'formula' => $input['formula'] ?? '',
        'description' => $input['description'] ?? '',
        'updated_at' => date('Y-m-d H:i:s')
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
            'material_name' => "Material {$i}",
            'category_id' => rand(1, 10),
            'formula' => "H2O{$i}",
            'description' => "Description for Material {$i}",
            'created_at' => date('Y-m-d H:i:s', strtotime("-{$i} days")),
            'updated_at' => date('Y-m-d H:i:s', strtotime("-{$i} hours"))
        ];
    }, range(1, 20))
], JSON_PRETTY_PRINT);
