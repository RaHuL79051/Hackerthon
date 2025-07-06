<?php
require_once '../../includes/db.php';

$parent_id = isset($_GET['parent_id']) ? intval($_GET['parent_id']) : null;

if ($parent_id === null) {
    $stmt = $pdo->prepare("SELECT * FROM chatbot_nodes WHERE parent_id IS NULL");
    $stmt->execute();
} else {
    $stmt = $pdo->prepare("SELECT * FROM chatbot_nodes WHERE parent_id = ?");
    $stmt->execute([$parent_id]);
}

$nodes = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $nodes[] = $row;
}
header('Content-Type: application/json');
echo json_encode($nodes);
?>
