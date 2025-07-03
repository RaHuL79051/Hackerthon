<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

$transportHubs = $pdo->query("SELECT * FROM locations WHERE type = 'transport'")->fetchAll();
$touristSpots = $pdo->query("SELECT * FROM locations WHERE type = 'tourist'")->fetchAll();

$distance = null;
$nearbyPlaces = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fromId = $_POST['from'];
    $toId = $_POST['to'];
    
    $stmt = $pdo->prepare("SELECT * FROM distances WHERE from_id = ? AND to_id = ?");
    $stmt->execute([$fromId, $toId]);
    $distance = $stmt->fetch();
    
    $stmt = $pdo->prepare("SELECT latitude, longitude FROM locations WHERE id = ?");
    $stmt->execute([$fromId]);
    $fromLocation = $stmt->fetch();
    
    if ($fromLocation) {
        $stmt = $pdo->prepare("
            SELECT *, 
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
            cos(radians(longitude) - radians(?)) + sin(radians(?)) * 
            sin(radians(latitude)))) AS distance_km 
            FROM locations 
            WHERE type = 'tourist' AND id != ?
            HAVING distance_km < 5 
            ORDER BY distance_km ASC 
            LIMIT 3
        ");
        $stmt->execute([
            $fromLocation['latitude'],
            $fromLocation['longitude'],
            $fromLocation['latitude'],
            $toId
        ]);
        $nearbyPlaces = $stmt->fetchAll();
    }
}
?>
<head>
    <link rel="stylesheet" href="./distance.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>
<body>
<div class="module-container">
    <h2>📍 Advanced Distance Calculator</h2>
    
    <form method="POST" class="distance-form">
        <div class="form-group">
            <label>Starting Point (Transport Hub):</label>
            <select name="from" required>
                <option value="">-- Select Hub --</option>
                <?php foreach ($transportHubs as $hub): ?>
                    <option value="<?= $hub['id'] ?>"><?= $hub['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Destination (Tourist Spot):</label>
            <select name="to" required>
                <option value="">-- Select Destination --</option>
                <?php foreach ($touristSpots as $spot): ?>
                    <option value="<?= $spot['id'] ?>"><?= $spot['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <button type="submit">Calculate Distance</button>
    </form>
    
    <?php if ($distance): ?>
        <div class="result">
            <h3>Distance: <?= $distance['distance_km'] ?> km</h3>
            <p>From <strong><?= $distance['from_name'] ?></strong> to <strong><?= $distance['to_name'] ?></strong></p>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($nearbyPlaces)): ?>
        <div class="nearby-places">
            <h3>✨ Nearby Places from Starting Point:</h3>
            <div class="places-grid">
                <?php foreach ($nearbyPlaces as $place): ?>
                    <div class="place-card">
                        <h4><?= $place['name'] ?></h4>
                        <p>Only <?= number_format($place['distance_km'], 1) ?> km away</p>
                        <p class="place-type"><?= ucfirst($place['type']) ?> Spot</p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    <script src="script.js" defer></script>
</div>
<!-- jQuery and Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
  $(document).ready(function() {
    $('select').select2({
      placeholder: "Select a location",
      allowClear: true
    });
  });
</script>
</body>
<?php require_once '../../includes/footer.php'; ?>