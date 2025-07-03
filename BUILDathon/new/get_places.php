<?php
header('Content-Type: application/json');
require_once './includes/db.php';

function haversine($lat1, $lon1, $lat2, $lon2) {
    $R = 6371;
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) * sin($dLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $R * $c;
}

$lat = $_GET['lat'];
$lng = $_GET['lng'];
$stmt = $pdo->query("SELECT * FROM places");
$results = [];
while ($row = $stmt->fetch()) {
    $row['distance'] = haversine($lat, $lng, $row['lat'], $row['lng']);
    $results[] = $row;
}
usort($results, fn($a, $b) => $a['distance'] <=> $b['distance']);
echo json_encode($results);
?>