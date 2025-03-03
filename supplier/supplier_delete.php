<?php if ($method === 'DELETE') {
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
