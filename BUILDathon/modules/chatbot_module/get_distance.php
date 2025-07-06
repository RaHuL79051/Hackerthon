<?php
require_once '../../includes/db.php';
$city = $_GET['city'] ?? '';
$station = $_GET['station'] ?? '';

$stmt = $pdo->prepare("SELECT distance_km FROM transport_distances WHERE city = ? AND station_name = ?");
$stmt->execute([$city, $station]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo json_encode(['distance' => $row['distance_km']]);
} else {
    echo json_encode(['distance' => null]);
}
?>
