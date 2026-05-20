<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$dataFile = __DIR__ . '/shop-data.json';

// Инициализация файла если не существует
if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode([
        'products' => [],
        'users' => [],
        'orders' => [],
        'customStatuses' => []
    ]));
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'getAll':
        echo file_get_contents($dataFile);
        break;
        
    case 'saveAll':
        $input = json_decode(file_get_contents('php://input'), true);
        if ($input) {
            file_put_contents($dataFile, json_encode($input, JSON_UNESCAPED_UNICODE));
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid data']);
        }
        break;
        
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action']);
}
?>
