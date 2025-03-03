<?php

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['supplier_name'], $input['phone'], $input['email'], $input['address'], $input['sub_district'], $input['district'], $input['city'], $input['province'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid input data',
            'data' => null
        ], JSON_PRETTY_PRINT);
        exit;
    }

    $newData = [
        'id' => rand(21, 100),
        'supplier_name' => $input['supplier_name'],
        'phone' => $input['phone'],
        'email' => $input['email'],
        'address' => $input['address'],
        'sub_district' => $input['sub_district'],
        'district' => $input['district'],
        'city' => $input['city'],
        'province' => $input['province'],
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

echo json_encode([
    'status' => 'success',
    'message' => 'Data retrieved successfully',
    'data' => array_map(function ($i) {
        return [
            'id' => $i,
            'supplier_name' => "Supplier $i",
            'phone' => '08' . rand(1000000000, 9999999999),
            'email' => "supplier$i@example.com",
            'address' => "Jl. Contoh No. $i",
            'sub_district' => "Kecamatan $i",
            'district' => "Kabupaten $i",
            'city' => "Kota $i",
            'province' => "Provinsi $i",
            'created_at' => date('Y-m-d H:i:s', strtotime("-{$i} days")),
            'updated_at' => date('Y-m-d H:i:s', strtotime("-{$i} hours"))
        ];
    }, range(1, 20))
], JSON_PRETTY_PRINT);
