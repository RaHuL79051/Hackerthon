<?php
require_once '../../includes/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($id === null) {
    // Fetch the root node (parent_id IS NULL, limit 1)
    $stmt = $pdo->prepare("SELECT * FROM chatbot_nodes WHERE parent_id IS NULL LIMIT 1");
    $stmt->execute();
} else {
    // Fetch node by id
    $stmt = $pdo->prepare("SELECT * FROM chatbot_nodes WHERE id = ?");
    $stmt->execute([$id]);
}

$node = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($node);
