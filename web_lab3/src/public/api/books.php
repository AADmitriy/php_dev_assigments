<?php
header('Content-Type: application/json; charset=utf-8');
require __DIR__ . '/../../config/db.php';

// Read optional filters from the query string: ?search=...&genre=...&max_price=...
$search   = trim($_GET['search'] ?? '');
$maxPrice = $_GET['max_price'] ?? '';

$sql = 'SELECT id, title, author, price, img_url FROM books WHERE 1=1';
$params = [];

if ($search !== '') {
    $sql .= ' AND (title LIKE :search OR author LIKE :search)';
    $params[':search'] = '%' . $search . '%';
}
if ($maxPrice !== '' && is_numeric($maxPrice)) {
    $sql .= ' AND price <= :max_price';
    $params[':max_price'] = $maxPrice;
}

$sql .= ' ORDER BY title';

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($books);   // PHP array -> JSON
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}