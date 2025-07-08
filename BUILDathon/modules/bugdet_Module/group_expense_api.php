<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'unauthorized']);
    exit;
}
include_once "../../includes/db.php";

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $payer = $_POST['payer'];
        $amount = floatval($_POST['amount']);
        $desc = trim($_POST['description']);
        $stmt = $pdo->prepare("INSERT INTO group_expenses (user_id, group_name, payer_name, amount, description) VALUES (?, 'default', ?, ?, ?)");
        $stmt->execute([$user_id, $payer, $amount, $desc]);
        echo json_encode(['status' => 'success']);
        exit;
    }

    if ($action === 'delete') {
        $id = intval($_POST['id']);
        $stmt = $pdo->prepare("DELETE FROM group_expenses WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user_id]);
        echo json_encode(['status' => 'success']);
        exit;
    }

    if ($action === 'update') {
        $id = intval($_POST['id']);
        $payer = $_POST['payer'];
        $amount = floatval($_POST['amount']);
        $desc = trim($_POST['description']);
        $stmt = $pdo->prepare("UPDATE group_expenses SET payer_name = ?, amount = ?, description = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$payer, $amount, $desc, $id, $user_id]);
        echo json_encode(['status' => 'success']);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("SELECT * FROM group_expenses WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}
