<?php

header('Content-Type: application/json');

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
