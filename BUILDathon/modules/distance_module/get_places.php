<?php
header('Content-Type: application/json');
require_once '../../includes/db.php';

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

// Predefined cities with their coordinates
$cities = [
    'Delhi' => ['lat' => 28.6139, 'lng' => 77.2090],
    'Mumbai' => ['lat' => 19.0760, 'lng' => 72.8777],
    'Chandigarh' => ['lat' => 30.7333, 'lng' => 76.7794]
];

$lat = $_GET['lat'];
$lng = $_GET['lng'];

// Find nearest city
$closestCity = null;
$minDistance = PHP_INT_MAX;

foreach ($cities as $city => $coords) {
    $dist = haversine($lat, $lng, $coords['lat'], $coords['lng']);
    if ($dist < $minDistance) {
        $minDistance = $dist;
        $closestCity = $city;
    }
}

$stmt = $pdo->prepare("SELECT * FROM places WHERE city = ?");
$stmt->execute([$closestCity]);
$results = [];

while ($row = $stmt->fetch()) {
    $row['distance'] = haversine($lat, $lng, $row['lat'], $row['lng']);
    $results[] = $row;
}

usort($results, fn($a, $b) => $a['distance'] <=> $b['distance']);

echo json_encode([
    'city' => $closestCity,
    'places' => $results
]);
?>
